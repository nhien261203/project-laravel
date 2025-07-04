@extends('layout.admin')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6 flex-wrap gap-2">
        <h2 class="text-2xl font-bold text-gray-800">📚 Danh sách bài viết</h2>
        <a href="{{ route('admin.blogs.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
            ➕ Viết bài mới
        </a>
    </div>

    <!-- Bộ lọc -->
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <input type="text" name="search" value="{{ request('search') }}"
               class="border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-100"
               placeholder="🔍 Tìm theo tiêu đề...">

        <select name="status"
                class="border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-100">
            <option value="">-- Tất cả trạng thái --</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Công khai</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nháp</option>
        </select>

        <div class="flex gap-2">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
                🔍 Tìm
            </button>
            <a href="{{ route('admin.blogs.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded shadow">
                ♻️ Reset
            </a>
        </div>
    </form>

    <!-- Bảng danh sách -->
    <div class="overflow-x-auto">
        <table class="w-full min-w-[640px] table-auto border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-gray-100 text-sm text-gray-700">
                <tr>
                    <th class="p-3 text-left">#</th>
                    <th class="p-3 text-left">Tiêu đề</th>
                    <th class="p-3 text-center">Trạng thái</th>
                    <th class="p-3 text-center">Ngày tạo</th>
                    <th class="p-3 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($blogs as $blog)
                    <tr class="border-t hover:bg-gray-50 transition-all">
                        <td class="p-3">{{ $loop->iteration + ($blogs->currentPage() - 1) * $blogs->perPage() }}</td>
                        <td class="p-3 font-medium text-gray-900">{{ $blog->title }}</td>
                        <td class="p-3 text-center">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $blog->status ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $blog->status ? 'Công khai' : 'Nháp' }}
                            </span>
                        </td>
                        <td class="p-3 text-center text-gray-600">{{ $blog->created_at->format('d/m/Y') }}</td>
                        <td class="p-3 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.blogs.show', $blog->id) }}"
                               class="inline-block text-blue-600 hover:underline text-sm">👁️</a>
                            <a href="{{ route('admin.blogs.edit', $blog->id) }}"
                               class="inline-block text-yellow-600 hover:underline text-sm">✏️</a>
                            <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST"
                                  onsubmit="return confirm('Bạn có chắc muốn xoá bài viết này?')"
                                  class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-sm">🗑️</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500 italic">Không có bài viết nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $blogs->links('pagination.custom-tailwind') }}
    </div>
</div>
@endsection
