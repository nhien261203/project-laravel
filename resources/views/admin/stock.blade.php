@extends('layout.admin')

@section('title', 'Thống kê tồn kho')

@section('content')
<div class="p-4 md:p-6 space-y-6">

    {{-- Tiêu đề --}}
    <div class="flex items-center justify-between">
        <h2 class="text-xl md:text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-boxes text-blue-600"></i> Thống kê tồn kho sản phẩm
        </h2>
    </div>

    {{-- Bộ lọc --}}
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-white p-4 rounded-xl shadow">
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Danh mục</label>
            <select name="category_id" class="w-full border-gray-300 rounded-lg">
                <option value="">-- Tất cả --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Ngưỡng tồn kho</label>
            <input type="number" name="threshold" value="{{ $threshold }}" min="1"
                   class="w-full border-gray-300 rounded-lg">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Sắp xếp</label>
            <select name="sort" class="w-full border-gray-300 rounded-lg">
                <option value="asc" {{ $sortOrder == 'asc' ? 'selected' : '' }}>Tồn kho thấp → cao</option>
                <option value="desc" {{ $sortOrder == 'desc' ? 'selected' : '' }}>Tồn kho cao → thấp</option>
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
                <i class="fas fa-filter"></i> Lọc
            </button>
            <a href="{{ route('admin.stock-all') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg shadow hover:bg-gray-300">
                <i class="fas fa-undo"></i> Reset
            </a>
        </div>
    </form>

    {{-- Bảng dữ liệu --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Ảnh</th>
                        <th class="px-4 py-3">Sản phẩm</th>
                        <th class="px-4 py-3">Màu</th>
                        <th class="px-4 py-3">Dung lượng</th>
                        <th class="px-4 py-3">Số lượng nhập</th>
                        <th class="px-4 py-3">Đã bán</th>
                        <th class="px-4 py-3">Tồn kho</th>
                        <th class="px-4 py-3">Danh mục</th>
                        <th class="px-4 py-3">Thương hiệu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($products as $index => $item)
                        @php
                            $stock = $item['stock'];
                            $badge = "bg-green-100 text-green-700";
                            $icon = "fa-check-circle";

                            if ($stock <= 3) {
                                $badge = "bg-red-100 text-red-700";
                                $icon = "fa-triangle-exclamation";
                            }
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $products->firstItem() + $index }}</td>
                            <td class="px-4 py-3">
                                <img src="{{ $item['image'] ?? '/images/no-image.png' }}" 
                                     class="w-12 h-12 rounded-lg object-cover">
                            </td>
                            <td class="px-4 py-3 font-semibold">{{ $item['product_name'] }}</td>
                            <td class="px-4 py-3">{{ $item['color'] }}</td>
                            <td class="px-4 py-3">{{ $item['storage'] }}</td>
                            <td class="px-4 py-3">{{ $item['quantity'] }}</td>
                            <td class="px-4 py-3">{{ $item['sold'] }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium {{ $badge }}">
                                    <i class="fas {{ $icon }}"></i> {{ $stock }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $item['category'] }}</td>
                            <td class="px-4 py-3">{{ $item['brand'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-6 text-gray-500">
                                <i class="fas fa-box-open text-2xl mb-2"></i><br>
                                Không có sản phẩm tồn kho thấp
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Phân trang --}}
    <div>
        {{ $products->appends(request()->query())->links('pagination.custom-tailwind') }}
    </div>
</div>
@endsection
