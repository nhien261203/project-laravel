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
                <input type="text" name="sku" value="{{ old('sku', $variant->sku) }}" class="form-input w-full" required>
                @error('sku')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Giá bán *</label>
                <input type="number" name="price" step="0.01" value="{{ old('price', $variant->price) }}" class="form-input w-full" required>
                @error('price')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Giá gốc</label>
                <input type="number" name="original_price" step="0.01" value="{{ old('original_price', $variant->original_price) }}" class="form-input w-full">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Số lượng *</label>
                <input type="number" name="quantity" value="{{ old('quantity', $variant->quantity) }}" class="form-input w-full" required>
                @error('quantity')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">RAM</label>
                <input type="text" name="ram" value="{{ old('ram', $variant->ram) }}" class="form-input w-full">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Bộ nhớ</label>
                <input type="text" name="storage" value="{{ old('storage', $variant->storage) }}" class="form-input w-full">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Màu sắc</label>
                <input type="text" name="color" value="{{ old('color', $variant->color) }}" class="form-input w-full">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Màn hình</label>
                <input type="text" name="screen_size" value="{{ old('screen_size', $variant->screen_size) }}" class="form-input w-full">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Pin</label>
                <input type="text" name="battery" value="{{ old('battery', $variant->battery) }}" class="form-input w-full">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Chip</label>
                <input type="text" name="chip" value="{{ old('chip', $variant->chip) }}" class="form-input w-full">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Trọng lượng</label>
                <input type="text" name="weight" value="{{ old('weight', $variant->weight) }}" class="form-input w-full">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
            <select name="status" class="form-select w-full">
                <option value="1" {{ old('status', $variant->status) == '1' ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ old('status', $variant->status) == '0' ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Ảnh hiện tại:</label>
            <div class="flex flex-wrap gap-4 mt-2">
                @foreach($variant->images as $image)
                    <div class="relative w-24 h-24">
                        <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-contain border rounded">
                        <div class="absolute top-0 right-0">
                            <input type="radio" name="primary_image_id" value="{{ $image->id }}" {{ $image->is_primary ? 'checked' : '' }}>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Thêm ảnh mới (nhiều ảnh)</label>
            <input type="file" name="images[]" multiple accept="image/*" class="form-input w-full">
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
