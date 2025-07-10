<div class="relative w-full overflow-hidden rounded-xl shadow-lg mt-[66px]">
    <div class="swiper myBannerSwiper md:aspect-[16/5] relative">
        <div class="swiper-wrapper">
            @foreach ($banners as $banner)
                <div class="swiper-slide">
                    <picture>
                        <source media="(min-width: 768px)" srcset="{{ asset('storage/' . $banner->image_desk) }}">
                        <img src="{{ asset('storage/' . $banner->image_mobile) }}"
                             alt="{{ $banner->title }}"
                             
                             class="w-full h-full object-cover block rounded-xl" />
                    </picture>
                </div>
            @endforeach
        </div>

        {{-- Dấu chấm --}}
        <div class="swiper-pagination !bottom-4"></div>

        {{-- Nút điều hướng --}}
        <div class="swiper-button-prev !w-8 !h-8 !bg-white !text-black !rounded-full !shadow-md !flex !items-center !justify-center !z-10 hover:!bg-gray-100"></div>
        <div class="swiper-button-next !w-8 !h-8 !bg-white !text-black !rounded-full !shadow-md !flex !items-center !justify-center !z-10 hover:!bg-gray-100"></div>
    </div>

    {{-- Custom Swiper UI --}}
    <style>
        .swiper-button-prev,
        .swiper-button-next {
            top: 50% !important;
            transform: translateY(-50%) !important;
        }

        .swiper-button-prev {
            left: 10px !important;
        }

        .swiper-button-next {
            right: 10px !important;
        }

        .swiper-button-prev::after,
        .swiper-button-next::after {
            font-size: 14px !important;
            font-weight: bold;
        }

        .swiper-pagination-bullet {
            @apply bg-white/60 w-3 h-3 rounded-full mx-1;
        }

        .swiper-pagination-bullet-active {
            @apply bg-white w-4 h-4 shadow;
        }
    </style>

    {{-- JS Init --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new Swiper('.myBannerSwiper', {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                speed: 800,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        });
    </script>
</div>
