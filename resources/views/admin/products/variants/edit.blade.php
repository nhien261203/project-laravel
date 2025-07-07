@extends('layout.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow p-6 rounded-lg">
    <h2 class="text-xl font-bold text-gray-800 mb-6">✏️ Chỉnh sửa biến thể sản phẩm: {{ $product->name }}</h2>

    <form method="POST" action="{{ route('admin.products.variants.update', [$product->id, $variant->id]) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">SKU *</label>
                <input type="text" name="sku" value="{{ old('sku', $variant->sku) }}" 
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
                @error('sku')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Bỏ "Giá bán" và thay bằng: -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Giá gốc *</label>
                <input type="number" name="original_price" step="0.01" value="{{ old('original_price', $variant->original_price) }}" 
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
                @error('original_price')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Giảm giá (%)</label>
                <input type="number" name="sale_percent" step="0.01" value="{{ old('sale_percent', $variant->sale_percent) }}"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
                <p class="text-xs text-gray-500 mt-1">Hệ thống sẽ tự tính giá bán dựa trên giảm giá.</p>
                @error('sale_percent')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Giá nhập *</label>
                <input type="number" name="import_price" step="0.01" value="{{ old('import_price', $variant->import_price) }}"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
                @error('import_price')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Số lượng *</label>
                <input type="number" name="quantity" value="{{ old('quantity', $variant->quantity) }}" 
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
                @error('quantity')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div><label class="block text-sm font-medium text-gray-700">RAM</label><input type="text" name="ram" value="{{ old('ram', $variant->ram) }}" 
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"></div>
            <div><label class="block text-sm font-medium text-gray-700">Bộ nhớ</label>
                <input type="text" name="storage" value="{{ old('storage', $variant->storage) }}" 
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"></div>
            <div><label class="block text-sm font-medium text-gray-700">Màu sắc</label><input type="text" name="color" value="{{ old('color', $variant->color) }}" 
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"></div>
            <div><label class="block text-sm font-medium text-gray-700">Màn hình</label><input type="text" name="screen_size" value="{{ old('screen_size', $variant->screen_size) }}" 
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"></div>
            <div><label class="block text-sm font-medium text-gray-700">Pin</label><input type="text" name="battery" value="{{ old('battery', $variant->battery) }}" 
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"></div>
            <div><label class="block text-sm font-medium text-gray-700">Chip</label><input type="text" name="chip" value="{{ old('chip', $variant->chip) }}" 
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"></div>
            <div><label class="block text-sm font-medium text-gray-700">Trọng lượng</label><input type="text" name="weight" value="{{ old('weight', $variant->weight) }}" 
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"></div>

            <div><label class="block text-sm font-medium text-gray-700">Hệ Điều Hành</label><input type="text" name="operating_system" value="{{ old('operating_system', $variant->operating_system) }}" 
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100"></div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
            <select name="status" class="form-select w-full">
                <option value="1" {{ old('status', $variant->status) == '1' ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ old('status', $variant->status) == '0' ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>

        <!-- Ảnh hiện tại -->
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Ảnh hiện tại:</label>
            <div id="image-gallery" class="flex flex-wrap gap-4 mt-2">
                @foreach($variant->images as $image)
                    <div class="relative w-24 h-24">
                        <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-contain border rounded">
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Checkbox thay thế -->
        <div class="mt-2">
            <label class="inline-flex items-center">
                <input type="checkbox" name="replace_images" class="form-checkbox text-red-600">
                <span class="ml-2 text-sm text-gray-700">Thay thế toàn bộ ảnh cũ</span>
            </label>
            <p class="text-xs text-gray-500 ml-6">Nếu chọn, ảnh cũ sẽ bị xóa và thay bằng ảnh mới</p>
        </div>

        <!-- Upload ảnh mới -->
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Thêm ảnh mới</label>
            <input type="file" name="images[]" id="images-input" multiple accept="image/*" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
            @error('images.*')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="pt-4 flex justify-between items-center">
            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-6 py-2 rounded">
                💾 Cập nhật biến thể
            </button>
            <a href="{{ route('admin.products.variants.index', $product->id) }}" class="text-sm text-gray-600 hover:underline">
                ← Quay lại danh sách biến thể
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('images-input').addEventListener('change', function (event) {
        const gallery = document.getElementById('image-gallery');
        const files = event.target.files;

        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const imgBox = document.createElement('div');
                imgBox.className = 'relative w-24 h-24 new-preview ring-2 ring-blue-500 shadow-lg rounded overflow-hidden';
                imgBox.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-contain border rounded">`;
                gallery.appendChild(imgBox);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush
