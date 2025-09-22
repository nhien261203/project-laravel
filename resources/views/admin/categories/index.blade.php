@extends('layout.admin')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-xl font-bold">📂 Danh sách Danh mục</h1>
    <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">+ Thêm Danh mục</a>
</div>

<!-- Thông tin kết quả -->
<div class="mb-4 text-sm text-gray-600">
    <p>🔍 Hiển thị <strong>{{ $categories->total() }}</strong> danh mục.</p>
    @if(request()->hasAny(['name', 'status']))
        <p>
            Kết quả tìm kiếm:
            @if(request('name')) <span class="text-blue-600">Tên chứa "{{ request('name') }}"</span> @endif
            @if(request('status') !== null)
                <span class="text-blue-600">Trạng thái: {{ request('status') == 1 ? 'Hoạt động' : 'Tạm ẩn' }}</span>
            @endif
        </p>
    @endif
</div>

<!-- Filter -->
<form method="GET" class="mb-6 flex flex-wrap gap-3 items-center">
    <input type="text" name="name" placeholder="Tên danh mục..." value="{{ request('name') }}"
        class="px-3 py-2 rounded border border-gray-300 shadow-sm focus:ring focus:ring-blue-100 focus:outline-none" />

    <select name="status"
        class="px-3 py-2 rounded border border-gray-300 shadow-sm focus:ring focus:ring-blue-100 focus:outline-none">
        <option value="">-- Trạng thái --</option>
        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Tạm ẩn</option>
    </select>

    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lọc</button>
    <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Reset</a>
</form>

<!-- Table -->
<table class="table-auto w-full bg-white shadow rounded mb-6">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-3 text-left">Tên</th>
            <th class="p-3 text-center">Slug</th>
            <th class="p-3 text-center">Danh mục cha</th>
            <th class="p-3 text-center">Trạng thái</th>
            <th class="p-3 text-center">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse($categories as $category)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3">{{ $category->name }}</td>
                <td class="p-3 text-center">{{ $category->slug }}</td>
                <td class="p-3 text-center">
                    {{ $category->parent?->name ?? 'Gốc' }}
                </td>
                <td class="p-3 text-center">
                    <span class="px-2 py-1 text-sm rounded {{ $category->status ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                        {{ $category->status ? 'Hoạt động' : 'Tạm ẩn' }}
                    </span>
                </td>
                <td class="p-3 space-x-2 text-center">
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="text-blue-600 hover:underline">Sửa</a>
                    @include('partials.delete-confirm', ['action' => route('admin.categories.destroy', $category->id)])
                    <a href="{{ route('admin.categories.show', $category->id) }}" class="text-green-600 hover:underline">Xem</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="p-4 text-center text-gray-500">Không có danh mục nào.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Pagination -->
<div class="mt-4">
    {{-- {{ $categories->links() }} --}}
    {{ $categories->links('pagination.custom-tailwind') }}
</div>
@endsection
