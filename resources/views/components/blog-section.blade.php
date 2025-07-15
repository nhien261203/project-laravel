<div class="bg-white my-8 p-4 rounded-lg shadow">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-2 sm:gap-0">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">
            Mạng xã hội công nghệ
        </h2>

        <a href="{{ route('blogs.index') }}"
           class="text-sm sm:text-base text-blue-500 font-medium hover:text-blue-600 transition">
            Xem tất cả
        </a>
    </div>

    {{-- Desktop grid --}}
    <div class="hidden sm:grid grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($blogs as $blog)
            <a href="{{ route('blogs.show', $blog->slug) }}" class="group block bg-white rounded-lg shadow hover:shadow-md transition overflow-hidden">
                @if($blog->thumbnail)
                    <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="{{ $blog->title }}"
                         class="w-full h-40 object-cover" loading="lazy">
                @else
                    <div class="w-full h-40 bg-gray-100 flex items-center justify-center text-gray-400">
                        Không có ảnh
                    </div>
                @endif

                <div class="p-4">
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-blue-600">
                        {{ Str::limit($blog->title, 60) }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ Str::limit($blog->excerpt, 80) }}
                    </p>
                </div>
            </a>
        @endforeach
    </div>

    {{-- Mobile swiper --}}
    <div class="block sm:hidden">
        <div class="swiper blog-swiper">
            <div class="swiper-wrapper">
                @foreach($blogs as $blog)
                    <div class="swiper-slide px-1">
                        <a href="{{ route('blogs.show', $blog->slug) }}" class="group block bg-white rounded-lg shadow hover:shadow-md transition overflow-hidden">
                            @if($blog->thumbnail)
                                <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="{{ $blog->title }}"
                                     class="w-full aspect-[5/3] object-cover" loading="lazy">
                            @else
                                <div class="w-full aspect-[5/3] bg-gray-100 flex items-center justify-center text-gray-400">
                                    Không có ảnh
                                </div>
                            @endif

                            <div class="p-3">
                                <h3 class="text-sm font-semibold text-gray-800 group-hover:text-blue-600">
                                    {{ Str::limit($blog->title, 50) }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ Str::limit($blog->excerpt, 70) }}
                                </p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- Nút điều hướng --}}
            <div class="swiper-button-prev text-gray-500"></div>
            <div class="swiper-button-next text-gray-500"></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.blog-swiper', {
            slidesPerView: 1.5,
            spaceBetween: 10,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                480: { slidesPerView: 2 },
            }
        });
    });
</script>
@endpush

