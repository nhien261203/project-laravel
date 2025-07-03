@extends('layout.admin')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-7xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">📚 Danh sách bài viết</h2>

    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <input type="text" name="search" value="{{ request('search') }}"
               class="border-gray-300 rounded px-3 py-2" placeholder="Tìm theo tiêu đề...">

        <select name="status" class="border-gray-300 rounded px-3 py-2">
            <option value="">-- Tất cả trạng thái --</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Công khai</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nháp</option>
        </select>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">🔍 Tìm</button>
            <a href="{{ route('admin.blogs.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">♻️ Reset</a>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">Tiêu đề</th>
                    <th class="p-3">Ảnh</th>
                    <th class="p-3">Trạng thái</th>
                    <th class="p-3">Ngày tạo</th>
                    <th class="p-3 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($blogs as $blog)
                    <tr class="border-t">
                        <td class="p-3">{{ $loop->iteration + ($blogs->currentPage() - 1) * $blogs->perPage() }}</td>
                        <td class="p-3 font-semibold">{{ $blog->title }}</td>
                        <td class="p-3">
                            @if($blog->thumbnail)
                                <img src="{{ asset('storage/' . $blog->thumbnail) }}" class="w-16 h-16 object-cover rounded shadow">

                            @else
                                <span class="text-gray-400 italic">Không có</span>
                            @endif
                        </td>

                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-white text-sm
                                {{ $blog->status ? 'bg-green-500' : 'bg-yellow-500' }}">
                                {{ $blog->status ? 'Công khai' : 'Nháp' }}
                            </span>
                        </td>
                        <td class="p-3 text-gray-500 text-sm">{{ $blog->created_at->format('d/m/Y') }}</td>
                        <td class="p-3 text-right space-x-2">
                            <a href="{{ route('admin.blogs.show', $blog->id) }}" class="text-blue-600 hover:underline">👁️ Xem</a>
                            <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="text-blue-600 hover:underline">✏️ Sửa</a>
                            <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST"
                                  onsubmit="return confirm('Xác nhận xóa?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">🗑️ Xoá</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">Không có bài viết nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $blogs->links('pagination.custom-tailwind') }}
    </div>
</div>
@endsection
