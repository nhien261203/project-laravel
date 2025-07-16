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
                            {{-- số lượng đã bán --}}
                            <div class="absolute bottom-2 right-2 text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">
                                Đã bán: {{ $product->variants->sum('sold') }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 mt-4">Không tìm thấy sản phẩm nào phù hợp với bộ lọc.</p>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const currentParams = new URLSearchParams(window.location.search);
    const pathname = window.location.pathname;

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

            // Redirect với query mới
            const newUrl = pathname + (currentParams.toString() ? '?' + currentParams.toString() : '');
            window.location.href = newUrl;
        });
    });
});
</script>
@endpush

