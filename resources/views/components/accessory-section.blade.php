<div class="bg-white my-8 p-4 rounded-lg shadow">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-2 sm:gap-0">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">
            Phụ kiện chất lượng giá tốt
        </h2>

        <a href="{{ route('product.accessory') }}"
           class="text-sm sm:text-base text-blue-500 font-medium hover:text-blue-600 transition">
            Xem tất cả
        </a>
    </div>

    {{-- Mobile: Slide --}}
    <div class="xl:hidden relative">
        <div class="swiper accessory-swiper-mobile">
            <div class="swiper-wrapper">
                @foreach($accessoryProducts as $product)
                    @php
                        $variant = $product->variants->first();
                        $image = optional($variant?->images->first())->image_path;
                        $price = $variant?->price;
                        $original = $variant?->original_price;
                        $storages = $product->variants->pluck('storage')->unique()->filter()->implode(' / ');
                    @endphp

                    <div class="swiper-slide px-1">
                        <div class="bg-white shadow rounded-xl overflow-hidden hover:shadow-lg transition">
                            <a href="{{ route('product.detailAccessory', $product->slug) }}">
                                <div class="relative bg-white aspect-[5/6] flex items-center justify-center">
                                    @if($image)
                                        
                                        <img src="{{ asset('storage/' . $image) }}"
                                                alt="{{ $product->name }}"
                                                class="object-contain max-h-full max-w-full p-2 transition-transform duration-300 group-hover:-translate-y-2" loading="lazy">
                                    
                                    @else
                                        <div class="aspect-square bg-gray-100 flex items-center justify-center text-gray-400">
                                            Không có ảnh
                                        </div>
                                    @endif
                                     @if($product->sale_percent > 0)
                                        <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded shadow">
                                            -{{ $product->sale_percent }}%
                                        </span>
                                    @endif
                                </div>
                                

                                <div class="p-3">
                                    <h3 class="text-sm font-semibold text-gray-800 hover:text-blue-600 transition line-clamp-2">
                                        {{ $product->name }}
                                    </h3>
                                    {{-- <p class="text-xs text-gray-500 mt-1">Bộ nhớ: {{ $storages ?: 'N/A' }}</p> --}}

                                    @if($price)
                                        <div class="mt-2 min-h-[3rem]">
                                            <span class="text-red-500 font-bold text-base">
                                                {{ number_format($price, 0, ',', '.') }}₫
                                            </span>
                                            @if($original && $original > $price)
                                                <span class="text-xs text-gray-400 line-through ml-2">
                                                    {{ number_format($original, 0, ',', '.') }}₫
                                                </span>
                                            @endif
                                           
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-400 mt-2">Chưa có giá</div>
                                    @endif
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="swiper-button-prev !text-gray-700 !left-0"></div>
            <div class="swiper-button-next !text-gray-700 !right-0"></div>
        </div>
    </div>

    {{-- Desktop: Grid --}}
    <div class="hidden xl:grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-6">
        @foreach($accessoryProducts as $product)
            @php
                $variant = $product->variants->first();
                $image = optional($variant?->images->first())->image_path;
                $price = $variant?->price;
                $original = $variant?->original_price;
                $storages = $product->variants->pluck('storage')->unique()->filter()->implode(' / ');
            @endphp

            <div class="bg-white shadow rounded-xl overflow-hidden hover:shadow-lg transition group">
                <a href="{{ route('product.detailAccessory', $product->slug) }}">
                    <div class="relative bg-white aspect-[5/6] flex items-center justify-center">
                        @if($image)
                        
                            <img src="{{ asset('storage/' . $image) }}"
                                alt="{{ $product->name }}"
                                class="object-contain max-h-full max-w-full p-2 transition-transform duration-300 group-hover:-translate-y-2" loading="lazy">
                        
                        @else
                            <div class="aspect-square bg-gray-100 flex items-center justify-center text-gray-400">
                                Không có ảnh
                            </div>
                        @endif
                        @if($product->sale_percent > 0)
                            <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded shadow">
                                -{{ $product->sale_percent }}%
                            </span>
                        @endif

                    </div>
                    

                    <div class="p-4">
                        <h3 class="text-sm font-semibold text-gray-800 hover:text-blue-600 transition line-clamp-2">
                            {{ $product->name }}
                        </h3>
                        {{-- <p class="text-xs text-gray-500 mt-1">Bộ nhớ: {{ $storages ?: 'N/A' }}</p> --}}

                        @if($price)
                            <div class="mt-2">
                                <span class="text-red-500 font-bold">
                                    {{ number_format($price, 0, ',', '.') }}₫
                                </span>
                                @if($original && $original > $price)
                                    <span class="text-sm text-gray-400 line-through ml-2">
                                        {{ number_format($original, 0, ',', '.') }}₫
                                    </span>
                                @endif
                               
                            </div>
                        @else
                            <div class="text-sm text-gray-400 mt-2">Chưa có giá</div>
                        @endif
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.accessory-swiper-mobile', {
            slidesPerView: 2,
            spaceBetween: 12,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                768: { // iPad mini / Air
                    slidesPerView: 3,
                },
                1024: { // iPad Pro
                    slidesPerView: 4,
                },
            }
        });
    });
</script>
@endpush
