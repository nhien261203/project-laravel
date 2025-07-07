@extends('layout.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow p-6 rounded-lg">
    <h2 class="text-xl font-bold text-gray-800 mb-6">🔍 Chi tiết biến thể sản phẩm: {{ $variant->product->name ?? 'Không xác định' }}</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <div><strong>SKU:</strong> {{ $variant->sku }}</div>
        <div><strong>Giá niêm yết:</strong> {{ number_format($variant->original_price) }} đ</div>
        <div><strong>Giảm giá:</strong> {{ $variant->sale_percent }}%</div>
        <div><strong>Giá bán:</strong> {{ number_format($variant->price) }} đ</div>

        <div><strong>Giá nhập:</strong> {{ number_format($variant->import_price) }} đ</div>
        <div><strong>Số lượng:</strong> {{ $variant->quantity }}</div>
        <div><strong>RAM:</strong> {{ $variant->ram }}</div>
        <div><strong>Bộ nhớ:</strong> {{ $variant->storage }}</div>
        <div><strong>Màu sắc:</strong> {{ $variant->color }}</div>
        <div><strong>Màn hình:</strong> {{ $variant->screen_size }}</div>
        <div><strong>Pin:</strong> {{ $variant->battery }}</div>
        <div><strong>Chip:</strong> {{ $variant->chip }}</div>
        <div><strong>Trọng lượng:</strong> {{ $variant->weight }}</div>
        <div><strong>Hệ điều hành:</strong> {{ $variant->operating_system }}</div>
        <div><strong>Trạng thái:</strong> {{ $variant->status ? 'Hiển thị' : 'Ẩn' }}</div>
    </div>

    <div class="mt-6">
        <h3 class="font-semibold text-gray-700 mb-2">Ảnh biến thể:</h3>
        <div class="flex flex-wrap gap-4">
            @foreach($variant->images as $image)
                <div class="relative w-24 h-24">
                    <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-contain border rounded">
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.products.variants.index', $variant->product_id) }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-sm text-gray-700 px-4 py-2 rounded">
            ← Quay lại danh sách biến thể
        </a>
    </div>
</div>
@endsection
