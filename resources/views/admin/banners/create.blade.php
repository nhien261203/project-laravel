@extends('layout.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow p-6 rounded-lg">
    <h2 class="text-xl font-bold mb-6 text-gray-700">➕ Thêm Banner</h2>

    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- Tiêu đề --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tiêu đề *</label>
            <input type="text" name="title" value="{{ old('title') }}"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"
                placeholder="VD: Banner khuyến mãi mùa hè">
            @error('title')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Ảnh desktop --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ảnh Desktop *</label>
            <input type="file" name="image_desk" id="image_desk" accept="image/*"
                onchange="previewImage(this, '#preview_image_desk')"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
            @error('image_desk')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror

            <div class="mt-2">
                <img id="preview_image_desk"
                     style="display: none;"
                     class="w-full max-w-lg h-auto object-cover rounded border">
            </div>
        </div>

        {{-- Ảnh mobile --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ảnh Mobile (tuỳ chọn)</label>
            <input type="file" name="image_mobile" id="image_mobile" accept="image/*"
                onchange="previewImage(this, '#preview_image_mobile')"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
            @error('image_mobile')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror

            <div class="mt-2">
                <img id="preview_image_mobile"
                     style="display: none;"
                     class="w-60 h-auto object-cover rounded border">
            </div>
        </div>

        {{-- link --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Liên kết (tuỳ chọn)</label>
            <input type="text" name="link" value="{{ old('link') }}"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"
                >
            @error('link')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Vị trí --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Vị trí hiển thị *</label>
            <input type="text" name="position" value="{{ old('position') }}"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"
                placeholder="VD: top_home, sidebar...">
            @error('position')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Trạng thái --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái *</label>
            <select name="status"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Ẩn</option>
            </select>
            @error('status')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nút --}}
        <div class="flex items-center justify-between mt-6">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded">
                💾 Lưu banner
            </button>
            <a href="{{ route('admin.banners.index') }}"
                class="text-gray-600 hover:underline text-sm">
                ← Quay lại danh sách
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input, targetSelector) {
        const file = input.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            const img = document.querySelector(targetSelector);
            if (img) {
                img.src = e.target.result;
                img.style.display = 'block';
            }
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush
