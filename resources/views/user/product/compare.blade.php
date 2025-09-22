@extends('layout.user')

@section('content')
<style>
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}
.animate-float { animation: float 3s ease-in-out infinite; }

@keyframes fade-in { from { opacity: 0; transform: scale(0.95); } to { transform: scale(1); } }
.animate-fade-in { animation: fade-in 0.5s ease-out; }

.sticky-col {
    position: sticky; left: 0; background: #f9fafb; z-index: 1;
    box-shadow: 2px 0 5px -2px rgba(0,0,0,0.05);
}

.marquee-container { position: relative; height: 2rem; }
.marquee { position: absolute; white-space: nowrap; will-change: transform; animation: marquee-linear 15s linear infinite; }
@keyframes marquee-linear { 0% { transform: translateX(100%); } 100% { transform: translateX(-100%); } }

table td, table th { border-collapse: collapse; }

/* Mobile responsive */
@media (max-width: 640px) {
    #compare-table-wrapper table {
        display: none;
    }
    #compare-table-wrapper .compare-cards {
        display: block;
    }
}
</style>

<div class="container mx-auto py-10 pt-[98px]" id="compare-container">
    <div class="flex items-center text-sm text-gray-600 space-x-2 mb-4">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Trang chủ</a>
        <span class="text-gray-400">›</span>
        <span class="text-gray-800 font-medium">So sánh sản phẩm</span>
    </div>

    <div id="compare-table-wrapper"></div>
</div>

@endsection

@push('scripts')
<script>
const allProducts = @json($products); // tất cả sản phẩm từ server
const category = "{{ request()->segment(2) }}";
const compareContainer = document.getElementById('compare-table-wrapper');

function getCompareList() {
    const compareList = JSON.parse(localStorage.getItem('compare_products')) || {};
    return compareList[category] || [];
}

function renderCompareTable() {
    const compareIds = getCompareList();
    if (compareIds.length < 1) {
        compareContainer.innerHTML = `
        <div class="text-gray-500 text-center py-12 bg-white rounded-xl shadow flex flex-col items-center space-y-5 animate-fade-in">
            <img src="https://cdn-icons-png.flaticon.com/512/747/747376.png" alt="So sánh sản phẩm"
                class="w-24 h-24 opacity-80 animate-float">
            <div class="text-lg font-semibold">
                Bạn cần chọn ít nhất <strong class="text-blue-600">2 sản phẩm</strong> để so sánh.
            </div>
            <div class="marquee-container overflow-hidden w-full max-w-md mx-auto border-t border-b border-gray-200">
                <div class="marquee whitespace-nowrap text-sm text-gray-400 py-1">
                    Hãy quay lại và chọn thêm sản phẩm để trải nghiệm tính năng so sánh tuyệt vời!
                </div>
            </div>
        </div>`;
        return;
    }

    const products = allProducts.filter(p => compareIds.includes(p.id));
    const rows = [
        {label:'Giá', cb: p => p.variants.map(v=>parseInt(v.price)).filter(Boolean).filter((v,i,a)=>a.indexOf(v)===i).map(v=>v.toLocaleString('vi-VN')+'₫').join(' / ') || 'N/A'},
        {label:'Bộ nhớ', cb: p => p.variants.map(v=>v.storage).filter(Boolean).join(' / ') || 'N/A'},
        {label:'RAM', cb: p => p.variants.map(v=>v.ram).filter(Boolean).join(' / ') || 'N/A'},
        {label:'Màu sắc', cb: p => p.variants.map(v=>v.color).filter(Boolean).join(' / ') || 'N/A'},
        {label:'Màn hình', cb: p => p.variants.map(v=>v.screen_size).filter(Boolean).join(' / ') || 'N/A'},
        {label:'Trọng lượng', cb: p => p.variants.map(v=>v.weight).filter(Boolean).join(' / ') || 'N/A'},
        {label:'Pin', cb: p => p.variants.map(v=>v.battery).filter(Boolean).join(' / ') || 'N/A'},
        {label:'Chip', cb: p => p.variants.map(v=>v.chip).filter(Boolean).join(' / ') || 'N/A'},
        {label:'Hệ điều hành', cb: p => p.variants.map(v=>v.operating_system).filter(Boolean).join(' / ') || 'N/A'},
        {label:'Đã bán', cb: p => p.variants.reduce((sum,v)=>sum+(v.sold||0),0)},
    ];

    // bảng ngang desktop
    let html = `<div class="overflow-auto bg-white rounded-xl shadow-xl ring-1 ring-gray-200">
    <table class="table-auto min-w-[900px] w-full text-sm text-gray-800 border-separate border-spacing-0">
        <thead class="sticky top-0 z-10 bg-blue-50 text-xs uppercase text-gray-600">
            <tr>
                <th class="p-4 border-r sticky-col font-bold text-left bg-blue-100">Thuộc tính</th>`;
    products.forEach(p => {
        const img = p.variants[0]?.images?.[0]?.image_path;
        html += `<th class="p-4 text-center relative group align-top min-w-[220px] bg-white" data-id="${p.id}">
            <div class="flex flex-col items-center">
                ${img ? `<img src="/storage/${img}" alt="${p.name}" class="h-28 object-contain rounded-xl shadow mb-2">` 
                      : `<div class="w-full h-28 flex items-center justify-center text-gray-400 bg-gray-50 rounded mb-2">Không có ảnh</div>`}
                <div class="text-base font-semibold text-gray-800 mb-1 text-center truncate max-w-[180px]">${p.name}</div>
            </div>
            <button onclick="removeFromCompare(${p.id})"
                class="absolute top-2 right-2 w-6 h-6 flex items-center justify-center bg-red-500 text-white text-sm rounded-full hover:bg-red-600">&times;</button>
        </th>`;
    });
    html += `</tr></thead><tbody>`;
    rows.forEach(r => {
        html += `<tr class="border-t hover:bg-gray-50 transition">
            <td class="p-4 font-medium text-gray-700 sticky-col border-r">${r.label}</td>`;
        products.forEach(p => html += `<td class="p-4 text-center">${r.cb(p)}</td>`);
        html += `</tr>`;
    });
    html += `</tbody></table></div>`;

    // card dọc mobile
    let cards = `<div class="compare-cards hidden space-y-4">`;
    products.forEach(p => {
        const img = p.variants[0]?.images?.[0]?.image_path;
        cards += `<div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-3">
                    ${img ? `<img src="/storage/${img}" class="h-16 w-16 object-contain rounded">` 
                          : `<div class="h-16 w-16 flex items-center justify-center bg-gray-50 text-gray-400">N/A</div>`}
                    <div>
                        <div class="font-semibold text-gray-800">${p.name}</div>
                    </div>
                </div>
                <button onclick="removeFromCompare(${p.id})" class="w-6 h-6 flex items-center justify-center bg-red-500 text-white text-sm rounded-full">&times;</button>
            </div>`;
        rows.forEach(r => {
            cards += `<div class="flex justify-between border-t py-2 text-sm">
                <span class="font-medium text-gray-600">${r.label}</span>
                <span class="text-gray-800 text-right">${r.cb(p)}</span>
            </div>`;
        });
        cards += `</div>`;
    });
    cards += `</div>`;

    compareContainer.innerHTML = html + cards;
}

function removeFromCompare(productId) {
    const compareList = JSON.parse(localStorage.getItem('compare_products')) || {};
    compareList[category] = (compareList[category] || []).filter(id => id !== productId);
    localStorage.setItem('compare_products', JSON.stringify(compareList));
    renderCompareTable();
}

// Render khi load trang
document.addEventListener('DOMContentLoaded', renderCompareTable);
</script>
@endpush
