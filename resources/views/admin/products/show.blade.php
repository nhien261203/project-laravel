@extends('layout.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow p-6 rounded-lg">
    <h2 class="text-xl font-bold text-gray-800 mb-6">📄 Chi tiết sản phẩm</h2>

    <div class="space-y-5">
        <div>
            <h4 class="text-sm font-semibold text-gray-600">Tên sản phẩm</h4>
            <p class="text-lg text-gray-800">{{ $product->name }}</p>
        </div>

        <div>
            <h4 class="text-sm font-semibold text-gray-600">Slug</h4>
            <p class="text-gray-700">{{ $product->slug }}</p>
        </div>

        <div>
            <h4 class="text-sm font-semibold text-gray-600">Danh mục</h4>
            <p class="text-gray-700">{{ optional($product->category)->name ?? '—' }}</p>
        </div>

        <div>
            <h4 class="text-sm font-semibold text-gray-600">Thương hiệu</h4>
            <p class="text-gray-700">{{ optional($product->brand)->name ?? '—' }}</p>
        </div>

        <div>
            <h4 class="text-sm font-semibold text-gray-600">Mô tả</h4>
            <div class="prose max-w-none text-gray-800">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>

        <div>
            <h4 class="text-sm font-semibold text-gray-600">Trạng thái</h4>
            <span class="px-3 py-1 rounded-full text-sm font-semibold
                {{ $product->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $product->status ? 'Hiển thị' : 'Tạm ẩn' }}
            </span>
        </div>

        <div>
            <h4 class="text-sm font-semibold text-gray-600">Ngày tạo</h4>
            <p class="text-gray-700">{{ $product->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <div>
            <h4 class="text-sm font-semibold text-gray-600">Ngày cập nhật</h4>
            <p class="text-gray-700">{{ $product->updated_at->format('d/m/Y H:i') }}</p>
        </div>

        <div class="pt-6 flex justify-between">
            <a href="{{ route('admin.products.edit', $product->id) }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded shadow">
                ✏️ Chỉnh sửa
            </a>

            <a href="{{ route('admin.products.index') }}"
               class="text-sm text-gray-600 hover:underline">
                ← Quay lại danh sách
            </a>
        </div>
    </div>
</div>
@endsection
