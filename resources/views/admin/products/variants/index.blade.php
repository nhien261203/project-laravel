@extends('layout.admin')

@section('content')
<div class="max-w-6xl mx-auto bg-white shadow p-6 rounded-lg">
    <h2 class="text-xl font-bold text-gray-800 mb-6">📦 Danh sách biến thể của sản phẩm: {{ $product->name }}</h2>

    <div class="mb-4 text-right">
        <a href="{{ route('admin.products.variants.create', $product->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">➕ Thêm biến thể</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">SKU</th>
                    <th class="px-4 py-2">Giá bán</th>
                    <th class="px-4 py-2">Số lượng</th>
                    <th class="px-4 py-2">Ảnh chính</th>
                    <th class="px-4 py-2">Trạng thái</th>
                    <th class="px-4 py-2">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($variants as $index => $variant)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $variant->sku }}</td>
                        <td class="px-4 py-2">{{ number_format($variant->price) }} đ</td>
                        <td class="px-4 py-2">{{ $variant->quantity }}</td>
                        <td class="px-4 py-2">
                            @php
                                $primaryImage = $variant->images->firstWhere('is_primary', 1);
                            @endphp
                            @if($primaryImage)
                                <img src="{{ asset('storage/' . $primaryImage->image_path) }}" alt="Ảnh chính" class="w-12 h-12 object-contain rounded">
                            @else
                                <span class="text-gray-400 italic">Không có</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $variant->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $variant->status ? 'Hiển thị' : 'Ẩn' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="{{ route('admin.products.variants.edit', [$product->id, $variant->id]) }}" class="text-yellow-500 hover:underline">✏️ Sửa</a>
                            <form action="{{ route('admin.products.variants.destroy', [$product->id, $variant->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xoá?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">🗑️ Xoá</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
