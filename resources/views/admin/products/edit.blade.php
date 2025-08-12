@extends('layout.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow p-6 rounded-lg">
    <h2 class="text-xl font-bold mb-6 text-gray-700">✏️ Cập nhật sản phẩm</h2>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tên sản phẩm *</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100" required />
            @error('name')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Slug (tuỳ chọn)</label>
            <input type="text" name="slug" value="{{ old('slug', $product->slug) }}"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100" />
            @error('slug')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Danh mục *</label>
                <select name="category_id"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100" required>
                    <option value="">-- Chọn danh mục --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Thương hiệu *</label>
                <select name="brand_id"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100" required>
                    <option value="">-- Chọn thương hiệu --</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
                @error('brand_id')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
            <textarea name="description" rows="4" id="summernote"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">{{ old('description', $product->description) }}</textarea>
            @error('description')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái *</label>
            <select name="status"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100" required>
                <option value="1" {{ old('status', $product->status) == '1' ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ old('status', $product->status) == '0' ? 'selected' : '' }}>Tạm ẩn</option>
            </select>
            @error('status')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between mt-6">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded">
                💾 Cập nhật
            </button>
            <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:underline text-sm">
                ← Quay lại danh sách
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<!-- Summernote -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

<script>
    $(document).ready(function () {
        $('#summernote').summernote({
            height: 250,
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['font', ['fontname', 'fontsize', 'color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ],
            fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Merriweather', 'Tahoma', 'Times New Roman', 'Verdana'],
            fontSizes: ['8', '9', '10', '11', '12', '14','16', '18', '24', '36', '48', '64'],
            fontNamesIgnoreCheck: ['Merriweather'] // Nếu font tùy chọn không có trên hệ thống, vẫn hiển thị trong danh sách
        });
    });
</script>

@endpush
