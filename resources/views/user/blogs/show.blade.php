@extends('layout.user')

@section('content')
<div class="container mx-auto px-4 pt-20 pb-10 max-w-3xl overflow-hidden">

    {{-- BÀI VIẾT --}}
    <article class="bg-white p-6 rounded shadow">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $blog->title }}</h1>

        {{-- TAGS --}}
        <div class="text-sm text-gray-500 mb-4">
            @foreach($blog->tags as $tag)
                <span class="inline-block bg-blue-100 text-blue-700 px-2 py-0.5 rounded mr-1 text-xs">
                    {{ $tag->name }}
                </span>
            @endforeach
        </div>

        {{-- ẢNH --}}
        @if ($blog->thumbnail)
            <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="Ảnh đại diện" class="w-full h-auto mb-6 rounded">
        @endif

        {{-- NỘI DUNG --}}
        <div class="prose prose-lg max-w-none">
            {!! $blog->content !!}
        </div>
    </article>
    {{-- Bài viết liên quan --}}
    @if($relatedBlogs->count())
        <div class="mt-12">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Bài viết liên quan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($relatedBlogs as $related)
                    <a href="{{ route('blogs.show', $related->slug) }}" class="block bg-white p-4 rounded shadow hover:shadow-lg transition">
                        @if($related->thumbnail)
                            <img src="{{ asset('storage/' . $related->thumbnail) }}" alt="{{ $related->title }}" class="w-full h-40 object-cover rounded mb-3">
                        @endif
                        <h3 class="text-gray-800 font-semibold mb-1">{{ $related->title }}</h3>
                        <p class="text-gray-500 text-sm line-clamp-2">{{ Str::limit(strip_tags($related->content), 100) }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif


    {{-- BÌNH LUẬN --}}
    <div class="mt-10 bg-white p-6 rounded shadow-sm">
        <h2 class="text-lg font-semibold text-gray-800 mb-3 flex justify-between items-center">
            💬 Bình luận
            @auth
                <button id="toggleCommentForm" 
                    class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition">
                    ✏ Viết bình luận
                </button>
            @endauth
        </h2>

        {{-- FORM BÌNH LUẬN (ẨN MẶC ĐỊNH) --}}
        @auth
            <form id="commentForm" method="POST" class="space-y-4 mb-4 hidden">
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

        {{-- DANH SÁCH BÌNH LUẬN --}}
        @if ($comments->count())
            <div class="mt-4 divide-y divide-gray-200">
                @foreach ($comments as $comment)
                    <div class="py-4 flex gap-3">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.121 17.804A4 4 0 0 1 8.586 16h6.828a4 4 0 0 1 3.465 1.804M15 11a3 3 0 1 0-6 0 3 3 0 0 0 6 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-semibold text-gray-800 text-sm">
                                    {{ $comment->user->name }}
                                </span>
                                <span class="text-xs text-gray-400">
                                    {{ $comment->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-gray-700 text-sm">{{ $comment->content }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- PHÂN TRANG --}}
            <div class="mt-4 flex justify-center">
                {{-- Phân trang --}}
                {{ $comments->links('pagination.custom-user') }}
            </div>
        @else
            <div class="mt-6 text-gray-500 text-sm">Chưa có bình luận nào. Hãy là người đầu tiên!</div>
        @endif
    </div>
</div>

{{-- SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggleCommentForm');
    const form = document.getElementById('commentForm');
    const contentError = document.getElementById('contentError');

    // Toggle form hiển thị
    // if (toggleBtn && form) {
    //     toggleBtn.addEventListener('click', () => {
    //         form.classList.toggle('hidden');
    //     });
    // }

    toggleBtn?.addEventListener('click', () => {
        if (form.classList.contains('hidden')) {
        form.classList.remove('hidden');
        toggleBtn.textContent = '✖ Đóng lại';
        } else {
        form.classList.add('hidden');
        toggleBtn.textContent = '✏ Viết đánh giá';
        }
    });

    // Submit bình luận bằng AJAX
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
                    form.classList.add('hidden');
                    Swal.fire({
                        icon: 'success',
                        title: 'Bình luận đã gửi!',
                        text: 'Bình luận của bạn sẽ được kiểm duyệt trong vòng 24 giờ.',
                        toast: true,
                        position: 'top-end',
                        timer: 2500,
                        showConfirmButton: false
                    });

                    // setTimeout(() => {
                    //     location.reload();
                    // }, 1000);
                } else if (data.errors?.content) {
                    contentError.innerText = data.errors.content[0];
                    contentError.classList.remove('hidden');
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Đã có lỗi xảy ra. Vui lòng thử lại.',
                    toast: true,
                    position: 'top-end',
                    timer: 2500,
                    showConfirmButton: false
                });
            });
        });
    }
});
</script>
@endsection
