@extends('layout.user')

@section('content')
<div class="container mx-auto pt-20 pb-10">
    {{-- Breadcrumb --}}
    <div class="flex items-center text-sm text-gray-600 space-x-2 mb-4">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Trang chủ</a>
        <span class="text-gray-400">›</span>
        <span class="text-gray-800 font-medium">Kết quả tìm kiếm</span>
    </div>

    <div class="bg-white p-6 rounded-xl shadow space-y-6">
        <h2 class="text-xl md:text-2xl font-semibold text-gray-800">
            Kết quả cho: <span class="text-blue-600">"{{ $keyword }}"</span>
        </h2>

        @if($products->count())
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($products as $product)
                    @php
                        $firstVariant = $product->variants->first();
                        $image = optional($firstVariant?->images->first())->image_path;
                        $price = $firstVariant?->price;
                        $originalPrice = $firstVariant?->original_price;

                        //kiểm tra detail vì detail của accessory khác so voi laptop, đinệ thoại 
                        $slug = $product->slug;
                        $categorySlug = $product->category->slug ?? '';

                        $detailRoute = in_array($categorySlug, ['dien-thoai', 'laptop'])
                            ? route('product.detail', $slug)
                            : route('product.detailAccessory', $slug);
                    @endphp


                    <a href="{{ $detailRoute }}"
                       class="group bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition hover:border-blue-400">
                        @if($image)
                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}"
                                 class="w-full h-40 md:h-44 object-contain bg-white p-2">
                        @else
                            <div class="w-full h-44 bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
                                Không có ảnh
                            </div>
                        @endif

                        <div class="p-4">
                            <h3 class="text-sm font-semibold text-gray-800 group-hover:text-blue-600 truncate">
                                {{ $product->name }}
                            </h3>

                            @if (!empty($product->all_storages))
                                <p class="text-xs text-gray-500 mt-1">
                                    Bộ nhớ: {{ $product->all_storages }}
                                </p>
                            @endif

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

                                    @if($product->sale_percent)
                                        <span class="ml-2 text-xs text-green-600 font-semibold bg-green-100 px-2 py-0.5 rounded">
                                            -{{ $product->sale_percent }}%
                                        </span>
                                    @endif
                                </div>
                            @else
                                <div class="text-sm text-gray-400 mt-2">Chưa có giá</div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination (optional) --}}
            {{-- <div class="mt-6">
                {{ $products->appends(request()->query())->links() }}
            </div> --}}
        @else
            <p class="text-gray-500 mt-4">
                Không tìm thấy sản phẩm phù hợp với từ khóa
                <strong class="text-red-500">"{{ $keyword }}"</strong>.
            </p>
        @endif
    </div>
</div>
@endsection
