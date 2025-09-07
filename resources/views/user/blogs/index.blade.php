@extends('layout.user')

@section('content')
<div class="container mx-auto pt-20 pb-10">
    {{-- Breadcrumb --}}
    <div class="flex items-center text-sm text-gray-600 space-x-2 p-1 mb-6">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Trang chủ</a>
        <span class="text-gray-400">›</span>
        <span class="text-gray-800 font-medium">Tin tức</span>
    </div>

    {{-- Danh sách blog --}}
    <div id="blog-list">
        @include('components.blog-list', ['blogs' => $blogs])
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const blogList = document.getElementById('blog-list');

    async function fetchBlogs(url) {
        try {
            const response = await fetch(url, {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            });
            const html = await response.text();
            blogList.innerHTML = html;
            window.history.pushState({}, "", url);

            // Scroll mượt lên đầu danh sách
            // window.scrollTo({
            //     top: 0,
            //     behavior: 'smooth'
            // });
        } catch (err) {
            console.error("Fetch blogs error:", err);
        }
    }

    // Event delegation cho pagination
    document.addEventListener("click", function (e) {
        const link = e.target.closest(".pagination a");
        if (!link) return;

        e.preventDefault();
        fetchBlogs(link.href);
    });
});
</script>
@endpush
