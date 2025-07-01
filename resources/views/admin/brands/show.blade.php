@extends('layout.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow p-6 rounded-lg">
    <h2 class="text-xl font-bold text-gray-800 mb-6">📄 Chi tiết thương hiệu</h2>

    <div class="space-y-5">
        <div>
            <h4 class="text-sm font-semibold text-gray-600">Tên thương hiệu</h4>
            <p class="text-lg text-gray-800">{{ $brand->name }}</p>
        </div>

        <div>
            <h4 class="text-sm font-semibold text-gray-600">Slug</h4>
            <p class="text-gray-700">{{ $brand->slug }}</p>
        </div>

        <div>
            <h4 class="text-sm font-semibold text-gray-600">Logo</h4>
            @if ($brand->logo)
                <img src="{{ asset('storage/' . $brand->logo) }}" alt="Logo" class="w-32 h-32 object-contain border rounded mt-2">
            @else
                <p class="text-gray-500 italic">Không có logo</p>
            @endif
        </div>

        <div>
            <h4 class="text-sm font-semibold text-gray-600">Quốc gia</h4>
            <p class="text-gray-700">{{ $brand->country }}</p>
        </div>

        <div>
            <h4 class="text-sm font-semibold text-gray-600">Trạng thái</h4>
            <span class="px-3 py-1 rounded-full text-sm font-semibold
                {{ $brand->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $brand->status ? 'Hoạt động' : 'Tạm ẩn' }}
            </span>
        </div>

        <div class="pt-6 flex justify-between">
            <a href="{{ route('admin.brands.edit', $brand->id) }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded shadow">
                ✏️ Chỉnh sửa
            </a>

            <a href="{{ route('admin.brands.index') }}"
               class="text-sm text-gray-600 hover:underline">
                ← Quay lại danh sách
            </a>
        </div>
    </div>
</div>
@endsection
