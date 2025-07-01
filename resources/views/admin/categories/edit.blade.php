@extends('layout.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow p-6 rounded-lg">
    <h2 class="text-xl font-bold mb-6 text-gray-700">✏️ Sửa danh mục</h2>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tên danh mục *</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100" required>
            @error('name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Slug (bỏ trống sẽ tự sinh)</label>
            <input type="text" name="slug" value="{{ old('slug', $category->slug) }}"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
            @error('slug') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Danh mục cha</label>
            <select name="parent_id"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
                <option value="">-- Gốc --</option>
                @foreach($parents as $parent)
                    <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                        {{ $parent->name }}
                    </option>
                @endforeach
            </select>
            @error('parent_id') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái *</label>
            <select name="status"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
                <option value="1" {{ old('status', $category->status) == 1 ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ old('status', $category->status) == 0 ? 'selected' : '' }}>Tạm ẩn</option>
            </select>
            @error('status') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between mt-6">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded">💾 Cập nhật</button>
            <a href="{{ route('admin.categories.index') }}" class="text-gray-600 hover:underline text-sm">← Quay lại danh sách</a>
        </div>
    </form>
</div>
@endsection
