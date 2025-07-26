@extends('layout.user')

@section('content')
<div class="container mx-auto pt-20 pb-10">
    {{-- Breadcrumb --}}
    <div class="flex items-center text-sm text-gray-600 space-x-2 mb-4">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Trang chủ</a>
        <span class="text-gray-400">›</span>
        <span class="text-gray-800 font-medium">Điện thoại</span>
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
                    @foreach(['under_10' => 'Dưới 10 triệu', 'from_10_to_20' => '10 – 20 triệu', 'over_20' => 'Trên 20 triệu'] as $key => $label)
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
    <div class="bg-white p-6 rounded-xl shadow space-y-6">
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
                        @if($image)
                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}"
                                 class="w-full h-40 md:h-44 object-contain bg-white p-2">
                        @else
                            <div class="w-full h-44 bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
                                Không có ảnh
                            </div>
                        @endif

                        <div class="p-4 relative">
                            <h3 class="text-sm font-semibold text-gray-800 group-hover:text-blue-600 truncate">
                                {{ $product->name }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">Bộ nhớ: {{ $product->all_storages ?? 'N/A' }}</p>

                            @if($price)
                                <div class="mt-2 ">
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

                            @else
                                <div class="text-sm text-gray-400 mt-2">Chưa có giá</div>
                            @endif
                            <div class="flex justify-between items-center mt-4">
                                <button
                                    type="button"
                                    onclick="event.stopPropagation(); event.preventDefault(); addToCompare({{ $product->id }}, '{{ request()->segment(1) }}')"
                                    class="w-8 h-8 flex items-center justify-center rounded-full border border-blue-500 text-blue-600 hover:bg-blue-500 hover:text-white transition"
                                    title="Thêm vào so sánh"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>

                                <div class="text-xs text-gray-500 flex items-center gap-1">
                                    {{-- <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18M9 3v18m6-18v18" />
                                    </svg> --}}
                                    <span>Đã bán: {{ $product->variants->sum('sold') }}</span>
                                </div>
                            </div>

                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 mt-4">Không tìm thấy sản phẩm nào phù hợp với bộ lọc.</p>
        @endif
    </div>
    <button onclick="goToComparePage('{{ request()->segment(1) }}')" 
            class="fixed bottom-5 right-5 px-4 py-2 bg-blue-600 text-white rounded shadow-lg z-50 hidden">
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
                window.location.href = newUrl;
            });
        });
    });
</script>
@endpush
