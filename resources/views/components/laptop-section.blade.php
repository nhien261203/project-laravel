<div class="bg-white my-8 p-4 rounded-lg shadow">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Laptop ưu đãi đặc biệt</h2>
        <a href="{{ route('product.laptop') }}" class="text-sm text-blue-500 hover:text-blue-600 font-medium">
            Xem tất cả 
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
        {{-- Cột trái: Banner ảnh --}}
        <div class="md:col-span-1">
            <img src="https://images.pexels.com/photos/5698417/pexels-photo-5698417.jpeg"
                 alt="Laptop Banner"
                 class="w-full h-[320px] object-cover rounded-lg shadow">
        </div>

        {{-- Cột phải: Swiper hiển thị sản phẩm --}}
        <div class="md:col-span-2 relative">
            <div class="swiper laptop-swiper">
                <div class="swiper-wrapper">
                    @foreach($laptopProducts as $product)
                        @php
                            $variant = $product->variants->first();
                            $image = optional($variant?->images->first())->image_path;
                            $price = $variant?->price;
                            $original = $variant?->original_price;
                            $salePercent = $variant && $variant->original_price && $variant->price
                                ? round(100 - ($variant->price / $variant->original_price * 100))
                                : 0;
                        @endphp

                        <div class="swiper-slide">
                            <a href="{{ route('product.detail', $product->slug) }}"
                               class="block bg-white border rounded-lg shadow hover:shadow-lg transition overflow-hidden hover:text-blue-600">
                                {{-- Ảnh sản phẩm --}}
                                <div class="relative bg-gray-50 aspect-[4/5] flex items-center justify-center">
                                    @if($image)
                                        <img src="{{ asset('storage/' . $image) }}"
                                             alt="{{ $product->name }}"
                                             class="object-contain max-h-full max-w-full p-2">
                                    @else
                                        <span class="text-gray-400 text-sm">Không có ảnh</span>
                                    @endif

                                    @if($salePercent)
                                        <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded shadow">
                                            -{{ $salePercent }}%
                                        </span>
                                    @endif
                                </div>

                                {{-- Thông tin --}}
                                <div class="p-3">
                                    <h3 class="text-sm font-semibold text-gray-800 truncate">
                                        {{ $product->name }}
                                    </h3>
                                    <div class="mt-1">
                                        @if($price)
                                            <span class="text-red-500 font-bold text-base">
                                                {{ number_format($price, 0, ',', '.') }}₫
                                            </span>
                                            @if($original && $original > $price)
                                                <span class="text-xs text-gray-400 line-through ml-2">
                                                    {{ number_format($original, 0, ',', '.') }}₫
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-sm text-gray-400">Chưa có giá</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- Nút điều hướng --}}
                <div class="swiper-button-next text-gray-700"></div>
                <div class="swiper-button-prev text-gray-700"></div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.laptop-swiper', {
            slidesPerView: 4,
            spaceBetween: 16,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                0: { slidesPerView: 2 },       // mobile: 2 sp
                640: { slidesPerView: 2.5 },
                768: { slidesPerView: 3 },
                1024: { slidesPerView: 4 },
            }
        });
    });
</script>
@endpush
