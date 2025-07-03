@extends('layout.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 shadow rounded">
    <h2 class="text-xl font-bold text-gray-800 mb-4">👁️ Xem chi tiết Banner</h2>

    <div class="space-y-4">
        <div>
            <label class="block text-gray-600 font-semibold">Tiêu đề:</label>
            <div class="text-gray-900">{{ $banner->title }}</div>
        </div>

        <div>
            <label class="block text-gray-600 font-semibold">Ảnh Desktop:</label>
            <img src="{{ asset('storage/' . $banner->image_desk) }}" class="w-60 h-auto rounded shadow mt-2">
        </div>

        @if($banner->image_mobile)
        <div>
            <label class="block text-gray-600 font-semibold">Ảnh Mobile:</label>
            <img src="{{ asset('storage/' . $banner->image_mobile) }}" class="w-60 h-auto rounded shadow mt-2">
        </div>
        @endif

        <div>
            <label class="block text-gray-600 font-semibold">Vị trí:</label>
            <div class="text-gray-900">{{ $banner->position }}</div>
        </div>

        <div>
            <label class="block text-gray-600 font-semibold">Trạng thái:</label>
            <div class="text-gray-900">
                @if($banner->status)
                    <span class="text-green-600 font-medium">Hiển thị</span>
                @else
                    <span class="text-red-600 font-medium">Ẩn</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-600 font-semibold">Tạo lúc:</label>
                <div class="text-gray-700">{{ $banner->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div>
                <label class="block text-gray-600 font-semibold">Cập nhật lúc:</label>
                <div class="text-gray-700">{{ $banner->updated_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        <div class="mt-6 text-right">
            <a href="{{ route('admin.banners.edit', $banner) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">✏️ Chỉnh sửa</a>
            <a href="{{ route('admin.banners.index') }}" class="ml-2 text-gray-600 hover:underline">⬅ Quay lại danh sách</a>
        </div>
    </div>
</div>
@endsection
