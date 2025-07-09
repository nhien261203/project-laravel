@extends('layout.user')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <article class="bg-white p-6 rounded shadow">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $blog->title }}</h1>

        <div class="text-sm text-gray-500 mb-4">
            @foreach($blog->tags as $tag)
                <span class="inline-block bg-blue-100 text-blue-700 px-2 py-0.5 rounded mr-1 text-xs">
                    {{ $tag->name }}
                </span>
            @endforeach
        </div>

        @if ($blog->thumbnail)
            <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="Ảnh đại diện"
                class="w-full h-auto mb-6 rounded">
        @endif

        <div class="prose prose-lg max-w-none">
            {!! $blog->content !!}
        </div>
    </article>
</div>
@endsection
