@extends('layout.admin')

@section('title', 'Quản lý bình luận')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">🗨️ Quản lý bình luận</h1>

    {{-- Filter --}}
    <form method="GET" class="mb-6 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
            <select name="approved" class="border border-gray-300 rounded px-3 py-2 text-sm">
                <option value="">-- Tất cả --</option>
                <option value="1" {{ request('approved') === '1' ? 'selected' : '' }}>Đã duyệt</option>
                <option value="0" {{ request('approved') === '0' ? 'selected' : '' }}>Chờ duyệt</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Từ khóa</label>
            <input type="text" name="keyword" value="{{ request('keyword') }}"
                   class="border border-gray-300 rounded px-3 py-2 text-sm w-64"
                   placeholder="Nội dung hoặc tên người dùng...">
        </div>

        <div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                🔍 Tìm kiếm
            </button>
            <a href="{{ route('admin.comments.index') }}"
               class="ml-2 text-sm text-blue-600 underline"> Reset</a>
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
                        <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 border font-semibold text-gray-800">
                            {{ $comment->user->name ?? 'Ẩn danh' }}
                        </td>
                        <td class="px-4 py-2 border">
                            <a href="{{ route('blogs.show', $comment->blog->slug) }}"
                               class="text-blue-600 hover:underline" target="_blank">
                               {{ Str::limit($comment->blog->title, 30) }}
                            </a>
                        </td>
                        <td class="px-4 py-2 border text-gray-700">
                            {{ Str::limit($comment->content, 200) }}
                        </td>
                        <td class="px-4 py-2 border">
                            @if ($comment->approved)
                                <span class="text-green-600 font-medium">Đã duyệt</span>
                            @else
                                <span class="text-yellow-600 font-medium">Chờ duyệt</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 border text-gray-500">
                            {{ $comment->created_at->diffForHumans() }}
                        </td>
                        <td class="px-4 py-2 border text-center space-x-1">
                            @if (!$comment->approved)
                                <form method="POST" action="{{ route('admin.comments.approve', $comment->id) }}"
                                      class="inline">
                                    @csrf
                                    <button class="text-green-600 hover:underline text-xs"
                                            onclick="return confirm('Duyệt bình luận này?')">Duyệt</button>
                                </form
                            @endif

                            <form method="POST" action="{{ route('admin.comments.destroy', $comment->id) }}"
                                  class="inline">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline text-xs"
                                        onclick="return confirm('Xóa bình luận này?')">Xoá</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-4 text-center text-gray-500">Không có bình luận nào.</td>
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
@endsection
