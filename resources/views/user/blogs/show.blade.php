@extends('layout.user')

@section('content')
<div class="container mx-auto px-4 pt-20 pb-10 max-w-3xl">
    {{-- BÀI VIẾT --}}
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
            <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="Ảnh đại diện" class="w-full h-auto mb-6 rounded">
        @endif

        <div class="prose prose-lg max-w-none">
            {!! $blog->content !!}
        </div>
    </article>

    {{-- FORM BÌNH LUẬN --}}
    <div class="mt-10 bg-white p-6 rounded shadow-sm">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">💬 Bình luận</h2>

        {{-- THÔNG BÁO --}}
        <div id="commentMessage" class="hidden mb-4 p-3 text-sm rounded bg-green-100 text-green-700 border border-green-300"></div>

        @auth
            <form id="commentForm" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                <textarea name="content" rows="4" placeholder="Nhập bình luận của bạn..."
                    class="w-full border border-gray-300 rounded p-3 text-sm focus:outline-none focus:ring focus:border-blue-400 resize-none"
                    required></textarea>
                <p id="contentError" class="text-sm text-red-500 hidden"></p>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                        Gửi bình luận
                    </button>
                </div>
            </form>
        @else
            <div class="text-sm text-gray-600">
                Vui lòng <a href="{{ route('login') }}" class="text-blue-600 underline">đăng nhập</a> để bình luận.
            </div>
        @endauth
    </div>

    {{-- DANH SÁCH BÌNH LUẬN --}}
    <div id="commentList">
        @php
            $comments = $blog->comments()->where('approved', true)->latest()->get();
        @endphp

        @if ($comments->count())
            <div class="mt-6 space-y-4">
                @foreach ($comments as $comment)
                    <div class="bg-white p-4 rounded shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center text-sm font-semibold text-gray-800 gap-1">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M5.121 17.804A4 4 0 0 1 8.586 16h6.828a4 4 0 0 1 3.465 1.804M15 11a3 3 0 1 0-6 0 3 3 0 0 0 6 0z" />
                                </svg>
                                {{ $comment->user->name }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $comment->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <p class="text-gray-700 text-sm mt-1 flex items-start gap-1">
                            <svg class="w-4 h-4 mt-1 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.985 9.985 0 0 1-4.324-.97L3 20l1.7-3.4A7.972 7.972 0 0 1 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            {{ $comment->content }}
                        </p>
                    </div>
                @endforeach

            </div>
        @else
            <div class="mt-6 text-gray-500 text-sm">Chưa có bình luận nào. Hãy là người đầu tiên!</div>
        @endif
    </div>
</div>

{{-- AJAX GỬI BÌNH LUẬN --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('commentForm');
    const msg = document.getElementById('commentMessage');
    const contentError = document.getElementById('contentError');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(form);
            const token = document.querySelector('input[name="_token"]').value;

            fetch("{{ route('comments.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": token
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                contentError.classList.add('hidden');
                if (data.success) {
                    form.reset();
                    msg.innerText = '✅ Bình luận của bạn đã được gửi và sẽ được kiểm duyệt trong vòng 24 giờ.';
                    msg.classList.remove('hidden');

                    setTimeout(() => {
                        msg.classList.add('hidden');
                    }, 5000);
                } else if (data.errors?.content) {
                    contentError.innerText = data.errors.content[0];
                    contentError.classList.remove('hidden');
                }
            })
            .catch(error => {
                msg.innerText = 'Đã có lỗi xảy ra. Vui lòng thử lại.';
                msg.classList.remove('hidden');
            });
        });
    }
});
</script>
@endsection
