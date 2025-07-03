@extends('layout.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow p-6 rounded-lg">
    <h2 class="text-xl font-bold mb-6 text-gray-700">✏️ Cập nhật Banner</h2>

    <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- Tiêu đề --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tiêu đề *</label>
            <input
                type="text"
                name="title"
                value="{{ old('title', $banner->title) }}"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"
                required
            >
            @error('title')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Ảnh desktop --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ảnh Desktop hiện tại</label>
            <div class="mb-2">
                <img
                    id="preview_image_desk"
                    src="{{ $banner->image_desk ? asset('storage/' . $banner->image_desk) : '' }}"
                    alt="Banner Desktop"
                    class="w-full max-w-sm h-auto object-cover border rounded shadow"
                    style="{{ $banner->image_desk ? '' : 'display: none;' }}"
                >
            </div>
            <input
                type="file"
                name="image_desk"
                accept="image/*"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"
                onchange="previewImage(this, 'preview_image_desk')"
            >
            @error('image_desk')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Ảnh mobile --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ảnh Mobile hiện tại (nếu có)</label>
            <div class="mb-2">
                <img
                    id="preview_image_mobile"
                    src="{{ $banner->image_mobile ? asset('storage/' . $banner->image_mobile) : '' }}"
                    alt="Banner Mobile"
                    class="w-48 h-auto object-cover border rounded shadow"
                    style="{{ $banner->image_mobile ? '' : 'display: none;' }}"
                >
            </div>
            <input
                type="file"
                name="image_mobile"
                accept="image/*"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"
                onchange="previewImage(this, 'preview_image_mobile')"
            >
            @error('image_mobile')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Vị trí --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Vị trí hiển thị *</label>
            <input
                type="text"
                name="position"
                value="{{ old('position', $banner->position) }}"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"
                required
            >
            @error('position')
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
                <option value="1" {{ old('status', $banner->status) == '1' ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ old('status', $banner->status) == '0' ? 'selected' : '' }}>Ẩn</option>
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
                💾 Cập nhật Banner
            </button>
            <a
                href="{{ route('admin.banners.index') }}"
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
                    preview.style.display = 'block';
                }
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush
