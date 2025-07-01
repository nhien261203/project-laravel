@extends('layout.admin')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-xl font-bold">📦 Danh sách Sản phẩm</h1>
    <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">+ Thêm sản phẩm</a>
</div>

<!-- Thông tin kết quả -->
<div class="mb-4 text-sm text-gray-600">
    <p>🔍 Có tổng cộng <strong>{{ $products->total() }}</strong> sản phẩm.</p>

    @if(request()->hasAny(['keyword', 'category_id', 'brand_id', 'status']))
        <p>
            Kết quả lọc:
            @if(request('keyword')) <span class="text-blue-600">Tên chứa "{{ request('keyword') }}"</span> @endif
            @if(request('category_id')) <span class="text-blue-600">Danh mục ID {{ request('category_id') }}</span> @endif
            @if(request('brand_id')) <span class="text-blue-600">Thương hiệu ID {{ request('brand_id') }}</span> @endif
            @if(request('status') !== null)
                <span class="text-blue-600">Trạng thái: {{ request('status') == 1 ? 'Hiển thị' : 'Ẩn' }}</span>
            @endif
        </p>
    @endif
</div>

<!-- Filter -->
<form method="GET" class="mb-6 flex flex-wrap gap-3 items-center">
    <input type="text" name="keyword" placeholder="Tên sản phẩm..." value="{{ request('keyword') }}"
        class="px-3 py-2 rounded border border-gray-300 shadow-sm focus:ring focus:ring-blue-100 focus:outline-none" />

    <select name="category_id"
        class="px-3 py-2 rounded border border-gray-300 shadow-sm focus:ring focus:ring-blue-100 focus:outline-none">
        <option value="">-- Danh mục --</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>

    <select name="brand_id"
        class="px-3 py-2 rounded border border-gray-300 shadow-sm focus:ring focus:ring-blue-100 focus:outline-none">
        <option value="">-- Thương hiệu --</option>
        @foreach($brands as $brand)
            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                {{ $brand->name }}
            </option>
        @endforeach
    </select>

    <select name="status"
        class="px-3 py-2 rounded border border-gray-300 shadow-sm focus:ring focus:ring-blue-100 focus:outline-none">
        <option value="">-- Trạng thái --</option>
        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hiển thị</option>
        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn</option>
    </select>

    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">🔍 Tìm</button>
    <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">🔄 Reset</a>
</form>

<!-- Table -->
<table class="table-auto w-full bg-white shadow rounded mb-6">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-3 text-left">Tên</th>
            <th class="p-3 text-center">Slug</th>
            <th class="p-3 text-center">Danh mục</th>
            <th class="p-3 text-center">Thương hiệu</th>
            <th class="p-3 text-center">Trạng thái</th>
            <th class="p-3 text-center">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $product)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3">{{ $product->name }}</td>
                <td class="p-3 text-center">{{ $product->slug }}</td>
                <td class="p-3 text-center">{{ $product->category->name ?? '-' }}</td>
                <td class="p-3 text-center">{{ $product->brand->name ?? '-' }}</td>
                <td class="p-3 text-center">
                    <span class="px-2 py-1 text-sm rounded {{ $product->status ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                        {{ $product->status ? 'Hiển thị' : 'Ẩn' }}
                    </span>
                </td>
                <td class="p-3 space-x-2 text-center">
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-600 hover:underline">✏️</a>
                    @include('partials.delete-confirm', [
                        'action' => route('admin.products.destroy', $product->id)
                    ])
                    <a href="{{ route('admin.products.show', $product->id) }}" class="text-green-600 hover:underline">👁️</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="p-4 text-center text-gray-500">Không có sản phẩm nào.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Pagination -->
<div class="mt-4">
    {{ $products->links('pagination.custom-tailwind') }}
</div>
@endsection
