@extends('layout.user')

@section('content') 
<div class="container mx-auto pt-20 pb-10">
    {{-- Breadcrumb --}}
    <div class="flex items-center text-sm text-gray-600 space-x-2 mb-4">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Trang chủ</a>
        <span class="text-gray-400">›</span>
        <span class="text-gray-800 font-medium">Laptop</span>
    </div>
    <div class="bg-white p-6 rounded-xl shadow space-y-6 mb-8">
        <form method="GET" id="filterForm"></form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
            {{-- Thương hiệu --}}
            @if ($brands->count())
                <div>
                    <h3 class="text-base font-semibold text-gray-800 mb-3">Thương hiệu</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($brands as $brand)
                            <button type="button"
                                class="btn-filter px-3 py-2 rounded-full border text-sm flex items-center gap-2 transition
                                    {{ in_array($brand->id, request('brand_ids', [])) ? 'bg-blue-500 text-white border-blue-500 hover:bg-blue-600' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-100' }}"
                                data-name="brand_ids[]"
                                data-value="{{ $brand->id }}">
                                @if($brand->logo)
                                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="w-12 h-4 object-contain">
                                @else
                                    {{ $brand->name }}
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Lọc theo giá --}}
            <div>
                <h3 class="text-base font-semibold text-gray-800 mb-3">Lọc theo giá</h3>
                <div class="flex flex-wrap gap-2 text-sm">
                    @foreach(['under_15' => 'Dưới 15 triệu', 'from_15_to_30' => '15 – 30 triệu', 'over_30' => 'Trên 30 triệu'] as $key => $label)
                        <button type="button"
                            class="btn-filter px-3 py-2 rounded-full border transition
                                {{ in_array($key, request('price_ranges', [])) ? 'bg-blue-500 text-white border-blue-500 hover:bg-blue-600' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-100' }}"
                            data-name="price_ranges[]"
                            data-value="{{ $key }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Sắp xếp --}}
            {{-- <div>
                <h3 class="text-base font-semibold text-gray-800 mb-3">Sắp xếp</h3>
                <div class="flex flex-wrap gap-2 text-sm">
                    @foreach(['price_asc' => 'Giá thấp → cao', 'price_desc' => 'Giá cao → thấp'] as $sortValue => $label)
                        <button type="button"
                            class="btn-filter px-3 py-2 rounded-full border transition
                                {{ request('sort') === $sortValue ? 'bg-blue-500 text-white border-blue-500 hover:bg-blue-600' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-100' }}"
                            data-name="sort"
                            data-value="{{ $sortValue }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div> --}}
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">
        

            

        {{-- Danh sách sản phẩm --}}
        @if($products->count())
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($products as $product)
                    @php
                        $firstVariant = $product->variants->first();
                        $image = optional($firstVariant?->images->first())->image_path;
                        $price = $firstVariant?->price;
                        $originalPrice = $firstVariant?->original_price;
                    @endphp

                    <a href="{{ route('product.detail', $product->slug) }}"
                       class="group bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition hover:border-blue-400">
                        {{-- Ảnh --}}
                        @if($image)
                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}"
                                 class="w-full h-40 md:h-44 object-contain bg-white p-2">
                        @else
                            <div class="w-full h-44 bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
                                Không có ảnh
                            </div>
                        @endif

                        {{-- Nội dung --}}
                        <div class="p-4 relative">
                            <h3 class="text-sm font-semibold text-gray-800 group-hover:text-blue-600 truncate">
                                {{ $product->name }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">Bộ nhớ: {{ $product->all_storages ?? 'N/A' }}</p>

                            {{-- Giá --}}
                            @if($price)
                                <div class="mt-2">
                                    <span class="text-red-500 font-bold">
                                        {{ number_format($price, 0, ',', '.') }}₫
                                    </span>
                                    @if($originalPrice && $originalPrice > $price)
                                        <span class="text-sm text-gray-400 line-through ml-2">
                                            {{ number_format($originalPrice, 0, ',', '.') }}₫
                                        </span>
                                    @endif
                                    @if($product->sale_percent > 0)
                                        <span class="ml-2 text-xs text-green-600 font-semibold bg-green-100 px-2 py-0.5 rounded">
                                            -{{ $product->sale_percent }}%
                                        </span>
                                    @endif
                                </div>
                            @else
                                <div class="text-sm text-gray-400 mt-2">Chưa có giá</div>
                            @endif
                            <div class="flex justify-between items-center mt-4">
                                <button
                                    type="button"
                                    onclick="event.stopPropagation(); event.preventDefault(); addToCompare({{ $product->id }}, '{{ request()->segment(1) }}')"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs border rounded-full text-blue-600 border-blue-500 hover:bg-blue-500 hover:text-white transition"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h8m-8 6h16" />
                                    </svg>
                                    So sánh
                                </button>

                                <div class="text-xs text-gray-500 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18M9 3v18m6-18v18" />
                                    </svg>
                                    <span>Đã bán: {{ $product->variants->sum('sold') }}</span>
                                </div>
                            </div>
                                
                        </div>
                        {{-- <button
                            type="button"
                            onclick="event.stopPropagation(); event.preventDefault(); addToCompare({{ $product->id }}, '{{ request()->segment(1) }}')"
                            class="mt-3 inline-flex items-center justify-center gap-1 px-3 py-1.5 text-xs border rounded-full text-blue-600 border-blue-500 hover:bg-blue-500 hover:text-white transition"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 6h16M4 12h8m-8 6h16" />
                            </svg>
                                So sánh
                        </button> --}}
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 mt-4">Không tìm thấy sản phẩm nào phù hợp với bộ lọc.</p>
        @endif
    </div>
    <button onclick="goToComparePage('{{ request()->segment(1) }}')" 
                                        class="fixed bottom-5 right-5 px-4 py-2 bg-blue-600 text-white rounded shadow-lg z-50">
                                    So sánh (<span id="compareCount">0</span>)
                                </button>
    <div class="mt-4 flex justify-center">
        {{-- Phân trang --}}
        {{ $products->appends(request()->except('page'))->links('pagination.custom-user') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const currentParams = new URLSearchParams(window.location.search);
    const pathname = window.location.pathname;
    function updateCompareCount() {
        const category = "{{ request()->segment(1) }}";
        const compareList = JSON.parse(localStorage.getItem('compare_products')) || {};
        const selected = compareList[category] || [];
        const countEl = document.getElementById('compareCount');
        const btnCompare = document.querySelector('[onclick^="goToComparePage"]');

        countEl.textContent = selected.length;

        // Ẩn hoặc hiện nút "So sánh"
        if (selected.length === 0) {
            btnCompare.style.display = 'none';
        } else {
            btnCompare.style.display = 'block';
        }
    }


    updateCompareCount();



    document.querySelectorAll('.btn-filter').forEach(btn => {
        btn.addEventListener('click', () => {
            const name = btn.getAttribute('data-name');
            const value = btn.getAttribute('data-value');

            // Nếu là mảng (checkbox price_ranges[])
            if (name.endsWith('[]')) {
                const allValues = currentParams.getAll(name);
                if (allValues.includes(value)) {
                    // Bỏ chọn
                    const newValues = allValues.filter(v => v !== value);
                    currentParams.delete(name);
                    newValues.forEach(v => currentParams.append(name, v));
                } else {
                    // Thêm chọn
                    currentParams.append(name, value);
                }
            } else {
                // Nếu đã chọn rồi → bỏ đi
                if (currentParams.get(name) === value) {
                    currentParams.delete(name);
                } else {
                    currentParams.set(name, value);
                }
            }

           
           // Xoá tham số page khi thay đổi bộ lọc
            currentParams.delete('page');

            // Redirect với query mới
            const newUrl = pathname + (currentParams.toString() ? '?' + currentParams.toString() : '');
            window.location.href = newUrl;


            // Cập nhật số lượng so sánh
            const category = "{{ request()->segment(1) }}";
            const compareList = JSON.parse(localStorage.getItem('compare_products')) || {};
            const selected = compareList[category] || [];
            document.getElementById('compareCount').textContent = selected.length;


        });
    });
});
function getCompareList(categorySlug) {
    const compare = JSON.parse(localStorage.getItem("compare_products")) || {};
    return compare[categorySlug] || [];
}

function addToCompare(productId, categorySlug) {
    const compare = JSON.parse(localStorage.getItem("compare_products")) || {};
    compare[categorySlug] = compare[categorySlug] || [];

    if (compare[categorySlug].includes(productId)) return;
    if (compare[categorySlug].length >= 4) {
        alert("Chỉ được so sánh tối đa 4 sản phẩm.");
        return;
    }

    compare[categorySlug].push(productId);
    localStorage.setItem("compare_products", JSON.stringify(compare));
    updateCompareCount();

    alert("Đã thêm vào danh sách so sánh");
}
function goToComparePage(categorySlug) {
    const compare = JSON.parse(localStorage.getItem("compare_products")) || {};
    const ids = compare[categorySlug] || [];

    if (ids.length < 2) {
        alert("Bạn cần chọn ít nhất 2 sản phẩm để so sánh.");
        return;
    }

    // Chuyển sang trang so sánh, ví dụ: /so-sanh/dien-thoai?ids[]=1&ids[]=2
    const query = ids.map(id => `ids[]=${id}`).join('&');
    window.location.href = `/so-sanh/${categorySlug}?${query}`;
}



</script>
@endpush


