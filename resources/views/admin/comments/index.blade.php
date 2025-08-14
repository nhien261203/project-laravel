@extends('layout.admin')

@section('title', 'Quản lý bình luận')

@section('content')
<style>
    thead th {
        position: sticky;
        top: 0;
        background: #f3f4f6; 
        z-index: 1;
    }
</style>

<div class="container mx-auto py-6">
    
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('admin.comments.index') }}" class="text-2xl font-bold mb-4">
            Quản lý bình luận blog
        </a>
    </div>

    {{-- Filter --}}
    <form method="GET" class="mb-6 flex flex-wrap gap-4 items-end">
        {{-- Trạng thái --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
            <select name="approved" class="border border-gray-300 rounded px-3 py-2 text-sm">
                <option value="" {{ !request()->filled('approved') ? 'selected' : '' }}>-- Tất cả --</option>
                <option value="approved" {{ request('approved') === 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                <option value="pending" {{ request('approved') === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
            </select>
        </div>

        {{-- Từ khóa --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Từ khóa</label>
            <input type="text" name="keyword" value="{{ request('keyword') }}"
                   class="border border-gray-300 rounded px-3 py-2 text-sm w-64"
                   placeholder="Nội dung hoặc tên người dùng...">
        </div>

        {{-- Nút tìm kiếm --}}
        <div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                🔍 Tìm kiếm
            </button>
            <a href="{{ route('admin.comments.index') }}"
               class="ml-2 text-sm text-blue-600 underline">Reset</a>
        </div>
    </form>

    {{-- Danh sách bình luận --}}
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full text-sm border">
            <thead class="bg-gray-100 text-gray-700">
                <tr class="text-left">
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">Người bình luận</th>
                    <th class="px-4 py-2 border">Bài viết</th>
                    <th class="px-4 py-2 border">Nội dung</th>
                    <th class="px-4 py-2 border">Trạng thái</th>
                    <th class="px-4 py-2 border">Thời gian</th>
                    <th class="px-4 py-2 border text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($comments as $comment)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2 border">{{ ($comments->currentPage()-1)*$comments->perPage() + $loop->iteration }}</td>

                        <td class="px-4 py-2 border font-semibold text-gray-800">
                            {{ $comment->user->name ?? 'Ẩn danh' }}
                        </td>

                        <td class="px-4 py-2 border relative group">
                            <a href="{{ route('blogs.show', $comment->blog->slug) }}" target="_blank"
                               class="text-blue-600 hover:underline cursor-pointer">
                                {{ Str::limit($comment->blog->title, 30) }}
                            </a>
                            <div class="absolute left-0 bottom-full mb-2 w-64 max-w-xs hidden group-hover:block 
                                        bg-gray-800 text-white text-xs rounded p-2 shadow-lg z-10 break-words">
                                {{ $comment->blog->title }}
                            </div>
                        </td>

                        <td class="px-4 py-2 border text-gray-700 relative group">
                            <span class="cursor-pointer">
                                {{ Str::limit($comment->content, 50) }}
                            </span>
                            <div class="absolute left-0 bottom-full mb-2 w-64 max-w-xs hidden group-hover:block bg-gray-800 text-white text-xs rounded p-2 shadow-lg z-10 break-words">
                                {{ $comment->content }}
                            </div>
                        </td>

                        {{-- Trạng thái --}}
                        <td class="px-4 py-2 border">
                            @if ($comment->approved === 'approved')
                                <span class="inline-block bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded">Đã duyệt</span>
                            @else
                                <span class="inline-block bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded">Chờ duyệt</span>
                            @endif
                        </td>

                        <td class="px-4 py-2 border text-gray-500">
                            {{ $comment->created_at->diffForHumans() }}
                        </td>

                        {{-- Hành động Dropdown --}}
                        <td class="px-4 py-2 border text-center">
                            <form method="POST" action="" id="action-form-{{ $comment->id }}">
                                @csrf
                                <select onchange="handleAction(this, {{ $comment->id }})"
                                        class="border border-gray-300 rounded px-2 py-1 text-sm">
                                    <option value="">Chọn hành động</option>

                                    @if ($comment->approved === 'pending')
                                        <option value="approve">Duyệt</option>
                                        <option value="delete">Xóa</option>
                                    @elseif ($comment->approved === 'approved')
                                        <option value="unapprove">Bỏ duyệt</option>
                                        <option value="delete">Xóa</option>
                                    @endif
                                </select>
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-4 text-center text-gray-500">
                            Không có bình luận nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $comments->links('pagination.custom-tailwind') }}
    </div>
</div>

{{-- JS xử lý dropdown action --}}
<script>
function handleAction(select, commentId) {
    const action = select.value;
    if (!action) return;

    let url = '';
    let confirmMessage = '';

    switch(action) {
        case 'approve':
            url = `/admin/comments/${commentId}/approve`;
            confirmMessage = 'Duyệt bình luận này?';
            break;
        case 'unapprove':
            url = `/admin/comments/${commentId}/unapprove`;
            confirmMessage = 'Bỏ duyệt bình luận này?';
            break;
        case 'delete':
            url = `/admin/comments/${commentId}`;
            confirmMessage = 'Xóa bình luận này?';
            break;
    }

    if (confirm(confirmMessage)) {
        const form = document.getElementById(`action-form-${commentId}`);
        form.action = url;

        if (action === 'delete') {
            form.innerHTML += '@method("DELETE")';
        }

        form.submit();
    } else {
        // reset select nếu cancel
        select.value = '';
    }
}
</script>
@endsection
