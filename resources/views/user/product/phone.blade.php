@extends('layout.user')

@section('content')

<div class="container mx-auto pt-20 pb-10 overflow-x-hidden">
    {{-- Breadcrumb --}}
    <div class="flex items-center text-sm text-gray-600 space-x-2 mb-4">
        {{-- Breadcrumb Trang chủ --}}
        <a href="{{ route('home') }}" class="hover:text-blue-600">Trang chủ</a>
        <span class="text-gray-400">›</span>
        
        {{-- Breadcrumb cho danh mục cha nếu có --}}
        @if ($parentCategory)
            <a href="{{ route('product.category', $parentCategory->slug) }}" class="hover:text-blue-600">
                {{ $parentCategory->name }}
            </a>
            <span class="text-gray-400">›</span>
        @endif
        
        {{-- Breadcrumb cho danh mục hiện tại --}}
        <span class="text-gray-800 font-medium">{{ $category->name }}</span>
    </div>
    <div id="loadingOverlay"
        class="fixed inset-0 bg-black/30 flex items-center justify-center z-50 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-white p-4 rounded shadow flex items-center gap-2">
            <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            {{-- <span>Đang tải...</span> --}}
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow mb-8">
        <form method="GET" id="filterForm"></form>

        <div class="grid grid-cols-[repeat(auto-fit,minmax(250px,1fr))] gap-6 items-start">
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

            @if ($rams->count())
                <div>
                    <h3 class="text-base font-semibold text-gray-800 mb-3">RAM</h3>
                    <div class="flex flex-wrap gap-2 text-sm">
                        @foreach($rams as $ram)
                            <button type="button"
                                class="btn-filter px-3 py-2 rounded-full border transition
                                    {{ in_array($ram, request('rams', [])) ? 'bg-blue-500 text-white border-blue-500 hover:bg-blue-600' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-100' }}"
                                data-name="rams[]"
                                data-value="{{ $ram }}">
                                {{ $ram }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Bộ nhớ (Storage) --}}
            @if ($storages->count())
                <div>
                    <h3 class="text-base font-semibold text-gray-800 mb-3">Bộ nhớ</h3>
                    <div class="flex flex-wrap gap-2 text-sm">
                        @foreach($storages as $storage)
                            <button type="button"
                                class="btn-filter px-3 py-2 rounded-full border transition
                                    {{ in_array($storage, request('storages', [])) ? 'bg-blue-500 text-white border-blue-500 hover:bg-blue-600' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-100' }}"
                                data-name="storages[]"
                                data-value="{{ $storage }}">
                                {{ $storage }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Lọc theo giá --}}
            <div>
                <h3 class="text-base font-semibold text-gray-800 mb-3">Lọc theo giá</h3>
                <div class="flex flex-wrap gap-2 text-sm">
                    @foreach(['under_10' => 'Dưới 10 triệu', 'from_10_to_20' => '10 - 20 triệu', 'from_20_to_30' => '20 - 30 triệu', 'over_30' => 'Trên 30 triệu'] as $key => $label)
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

    
    {{-- Danh sách sản phẩm --}}
<div class="bg-white p-6 rounded-xl shadow space-y-6 overflow-hidden"> {{-- fix trượt ngang --}}
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
                   class="group bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition hover:border-blue-400 relative">
                    
                    {{-- Ảnh + badge sale --}}
                    <div class="relative w-full h-40 md:h-44 bg-white flex items-center justify-center">
                        @if($image)
                            <img src="{{ asset('storage/' . $image) }}" 
                                 alt="{{ $product->name }}"
                                 class="max-h-full max-w-full object-contain p-2 mt-5">
                        @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
                                Không có ảnh
                            </div>
                        @endif

                        @if($product->sale_percent > 0)
                            <span class="absolute top-1 right-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded shadow">
                                -{{ $product->sale_percent }}%
                            </span>
                        @endif
                    </div>

                    {{-- Nội dung --}}
                    <div class="p-4">
                        <h3 class="text-sm font-semibold text-gray-800 group-hover:text-blue-600 truncate">
                            {{ $product->name }}
                        </h3>
                        <p class="text-xs text-gray-500 mt-1 ">{{ $product->all_storages ?? 'N/A' }}</p>

                        @if($price)
                            <div class="mt-2 min-h-[3rem]">
                                <span class="text-red-500 font-bold">
                                    {{ number_format($price, 0, ',', '.') }}₫
                                </span>
                                @if($originalPrice && $originalPrice > $price)
                                    <span class="text-xs text-gray-400 line-through ml-2">
                                        {{ number_format($originalPrice, 0, ',', '.') }}₫
                                    </span>
                                @endif
                            </div>
                        @else
                            <div class="text-sm text-gray-400 mt-2">Chưa có giá</div>
                        @endif
                    </div>

                    {{-- Nút so sánh góc phải dưới --}}
                    {{-- <div class="text-xs text-gray-500 flex items-center gap-1">
               
                        <span>Đã bán: {{ $product->variants->sum('sold') }}</span>
                    </div> --}}
                    <button
                        type="button"
                        onclick="event.stopPropagation(); event.preventDefault(); addToCompare({{ $product->id }}, '{{ request()->segment(1) }}')"
                        class="absolute bottom-2 right-2 w-6 h-6 flex items-center justify-center rounded-full border border-blue-500 text-blue-600 hover:bg-blue-500 hover:text-white transition"
                        title="Thêm vào so sánh"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                    {{-- <button
                        type="button"
                        onclick="event.stopPropagation(); event.preventDefault(); toggleFavorite({{ $product->id }})"
                        class="absolute top-2 left-2 w-7 h-7 flex items-center justify-center rounded-full border border-gray-300 bg-white text-gray-400 opacity-0 group-hover:opacity-100 hover:text-red-500 hover:border-red-500 transition favorite-btn"
                        data-product-id="{{ $product->id }}"
                        title="Yêu thích"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 
                            2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 
                            3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 
                            3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    </button> --}}
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
                <img src="https://cdn-icons-png.flaticon.com/512/6134/6134065.png"
                    alt="No result"
                    class="w-40 h-40 mx-auto mb-6 opacity-80" />
                
                <p class="text-gray-500 text-lg">
                    Không tìm thấy sản phẩm phù hợp với bộ lọc
                    
                </p>

                
        </div>
    @endif
</div>

    <button onclick="goToComparePage('{{ request()->segment(1) }}')" 
            class="fixed bottom-[120px] right-5 px-4 py-2 bg-blue-600 text-white rounded shadow-lg z-50 hidden">
        So sánh (<span id="compareCount">0</span>)
    </button>

    <div class="mt-4 flex justify-center">
        {{-- Phân trang --}}
        {{ $products->appends(request()->except('page'))->links('pagination.custom-user') }}
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function updateCompareCount() {
        const category = "{{ request()->segment(1) }}";
        const compareList = JSON.parse(localStorage.getItem('compare_products')) || {};
        const selected = compareList[category] || [];
        const countEl = document.getElementById('compareCount');
        const btnCompare = document.querySelector('[onclick^="goToComparePage"]');

        if (countEl) countEl.textContent = selected.length;

        // Ẩn hoặc hiện nút "So sánh"
        if (btnCompare) {
            btnCompare.style.display = selected.length > 0 ? 'block' : 'none';
        }
    }

    function addToCompare(productId, categorySlug) {
        const compare = JSON.parse(localStorage.getItem("compare_products")) || {};
        compare[categorySlug] = compare[categorySlug] || [];

        if (compare[categorySlug].includes(productId)) {
            Swal.fire({
                icon: 'info',
                title: 'Đã có trong danh sách so sánh',
                toast: true,
                position: 'top-end',
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

        if (compare[categorySlug].length >= 4) {
            Swal.fire({
                icon: 'warning',
                title: 'Chỉ được so sánh tối đa 4 sản phẩm',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false
            });
            return;
        }

        compare[categorySlug].push(productId);
        localStorage.setItem("compare_products", JSON.stringify(compare));
        updateCompareCount();

        Swal.fire({
            icon: 'success',
            title: 'Đã thêm vào danh sách so sánh!',
            toast: true,
            position: 'top-end',
            timer: 2000,
            showConfirmButton: false
        });
    }

    function goToComparePage(categorySlug) {
        const compare = JSON.parse(localStorage.getItem("compare_products")) || {};
        const ids = compare[categorySlug] || [];

        if (ids.length < 2) {
            Swal.fire({
                icon: 'warning',
                title: 'Bạn cần chọn ít nhất 2 sản phẩm để so sánh.',
                toast: true,
                position: 'top-end',
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }

        const query = ids.map(id => `ids[]=${id}`).join('&');
        window.location.href = `/so-sanh/${categorySlug}?${query}`;
    }

    document.addEventListener('DOMContentLoaded', function () {
    updateCompareCount();

    const currentParams = new URLSearchParams(window.location.search);
    const pathname = window.location.pathname;
    //const overlay = document.getElementById('loadingOverlay');

    document.querySelectorAll('.btn-filter').forEach(btn => {
        btn.addEventListener('click', () => {
            const name = btn.getAttribute('data-name');
            const value = btn.getAttribute('data-value');

            if (name.endsWith('[]')) {
                const allValues = currentParams.getAll(name);
                if (allValues.includes(value)) {
                    const newValues = allValues.filter(v => v !== value);
                    currentParams.delete(name);
                    newValues.forEach(v => currentParams.append(name, v));
                } else {
                    currentParams.append(name, value);
                }
            } else {
                if (currentParams.get(name) === value) {
                    currentParams.delete(name);
                } else {
                    currentParams.set(name, value);
                }
            }

            currentParams.delete('page');
            const newUrl = pathname + (currentParams.toString() ? '?' + currentParams.toString() : '');
            

            // Hiển thị overlay trước khi reload
            // overlay.classList.remove('pointer-events-none', 'opacity-0');
            // overlay.classList.add('opacity-100');

            // Delay để overlay render
            // setTimeout(() => {
            //     window.location.href = newUrl;
            // }, 200);
            window.location.href = newUrl;
        });
    });
    

});

</script>

@endpush
