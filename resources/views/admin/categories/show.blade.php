@extends('layout.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow p-6 rounded-lg">
    <h2 class="text-xl font-bold mb-6 text-gray-700">📂 Chi tiết danh mục</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-800">
        <div>
            <label class="font-semibold text-gray-600">Tên danh mục:</label>
            <p class="text-gray-800">{{ $category->name }}</p>
        </div>

        <div>
            <label class="font-semibold text-gray-600">Slug:</label>
            <p class="text-gray-800">{{ $category->slug }}</p>
        </div>

        <div>
            <label class="font-semibold text-gray-600">Danh mục cha:</label>
            <p class="text-gray-800">
                {{ $category->parent ? $category->parent->name : 'Gốc' }}
            </p>
        </div>

        <div class="md:col-span-2">
            <h4 class="text-sm font-semibold text-gray-600">Ảnh</h4>
            @if ($category->logo)
                <img src="{{ asset('storage/' . $category->logo) }}" alt="Logo"
                     class="w-32 h-32 object-contain border rounded mt-2">
            @else
                <p class="text-gray-500 italic mt-1">Không có logo</p>
            @endif
        </div>

        <div>
            <label class="font-semibold text-gray-600">Trạng thái:</label>
            <span class="inline-block px-2 py-1 text-sm rounded 
                {{ $category->status ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                {{ $category->status ? 'Hoạt động' : 'Tạm ẩn' }}
            </span>
        </div>

        <div>
            <label class="font-semibold text-gray-600">Ngày tạo:</label>
            <p class="text-gray-800">{{ $category->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <div>
            <label class="font-semibold text-gray-600">Cập nhật lần cuối:</label>
            <p class="text-gray-800">{{ $category->updated_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
    
        

        <div class="pt-8 flex justify-between">
            <a href="{{ route('admin.categories.edit', $category->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                ✏️ Sửa
            </a>
            <a href="{{ route('admin.categories.index') }}" class="ml-2 text-gray-600 hover:underline">← Quay lại danh sách</a>
        </div>
    
</div>
@endsection