@extends('layout.user')

@section('content')
<div class="container mx-auto pt-20 pb-10">
    {{-- Breadcrumb --}}
    <div class="flex items-center text-sm text-gray-600 space-x-2 p-1 mb-6">
        <a href="{{ route('home') }}" class="flex items-center hover:text-blue-600">Trang chủ</a>
        <span class="text-gray-400">›</span>
        <span class="text-gray-800 font-medium">Tin tức</span>
    </div>

    <div id="loadingOverlay"
        class="fixed inset-0 bg-black/30 flex items-center justify-center z-50 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-white p-4 rounded shadow flex items-center gap-2">
            <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
        </div>
    </div>

    {{-- Bộ lọc theo tag --}}
    {{-- @if ($tags->count())
        <div class="flex flex-wrap gap-3 items-center mb-8 px-1">

            @foreach ($tags as $tag)
                @php
                    $isSelected = in_array($tag->id, request()->input('tags', []));
                    $query = request()->except('page');
                    $tagsSelected = request()->input('tags', []);
                    $newTags = $isSelected
                        ? array_diff($tagsSelected, [$tag->id])
                        : array_merge($tagsSelected, [$tag->id]);
                    $query['tags'] = $newTags;
                @endphp

                <a href="{{ route('blogs.index', $query) }}"
                   class="px-3 py-1 rounded-full text-sm border transition font-medium 
                        {{ $isSelected ? 'bg-blue-100 text-blue-700 border-blue-400 shadow-sm' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
                    #{{ $tag->name }}
                </a>
            @endforeach

        </div>
    @endif --}}

    {{-- Danh sách bài viết --}}
    @if ($blogs->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">
            @foreach ($blogs as $blog)
                @php
                    preg_match('/<img[^>]+src="([^">]+)"/', $blog->content, $matches);
                    $thumbnail = $blog->thumbnail 
                        ? asset('storage/' . $blog->thumbnail) 
                        : ($matches[1] ?? asset('images/no-image.png'));
                @endphp

                <a href="{{ route('blogs.show', $blog->slug) }}"
                   class="group bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                    {{-- Hình ảnh --}}
                    <div class="overflow-hidden">
                        <img src="{{ $thumbnail }}" alt="{{ $blog->title }}"
                             class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105" />
                    </div>

                    {{-- Nội dung --}}
                    <div class="p-4">
                        <div class="flex items-center text-xs text-gray-400 mb-1">
                            <svg class="w-4 h-4 mr-1 text-blue-400" fill="none" stroke="currentColor"
                                 stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M8 7V3m8 4V3M5 11h14M5 19h14M5 15h14M4 5h16a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1z" />
                            </svg>
                            {{ $blog->created_at->format('d/m/Y') }}
                        </div>

                        <h2 class="text-base font-semibold text-gray-800 group-hover:text-blue-600 mb-2 line-clamp-2">
                            {{ $blog->title }}
                        </h2>

                        <p class="text-sm text-gray-600 line-clamp-3 leading-relaxed">
                            {{ strip_tags($blog->excerpt ?? Str::limit($blog->content, 100)) }}
                        </p>

                        @if ($blog->tags->count())
                            <div class="mt-3 flex flex-wrap gap-1">
                                @foreach ($blog->tags as $tag)
                                    <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded text-xs font-medium">
                                        #{{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Phân trang --}}
        <div class="mt-6 flex justify-center">
            {{-- Sử dụng custom pagination --}}
            {{ $blogs->links('pagination.custom-user') }}
        </div>
    @else
        <p class="text-gray-500 text-center mt-10">Không tìm thấy bài viết nào.</p>
    @endif
</div>
{{-- <script>
    document.addEventListener('DOMContentLoaded', () => {
        const overlay = document.getElementById('loadingOverlay');
        
        const filterLinks = document.querySelectorAll('a[href*="blogs"]');

        filterLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                
                if (link.hostname === window.location.hostname) {
                    overlay.classList.remove('pointer-events-none');
                    overlay.classList.remove('opacity-0');
                    overlay.classList.add('opacity-100');
                }
            });
        });
    });
</script> --}}

@endsection
