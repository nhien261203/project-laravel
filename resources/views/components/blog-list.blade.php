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
                <div class="overflow-hidden">
                    <img src="{{ $thumbnail }}" alt="{{ $blog->title }}"
                        class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105" />
                </div>

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

    <div class="mt-6 flex justify-center">
        {{ $blogs->links('pagination.custom-user') }}
    </div>
@else
    <p class="text-gray-500 text-center mt-10">Không tìm thấy bài viết nào.</p>
@endif
