@extends('layout.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow p-6 rounded-lg">
    <h2 class="text-xl font-bold mb-6 text-gray-700">➕ Thêm thương hiệu</h2>

    <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- Tên thương hiệu --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tên thương hiệu *</label>
            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"
                placeholder="VD: Samsung, Apple..."
            >
            @error('name')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Slug --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Slug (bỏ trống sẽ tự sinh)</label>
            <input
                type="text"
                name="slug"
                value="{{ old('slug') }}"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"
                placeholder="slug-tu-dong"
            >
            @error('slug')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Logo + preview --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Logo (≤ 2MB)</label>
            <div class="mb-2">
                <img id="preview_logo" style="display: none"
                     alt="Preview Logo"
                     class="w-32 h-20 object-contain border rounded shadow transition duration-300 ease-in-out"
                >
            </div>
            <input
                type="file"
                name="logo"
                accept="image/*"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"
                onchange="previewImage(this, 'preview_logo')"
            >
            @error('logo')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Quốc gia --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Quốc gia *</label>
            <input
                type="text"
                name="country"
                value="{{ old('country') }}"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"
                placeholder="VD: Hàn Quốc, Mỹ..."
            >
            @error('country')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Trạng thái --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái *</label>
            <select
                name="status"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"
            >
                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Tạm ẩn</option>
            </select>
            @error('status')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nút --}}
        <div class="flex items-center justify-between mt-6">
            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded"
            >
                💾 Lưu thương hiệu
            </button>
            <a
                href="{{ route('admin.brands.index') }}"
                class="text-gray-600 hover:underline text-sm"
            >
                ← Quay lại danh sách
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input, targetId) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById(targetId);
                if (preview) {
                    preview.src = e.target.result;
                    preview.style.display = 'block'; // chỉ hiện ảnh lên khi nguoif dùng up
                }
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush
