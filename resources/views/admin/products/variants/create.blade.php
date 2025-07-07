@extends('layout.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow p-6 rounded-lg">
    <h2 class="text-xl font-bold mb-6 text-gray-800">➕ Thêm biến thể sản phẩm cho: <span class="text-blue-600">{{ $product->name }}</span></h2>

    <form method="POST" action="{{ route('admin.products.variants.store', $product->id) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Grid thông tin -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">SKU *</label>
                <input type="text" name="sku" value="{{ old('sku') }}"
                placeholder="Nên để sku tự sinh( không cần nhập )"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100" />
                @error('sku')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Giá gốc *</label>
                <input type="number" name="original_price" step="0.01" value="{{ old('original_price') }}"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100" />
                @error('original_price')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Giảm giá (%)</label>
                <input type="number" name="sale_percent" step="0.01" value="{{ old('sale_percent', 0) }}"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100" />
                <p class="text-xs text-gray-500 mt-1">Hệ thống sẽ tự tính giá bán sau khi giảm.</p>
                @error('sale_percent')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Giá nhập *</label>
                <input type="number" name="import_price" step="0.01" value="{{ old('import_price') }}"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100" />
                @error('import_price')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Số lượng *</label>
                <input type="number" name="quantity" value="{{ old('quantity') }}"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100" />
                @error('quantity')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">RAM</label>
                <input type="text" name="ram" value="{{ old('ram') }}"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bộ nhớ</label>
                <input type="text" name="storage" value="{{ old('storage') }}"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Màu sắc</label>
                <input type="text" name="color" value="{{ old('color') }}"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Màn hình</label>
                <input type="text" name="screen_size" value="{{ old('screen_size') }}"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pin</label>
                <input type="text" name="battery" value="{{ old('battery') }}"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Chip</label>
                <input type="text" name="chip" value="{{ old('chip') }}"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hệ điều hành</label>
                <input type="text" name="operating_system" value="{{ old('operating_system') }}"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100" />
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Trọng lượng</label>
                <input type="text" name="weight" value="{{ old('weight') }}"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100" />
            </div>
        </div>

        <!-- Trạng thái -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
            <select name="status" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>

        <!-- Ảnh -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ảnh biến thể (chọn nhiều ảnh)</label>
            <input type="file" name="images[]" id="images-input" multiple accept="image/*"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100" />
            @error('images.*')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Preview ảnh -->
        <div id="preview-images" class="flex flex-wrap gap-3 mt-3"></div>

        <!-- Nút hành động -->
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
                    <img src="${e.target.result}" class="w-full h-full object-cover border rounded shadow-sm">
                `;
                preview.appendChild(imgBox);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush
