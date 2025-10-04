@extends('layout.user')

@section('content')
<style>
/* Skeleton effect */
.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 37%, #f0f0f0 63%);
    background-size: 400% 100%;
    animation: skeleton-loading 1.4s ease infinite;
}
@keyframes skeleton-loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
.marquee-container { position: relative; height: 2rem; }
.marquee { position: absolute; white-space: nowrap; will-change: transform; animation: marquee-linear 15s linear infinite; }
@keyframes marquee-linear { 0% { transform: translateX(100%); } 100% { transform: translateX(-100%); } }
</style>

<div class="container mx-auto py-10 pt-[98px]" id="compare-container">
    <div class="flex items-center text-sm text-gray-600 space-x-2 mb-4">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Trang chủ</a>
        <span class="text-gray-400">›</span>
        <span class="text-gray-800 font-medium">So sánh sản phẩm</span>
    </div>

    <!-- Skeleton loading -->
    <div id="compare-loading" class="bg-white rounded-xl shadow p-6 space-y-4">
        <div class="h-6 w-40 skeleton rounded"></div>
        <div class="grid grid-cols-3 gap-4">
            <div class="h-28 skeleton rounded"></div>
            <div class="h-28 skeleton rounded"></div>
            <div class="h-28 skeleton rounded"></div>
        </div>
        <div class="h-4 w-full skeleton rounded"></div>
        <div class="h-4 w-full skeleton rounded"></div>
        <div class="h-4 w-2/3 skeleton rounded"></div>
    </div>

    <!-- Bảng thật sẽ render vào đây -->
    <div id="compare-table-wrapper" class="hidden"></div>
</div>
@endsection

@push('scripts')
<script>
const allProducts = @json($products);
const category = "{{ request()->segment(2) }}";
const compareContainer = document.getElementById('compare-table-wrapper');
const loadingBox = document.getElementById('compare-loading');

function getCompareList() {
    const compareList = JSON.parse(localStorage.getItem('compare_products')) || {};
    return compareList[category] || [];
}

function preprocessProduct(p) {
    return {
        ...p,
        prices: [...new Set(p.variants.map(v => v.price).filter(Boolean))]
            .map(v => parseInt(v).toLocaleString('vi-VN') + '₫').join(' / ') || 'N/A',
        storages: [...new Set(p.variants.map(v => v.storage).filter(Boolean))].join(' / ') || 'N/A',
        rams: [...new Set(p.variants.map(v => v.ram).filter(Boolean))].join(' / ') || 'N/A',
        colors: [...new Set(p.variants.map(v => v.color).filter(Boolean))].join(' / ') || 'N/A',
        screens: [...new Set(p.variants.map(v => v.screen_size).filter(Boolean))].join(' / ') || 'N/A',
        weights: [...new Set(p.variants.map(v => v.weight).filter(Boolean))].join(' / ') || 'N/A',
        batteries: [...new Set(p.variants.map(v => v.battery).filter(Boolean))].join(' / ') || 'N/A',
        chips: [...new Set(p.variants.map(v => v.chip).filter(Boolean))].join(' / ') || 'N/A',
        os: [...new Set(p.variants.map(v => v.operating_system).filter(Boolean))].join(' / ') || 'N/A',
        soldTotal: p.variants.reduce((sum, v) => sum + (v.sold || 0), 0),
        thumb: p.variants[0]?.images?.[0]?.image_path || null
    };
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

    const products = allProducts.filter(p => compareIds.includes(p.id)).map(preprocessProduct);

    const rows = [
        {label:'Giá', key:'prices'},
        {label:'Bộ nhớ', key:'storages'},
        {label:'RAM', key:'rams'},
        {label:'Màu sắc', key:'colors'},
        {label:'Màn hình', key:'screens'},
        {label:'Trọng lượng', key:'weights'},
        {label:'Pin', key:'batteries'},
        {label:'Chip', key:'chips'},
        {label:'Hệ điều hành', key:'os'},
        {label:'Đã bán', key:'soldTotal'},
    ];

    let html = `<div class="overflow-auto bg-white rounded-xl shadow-xl ring-1 ring-gray-200">
    <table class="table-auto min-w-[900px] w-full text-sm text-gray-800 border-separate border-spacing-0">
        <thead class="sticky top-0 z-10 bg-blue-50 text-xs uppercase text-gray-600">
            <tr>
                <th class="p-4 border-r sticky-col font-bold text-left bg-blue-100">Ảnh</th>`;
    products.forEach(p => {
        html += `<th class="p-4 text-center relative group align-top min-w-[220px] bg-white" data-id="${p.id}">
            <div class="flex flex-col items-center">
                ${p.thumb 
                    ? `<img src="/storage/${p.thumb}" alt="${p.name}" class="h-28 object-contain rounded-xl shadow mb-2">`
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
        products.forEach(p => html += `<td class="p-4 text-center">${p[r.key]}</td>`);
        html += `</tr>`;
    });
    html += `</tbody></table></div>`;

    compareContainer.innerHTML = html;
}

/** Xoá sản phẩm khỏi danh sách */
function removeFromCompare(productId) {
    const compareList = JSON.parse(localStorage.getItem('compare_products')) || {};
    compareList[category] = (compareList[category] || []).filter(id => id !== productId);
    localStorage.setItem('compare_products', JSON.stringify(compareList));
    renderCompareTable();
}

// Render khi load trang
document.addEventListener('DOMContentLoaded', () => {
    // Hiển thị loading trước
    setTimeout(() => {
        loadingBox.classList.add('hidden');
        compareContainer.classList.remove('hidden');
        renderCompareTable();
    }, 200); // giả lập delay 0.6s
});
</script>
@endpush
