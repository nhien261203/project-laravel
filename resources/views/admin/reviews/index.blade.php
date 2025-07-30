@extends('layout.admin')

@section('title', 'Quản lý đánh giá sản phẩm')

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
        <a href="{{ route('admin.comments.index') }}" class="text-2xl font-bold mb-4">Quản lý bình luận blog</a>
        <a href="{{ route('admin.reviews.index') }}" class="text-2xl font-bold mb-4">Quản lý đánh giá sản phẩm</a>
    </div>

    {{-- Filter --}}
    <form method="GET" class="mb-6 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
            <select name="status" class="border border-gray-300 rounded px-3 py-2 text-sm">
                <option value="">-- Tất cả --</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Từ chối</option>
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
            <a href="{{ route('admin.reviews.index') }}" class="ml-2 text-sm text-blue-600 underline">Reset</a>
        </div>
    </form>

    {{-- Danh sách đánh giá --}}
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full text-sm border">
            <thead class="bg-gray-100 text-gray-700">
                <tr class="text-left">
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">Người đánh giá</th>
                    <th class="px-4 py-2 border">Sản phẩm</th>
                    <th class="px-4 py-2 border">Số sao</th>
                    <th class="px-4 py-2 border">Nội dung</th>
                    <th class="px-4 py-2 border">Trạng thái</th>
                    <th class="px-4 py-2 border">Thời gian</th>
                    <th class="px-4 py-2 border text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reviews as $review)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 border font-semibold text-gray-800">{{ $review->user->name ?? 'Ẩn danh' }}</td>
                        <td class="px-4 py-2 border">{{ $review->product->name ?? '-' }}</td>
                        <td class="px-4 py-2 border text-yellow-500">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                            @endfor
                        </td>
                        <td class="px-4 py-2 border text-gray-700">
                            {{ Str::limit($review->comment, 100) }}
                        </td>
                        <td class="px-4 py-2 border">
                            @if ($review->status === 'approved')
                                <span class="inline-block bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded">Đã duyệt</span>
                            @elseif($review->status === 'pending')
                                <span class="inline-block bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded">Chờ duyệt</span>
                            @else
                                <span class="inline-block bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded">Từ chối</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 border text-gray-500">
                            {{ $review->created_at->diffForHumans() }}
                        </td>
                        <td class="px-4 py-2 border text-center space-x-1">
                            <form method="POST" action="{{ route('admin.reviews.updateStatus', $review) }}" class="inline">
                                @csrf
                                <select name="status" onchange="this.form.submit()"
                                    class="text-xs border-gray-300 rounded px-1 py-0.5 bg-white text-gray-700">
                                    <option value="pending" {{ $review->status === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                    <option value="approved" {{ $review->status === 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                                    <option value="rejected" {{ $review->status === 'rejected' ? 'selected' : '' }}>Từ chối</option>
                                </select>
                            </form>

                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline text-xs"
                                    onclick="return confirm('Xoá đánh giá này?')">Xoá</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-4 text-center text-gray-500">Không có đánh giá nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $reviews->links('pagination.custom-tailwind') }}
    </div>
</div>
@endsection
