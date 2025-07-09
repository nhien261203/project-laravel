@extends('layout.user')

@section('content')
<div class="container mx-auto my-10">
    <div class="flex items-center text-sm text-gray-600 space-x-2 p-1">
        <a href="{{ route('home') }}" class="flex items-center hover:text-blue-600">
            Trang chủ
        </a>
        <span class="text-gray-400">›</span>
        <a href="{{ route('product.accessory') }}" class="text-gray-800 font-medium hover:text-blue-600">Phụ kiện</a>
        <span class="text-gray-400">›</span>
        <span class="text-gray-800 font-medium">Phụ kiện di động</span>
    </div>
    <div class="bg-white p-6 rounded-xl shadow">
        {{-- Tiêu đề --}}
        {{-- <h2 class="text-2xl font-bold text-gray-800 mb-6">📱 Danh sách phụ kiện di động</h2> --}}

        {{-- Bộ lọc theo brand --}}
        @if ($brands->count())
            <div class="flex flex-wrap gap-4 mb-8">
                @foreach($brands as $brand)
                    <a href="{{ request()->fullUrlWithQuery(['brand_id' => $brand->id]) }}"
                       class="flex items-center gap-2 bg-gray-50 border border-gray-200 px-4 py-2 rounded-xl shadow-sm hover:bg-gray-100 transition
                       {{ request('brand_id') == $brand->id ? 'ring-2 ring-blue-400' : '' }}">
                        @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="w-12 h-6 object-contain">
                        @endif
                        
                    </a>
                @endforeach
                @if(request()->has('brand_id'))
                    <a href="{{ route('product.accessory.moblie') }}"
                       class="text-sm text-blue-600 underline self-center ml-2">🔄 Reset lọc</a>
                @endif
            </div>
        @endif

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

                    <a href="{{ route('product.detailAccessory', $product->slug) }}"
                       class="group bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all duration-200 hover:border-blue-400">
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
                        <div class="p-4">
                            <h3 class="text-sm font-semibold text-gray-800 group-hover:text-blue-600 truncate">
                                {{ $product->name }}
                            </h3>
                            {{-- <p class="text-xs text-gray-500 mt-1">Bộ nhớ: {{ $product->all_storages ?? 'N/A' }}</p> --}}

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

            {{-- Phân trang --}}
            {{-- <div class="mt-8">
                {{ $products->links('pagination::tailwind') }}
            </div> --}}
        @else
            <p class="text-gray-500">Không tìm thấy sản phẩm nào.</p>
        @endif
    </div>
</div>
@endsection
