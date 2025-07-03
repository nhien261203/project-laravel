@extends('layout.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">📄 Chi tiết bài viết</h2>

    <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-700">{{ $blog->title }}</h3>
        <p class="text-sm text-gray-500">Đăng ngày: {{ $blog->created_at->format('d/m/Y H:i') }}</p>
        <p class="mt-1 text-sm text-gray-600">
            Trạng thái:
            <span class="inline-block px-2 py-1 rounded text-white text-sm
                {{ $blog->status ? 'bg-green-500' : 'bg-yellow-500' }}">
                {{ $blog->status ? 'Công khai' : 'Nháp' }}
            </span>
        </p>
        @if($blog->tags->count())
            <p class="mt-2">
                <span class="font-semibold">Tags:</span>
                @foreach($blog->tags as $tag)
                    <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">
                        #{{ $tag->name }}
                    </span>
                @endforeach
            </p>
        @endif
    </div>

    @if($blog->thumbnail)
        <div class="mb-6">
            <img src="{{ asset('storage/' . $blog->thumbnail) }}"
                 alt="{{ $blog->title }}"
                 class="w-full rounded shadow">
        </div>
    @endif

    <div class="prose max-w-none">
        {!! $blog->content !!}
    </div>

    <div class="mt-8 flex justify-end gap-3">
        <a href="{{ route('admin.blogs.edit', $blog->id) }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">✏️ Sửa</a>
        <a href="{{ route('admin.blogs.index') }}"
           class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">← Quay lại</a>
    </div>
</div>
@endsection
