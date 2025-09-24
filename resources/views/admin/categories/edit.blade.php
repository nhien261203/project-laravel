@extends('layout.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow p-6 rounded-lg">
    <h2 class="text-xl font-bold text-gray-800 mb-6">✏️ Sửa danh mục</h2>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Tên --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tên danh mục *</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $category->name) }}"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"
                    required
                >
                @error('name')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Slug --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Slug (tuỳ chọn)</label>
                <input
                    type="text"
                    name="slug"
                    value="{{ old('slug', $category->slug) }}"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"
                >
                @error('slug')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Danh mục cha --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Danh mục cha</label>
                <select
                    name="parent_id"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"
                >
                    <option value="">-- Gốc --</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
                @error('parent_id')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Trạng thái --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái *</label>
                <select
                    name="status"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"
                    required
                >
                    <option value="1" {{ old('status', $category->status) == '1' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('status', $category->status) == '0' ? 'selected' : '' }}>Tạm ẩn</option>
                </select>
                @error('status')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Logo --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>

            <div class="flex items-start gap-6">
                <div>
                    @if($category->logo)
                        <img id="current_logo" src="{{ asset('storage/' . $category->logo) }}" alt="Logo hiện tại" class="w-24 h-24 object-contain border rounded">
                    @endif
                    <img id="preview_logo" class="w-24 h-24 object-contain border rounded mt-1 hidden">
                </div>

                <div class="flex-1">
                    <input
                        type="file"
                        name="logo"
                        accept="image/*"
                        onchange="previewImage(this)"
                        class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"
                    >
                    @error('logo')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Nút --}}
        <div class="flex items-center justify-between pt-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded">
                💾 Cập nhật
            </button>
            <a href="{{ route('admin.categories.index') }}" class="text-gray-600 hover:underline text-sm">
                ← Quay lại danh sách
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        const file = input.files[0];
        const preview = document.getElementById('preview_logo');
        const current = document.getElementById('current_logo');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (current) current.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush
