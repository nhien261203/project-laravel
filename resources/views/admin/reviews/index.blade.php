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

<div class="max-w-6xl mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Quản lý đánh giá sản phẩm</h1>

    {{-- Filter --}}
    <form method="GET" class="mb-6 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
            <select name="status" class="border border-gray-300 rounded px-3 py-2 text-sm">
                <option value="">-- Tất cả --</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Bị từ chối</option>
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
                Lọc
            </button>
            <a href="{{ route('admin.reviews.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Reset</a>
        </div>
    </form>

    {{-- Table --}}
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full text-sm border">
            <thead class="bg-gray-100 text-gray-700">
                <tr class="text-left">
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">Người đánh giá</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Số điện thoại</th>
                    <th class="px-4 py-2 border">Sản phẩm</th>
                    <th class="px-4 py-2 border">Số sao</th>
                    <th class="px-4 py-2 border">Nội dung</th>
                    <th class="px-4 py-2 border">Trạng thái</th>
                    <th class="px-4 py-2 border">Thời gian</th>
                    <th class="px-4 py-2 border ">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reviews as $review)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2 border">
                            {{ ($reviews->currentPage()-1)*$reviews->perPage() + $loop->iteration }}
                        </td>

                        <td class="px-4 py-2 border font-semibold text-gray-800">{{ $review->user->name ?? 'Ẩn danh' }}</td>
                        <td class="px-4 py-2 border">{{ $review->user->email ?? '-' }}</td>
                        <td class="px-4 py-2 border">{{ $review->user->phone ?? '-' }}</td>
                        <td class="px-4 py-2 border">{{ $review->product->name ?? '-' }}</td>
                        <td class="px-4 py-2 border text-yellow-500">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                            @endfor
                        </td>
                        <td class="px-4 py-2 border text-gray-700 relative group">
                            <span class="cursor-pointer">{{ Str::limit($review->comment, 50) }}</span>
                            <div class="absolute left-0 bottom-full mb-2 w-64 max-w-xs hidden group-hover:block bg-gray-800 text-white text-xs rounded p-2 shadow-lg z-10 break-words">
                                {{ $review->comment }}
                            </div>
                        </td>
                        <td class="px-4 py-2 border">
                            @if ($review->status === 'approved')
                                <span class="inline-block bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded">Đã duyệt</span>
                            @elseif ($review->status === 'pending')
                                <span class="inline-block bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded">Chờ duyệt</span>
                            @else
                                <span class="inline-block bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded">Đã từ chối</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 border text-gray-500">
                            {{ $review->created_at->format('d/m/Y H:i:s') }}
                        </td>

                        {{-- Action Dropdown --}}
                        <td class="px-4 py-2 border text-center">
                            <form method="POST" action="" id="action-form-{{ $review->id }}">
                                @csrf
                                <select onchange="handleAction(this, {{ $review->id }})"
                                        class="border border-gray-300 rounded px-2 py-1 text-sm">
                                    <option value="">Chọn hành động</option>
                                    @if ($review->status === 'pending')
                                        <option value="approve"> Duyệt</option>
                                        <option value="reject">Từ chối</option>
                                        <option value="delete"> Xóa</option>
                                    @elseif ($review->status === 'approved')
                                        <option value="unapprove">Chuyển về chờ duyệt</option>
                                        {{-- <option value="reject">Từ chối</option> --}}
                                        <option value="delete">Xóa</option>
                                    @elseif ($review->status === 'rejected')
                                        <option value="approve">Duyệt lại</option>
                                        <option value="delete">Xóa</option>
                                    @endif
                                </select>
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

{{-- JS xử lý dropdown action --}}
<script>
function handleAction(select, reviewId) {
    const action = select.value;
    if (!action) return;

    let url = '';
    let confirmMessage = '';

    switch(action) {
        case 'approve':
            url = `/admin/reviews/${reviewId}/approve`;
            confirmMessage = 'Duyệt đánh giá này?';
            break;
        case 'unapprove':
            url = `/admin/reviews/${reviewId}/unapprove`;
            confirmMessage = 'Bỏ duyệt đánh giá này?';
            break;
        case 'reject':
            url = `/admin/reviews/${reviewId}/reject`;
            confirmMessage = 'Từ chối đánh giá này?';
            break;
        case 'delete':
            url = `/admin/reviews/${reviewId}`;
            confirmMessage = 'Xóa đánh giá này?';
            break;
    }

    if (confirm(confirmMessage)) {
        const form = document.getElementById(`action-form-${reviewId}`);
        form.action = url;

        if (action === 'delete') {
            form.innerHTML += '@method("DELETE")';
        }

        form.submit();
    } else {
        select.value = '';
    }
}
</script>
@endsection
