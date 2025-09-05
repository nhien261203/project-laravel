<div class="bg-white p-6 rounded-xl shadow space-y-6 overflow-hidden">
    @if($accessories->count())
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-6">
            @foreach($accessories as $product)
                @php
                    $firstVariant = $product->variants->first();
                    $image = optional($firstVariant?->images->first())->image_path;
                    $price = $firstVariant?->price;
                    $originalPrice = $firstVariant?->original_price;
                @endphp

                <a href="{{ route('product.detailAccessory', $product->slug) }}"
                   class="group bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition hover:border-blue-400 relative">

                    <div class="relative w-full h-40 md:h-44 bg-white flex items-center justify-center">
                        @if($image)
                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}"
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

                    <div class="p-4">
                        <h3 class="text-sm font-semibold text-gray-800 group-hover:text-blue-600 truncate">
                            {{ $product->name }}
                        </h3>
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
                </a>
            @endforeach
        </div>

        {{-- Phân trang --}}
        
    @else
        <div class="text-center py-12">
            <img src="https://cdn-icons-png.flaticon.com/512/6134/6134065.png"
                 alt="No result"
                 class="w-40 h-40 mx-auto mb-6 opacity-80" />
            <p class="text-gray-500 text-lg">
                Không tìm thấy phụ kiện phù hợp với bộ lọc
            </p>
        </div>
    @endif
    
</div>

