@extends('layout.admin')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 shadow rounded">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-800">📃 Danh sách Banner</h2>
        <a href="{{ route('admin.banners.create') }}" class="bg-green-500 hover:bg-green-600 text-blue px-4 py-2 rounded shadow">
            ➕ Thêm mới
        </a>
    </div>

    {{-- Bộ lọc --}}
    <form method="GET" action="{{ route('admin.banners.index') }}" class="mb-4 flex flex-wrap items-center gap-4">
        <input
            type="text"
            name="keyword"
            value="{{ request('keyword') }}"
            placeholder="Tìm theo tiêu đề..."
            class="px-4 py-2 border rounded w-64"
        >

        <select name="status" class="px-4 py-2 border rounded">
            <option value="">-- Trạng thái --</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hiển thị</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn</option>
        </select>

        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            🔍 Lọc
        </button>

        <a href="{{ route('admin.banners.index') }}" class="text-gray-600 hover:underline ml-2">
            ♻️ Reset
        </a>
    </form>


    <table class="w-full table-auto border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">#</th>
                <th class="p-2 border">Tiêu đề</th>
                <th class="p-2 border">Ảnh desktop</th>
                <th class="p-2 border">Vị trí</th>
                <th class="p-2 border">Trạng thái</th>
                <th class="p-2 border">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($banners as $banner)
                <tr class="text-center">
                    <td class="p-2 border">{{ $loop->iteration + ($banners->currentPage() - 1) * $banners->perPage() }}</td>
                    <td class="p-2 border">{{ $banner->title }}</td>
                    <td class="p-2 border">
                        <img src="{{ asset('storage/' . $banner->image_desk) }}"
                                class="w-40 h-24 object-contain mx-auto rounded border border-gray-200 shadow-sm">

                    </td>
                    <td class="p-2 border">{{ $banner->position }}</td>
                    <td class="p-2 border">
                        @if($banner->status)
                            <span class="text-green-600 font-medium">Hiển thị</span>
                        @else
                            <span class="text-red-500 font-medium">Ẩn</span>
                        @endif
                    </td>
                    <td class="p-2 border">
                        <a href="{{ route('admin.banners.edit', $banner) }}" class="text-blue-600 hover:underline">✏️</a> 
                        {{-- <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="inline" onsubmit="return confirm('Xoá banner này?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Xoá</button>
                        </form> --}}
                        @include('partials.delete-confirm', [
                            'action' => route('admin.banners.destroy', $banner->id)
                        ])
                        <a href="{{ route('admin.banners.show', $banner) }}" class="text-gray-600 hover:underline">👁️</a>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center p-4 text-gray-500">Không có banner nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $banners->links('pagination.custom-tailwind') }}
    </div>
</div>
@endsection
