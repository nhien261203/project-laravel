@extends('layout.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow p-6 rounded-lg">
    <h2 class="text-xl font-bold text-gray-800 mb-6">➕ Thêm biến thể sản phẩm cho: {{ $product->name }}</h2>

    <form method="POST" action="{{ route('admin.products.variants.store', $product->id) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Các field thông tin chung -->
            <div>
                <label class="block text-sm font-medium text-gray-700">SKU *</label>
                <input type="text" name="sku" value="{{ old('sku') }}" class="form-input w-full">
                @error('sku')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Giá bán *</label>
                <input type="number" name="price" step="0.01" value="{{ old('price') }}" class="form-input w-full">
                @error('price')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Giá gốc</label>
                <input type="number" name="original_price" step="0.01" value="{{ old('original_price') }}" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Số lượng *</label>
                <input type="number" name="quantity" value="{{ old('quantity') }}" class="form-input w-full">
                @error('quantity')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div><label class="block text-sm font-medium text-gray-700">RAM</label><input type="text" name="ram" value="{{ old('ram') }}" class="form-input w-full"></div>
            <div><label class="block text-sm font-medium text-gray-700">Bộ nhớ</label><input type="text" name="storage" value="{{ old('storage') }}" class="form-input w-full"></div>
            <div><label class="block text-sm font-medium text-gray-700">Màu sắc</label><input type="text" name="color" value="{{ old('color') }}" class="form-input w-full"></div>
            <div><label class="block text-sm font-medium text-gray-700">Màn hình</label><input type="text" name="screen_size" value="{{ old('screen_size') }}" class="form-input w-full"></div>
            <div><label class="block text-sm font-medium text-gray-700">Pin</label><input type="text" name="battery" value="{{ old('battery') }}" class="form-input w-full"></div>
            <div><label class="block text-sm font-medium text-gray-700">Chip</label><input type="text" name="chip" value="{{ old('chip') }}" class="form-input w-full"></div>
            <div><label class="block text-sm font-medium text-gray-700">Trọng lượng</label><input type="text" name="weight" value="{{ old('weight') }}" class="form-input w-full"></div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
            <select name="status" class="form-select w-full">
                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>

        <!-- Ảnh -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Ảnh biến thể (chọn nhiều ảnh)</label>
            <input type="file" name="images[]" id="images-input" multiple accept="image/*" class="form-input w-full">
            @error('images.*')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Preview ảnh -->
        <div id="preview-images" class="flex flex-wrap gap-4 mt-4"></div>

        <!-- Nút submit -->
        <div class="pt-4 flex justify-between items-center">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded">
                💾 Lưu biến thể
            </button>
            <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-600 hover:underline">
                ← Quay lại danh sách sản phẩm
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('images-input').addEventListener('change', function (event) {
        const preview = document.getElementById('preview-images');
        preview.innerHTML = '';
        const files = event.target.files;

        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const imgBox = document.createElement('div');
                imgBox.classList.add('relative', 'w-24', 'h-24');
                imgBox.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-contain border rounded">
                `;
                preview.appendChild(imgBox);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush
