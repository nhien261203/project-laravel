@extends('layout.user')

@section('content')
<div class="container ">
    {{-- <h1 class="text-3xl font-bold text-gray-800 mb-6">📰 Bài viết mới nhất</h1> --}}

    <div class="grid grid-cols-2 xl:grid-cols-3 gap-6 mt-10">
        @foreach ($blogs as $blog)
            @php
                preg_match('/<img[^>]+src="([^">]+)"/', $blog->content, $matches);
                $thumbnail = $blog->thumbnail 
                    ? asset('storage/' . $blog->thumbnail) 
                    : ($matches[1] ?? asset('images/no-image.png'));
            @endphp
            <a href="{{ route('blogs.show', $blog->slug) }}"
               class="group block bg-white shadow rounded overflow-hidden hover:shadow-lg transition duration-300">
                <img src="{{ $thumbnail }}" alt="{{ $blog->title }}"
                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300" />

                <div class="p-4">
                    <h2 class="text-lg font-semibold text-gray-800 group-hover:text-blue-600 truncate">
                        {{ $blog->title }}
                    </h2>

                    <div class="mt-2 text-sm text-gray-600 line-clamp-3">
                        {{ strip_tags($blog->excerpt ?? Str::limit($blog->content, 100)) }}
                    </div>

                    <div class="mt-3 text-xs text-gray-400 flex flex-wrap gap-1">
                        @foreach ($blog->tags as $tag)
                            <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $blogs->links() }}
    </div>
</div>
@endsection
