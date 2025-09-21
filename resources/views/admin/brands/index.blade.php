@extends('layout.admin')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-xl font-bold">📦 Danh sách Brand</h1>
    <a href="{{ route('admin.brands.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">+ Thêm Brand</a>
</div>

<!-- Thông tin kết quả -->
<div class="mb-4 text-sm text-gray-600">
    <p>🔍 Hiển thị <strong>{{ $brands->total() }}</strong> brand{{ $brands->total() > 1 ? 's' : '' }}.</p>

    @if(request()->hasAny(['name', 'country', 'status']))
        <p>
            Kết quả tìm kiếm:
            @if(request('name')) <span class="text-blue-600">Tên chứa "{{ request('name') }}"</span> @endif
            @if(request('country')) <span class="text-blue-600">Quốc gia "{{ request('country') }}"</span> @endif
            @if(request('status') !== null)
                <span class="text-blue-600">Trạng thái: {{ request('status') == 1 ? 'Hoạt động' : 'Tạm ẩn' }}</span>
            @endif
        </p>
    @endif
</div>

<!-- Filter -->
<form method="GET" class="mb-6 flex flex-wrap gap-3 items-center">
    <input type="text" name="name" placeholder="Tên brand..." value="{{ request('name') }}"
        class="px-3 py-2 rounded border border-gray-300 shadow-sm focus:ring focus:ring-blue-100 focus:outline-none" />
    
    <input type="text" name="country" placeholder="Quốc gia..." value="{{ request('country') }}"
        class="px-3 py-2 rounded border border-gray-300 shadow-sm focus:ring focus:ring-blue-100 focus:outline-none" />
    
    <select name="status"
        class="px-3 py-2 rounded border border-gray-300 shadow-sm focus:ring focus:ring-blue-100 focus:outline-none">
        <option value="">-- Trạng thái --</option>
        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Tạm ẩn</option>
    </select>

    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lọc</button>
    <a href="{{ route('admin.brands.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Reset</a>
</form>

<!-- Table -->
<table class="table-auto w-full bg-white shadow rounded mb-6">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-3">#</th>
            <th class="p-3 text-left">Tên</th>
            <th class="p-3 text-center">Logo</th>
            <th class="p-3 text-center">Quốc gia</th>
            <th class="p-3 text-center">Trạng thái</th>
            <th class="p-3 text-center">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse($brands as $brand)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3 text-center">{{ $loop->iteration + ($brands->currentPage() - 1) * $brands->perPage() }}</td>
                <td class="p-3">{{ $brand->name }}</td>
                <td class="p-3 text-center">
                    @if($brand->logo)
                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="Logo"
                            class="w-16 h-16 mx-auto object-contain rounded border" />
                    @endif
                </td>
                <td class="p-3 text-center">{{ $brand->country }}</td>
                <td class="p-3 text-center">
                    <span class="px-2 py-1 text-sm rounded {{ $brand->status ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                        {{ $brand->status ? 'Hoạt động' : 'Tạm ẩn' }}
                    </span>
                </td>
                <td class="p-3 space-x-2 text-center">
                    <a href="{{ route('admin.brands.edit', $brand->id) }}" class="text-blue-600 hover:underline">sửa</a>
                    @include('partials.delete-confirm', [
                        'action' => route('admin.brands.destroy', $brand->id)
                    ])

                    <a href="{{ route('admin.brands.show', $brand->id) }}" class="text-green-600 hover:underline">xem</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="p-4 text-center text-gray-500">Không có brand nào.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Pagination -->
<div class="mt-4">
    {{-- {{ $brands->links() }} --}}
    {{ $brands->links('pagination.custom-tailwind') }}
</div>
@endsection
