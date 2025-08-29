@extends('layout.user')

@section('content')
<div class="container mx-auto pt-20 pb-10 overflow-x-hidden">
    <div class="flex items-center text-sm text-gray-600 space-x-2 p-1">
        <a href="{{ route('home') }}" class="flex items-center hover:text-blue-600">
            Trang chủ
        </a>
        <span class="text-gray-400">›</span>
        <a href="{{ route('product.accessory') }}" class="text-gray-800 font-medium hover:text-blue-600">Phụ kiện</a>
        <span class="text-gray-400">›</span>
        <span class="text-gray-800 font-medium">Phụ kiện di động</span>
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

            {{-- Lọc theo giá --}}
            <div>
                <h3 class="text-base font-semibold text-gray-800 mb-3">Lọc theo giá</h3>
                <div class="flex flex-wrap gap-2 text-sm">
                    @foreach(['under_1' => 'Dưới 1 triệu', 'from_1_to_5' => '1 – 5 triệu', 'over_5' => 'Trên 5 triệu'] as $key => $label)
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

                <a href="{{ route('product.detailAccessory', $product->slug) }}"
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
                        {{-- <p class="text-xs text-gray-500 mt-1 ">{{ $product->all_storages ?? 'N/A' }}</p> --}}

                        @if($price)
                            <div class="mt-2 min-h-[3rem] md:min-h-[2rem]">
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
    //const overlay = document.getElementById('loadingOverlay');

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
            // overlay.classList.remove('pointer-events-none', 'opacity-0');
            // overlay.classList.add('opacity-100');

            // Delay để overlay render
            // setTimeout(() => {
            //     window.location.href = newUrl;
            // }, 150);

            window.location.href = newUrl;
        });
    });
});
</script>
@endpush
