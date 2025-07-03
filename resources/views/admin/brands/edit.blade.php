@extends('layout.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow p-6 rounded-lg">
    <h2 class="text-xl font-bold mb-6 text-gray-700">✏️ Cập nhật thương hiệu</h2>

    <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- Tên --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tên thương hiệu *</label>
            <input
                type="text"
                name="name"
                value="{{ old('name', $brand->name) }}"
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
                value="{{ old('slug', $brand->slug) }}"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"
            >
            @error('slug')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Logo --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Logo hiện tại</label>
            <div class="mb-2">
                @if($brand->logo)
                    <img id="current_logo" src="{{ asset('storage/' . $brand->logo) }}" alt="Logo hiện tại" class="w-24 h-24 object-contain border rounded">
                @endif
                <img id="preview_logo" class="w-24 h-24 object-contain border rounded mt-2 hidden">
            </div>

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

        {{-- Quốc gia --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Quốc gia *</label>
            <input
                type="text"
                name="country"
                value="{{ old('country', $brand->country) }}"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"
                required
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
                required
            >
                <option value="1" {{ old('status', $brand->status) == '1' ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ old('status', $brand->status) == '0' ? 'selected' : '' }}>Tạm ẩn</option>
            </select>
            @error('status')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nút --}}
        <div class="flex items-center justify-between mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded">
                💾 Cập nhật
            </button>
            <a href="{{ route('admin.brands.index') }}" class="text-gray-600 hover:underline text-sm">
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
