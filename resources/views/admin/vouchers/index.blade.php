@extends('layout.admin')

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-700">📋 Danh sách mã giảm giá</h2>
        <a href="{{ route('admin.vouchers.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">➕ Thêm mới</a>
    </div>

    {{-- Bộ lọc --}}
    <form method="GET" action="{{ route('admin.vouchers.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <input type="text" name="keyword" value="{{ $filters['keyword'] ?? '' }}"
               class="border px-3 py-2 rounded w-full" placeholder="Tìm theo mã...">

        <select name="type" class="border px-3 py-2 rounded">
            <option value="">-- Loại --</option>
            <option value="fixed" @selected(($filters['type'] ?? '') === 'fixed')>Cố định</option>
            <option value="percent" @selected(($filters['type'] ?? '') === 'percent')>Phần trăm</option>
        </select>

        <select name="is_active" class="border px-3 py-2 rounded">
            <option value="">-- Trạng thái --</option>
            <option value="1" @selected(($filters['is_active'] ?? '') === '1')>Hoạt động</option>
            <option value="0" @selected(($filters['is_active'] ?? '') === '0')>Tạm dừng</option>
        </select>

        <select name="only_for_new_user" class="border px-3 py-2 rounded">
            <option value="">-- User mới --</option>
            <option value="1" @selected(($filters['only_for_new_user'] ?? '') === '1')>Chỉ user mới</option>
            <option value="0" @selected(($filters['only_for_new_user'] ?? '') === '0')>Tất cả</option>
        </select>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Lọc</button>
            <a href="{{ route('admin.vouchers.index') }}" class="text-gray-600 underline px-3 py-2">Reset</a>
        </div>
    </form>

    {{-- Bảng dữ liệu --}}
    <div class="overflow-x-auto">
        <table class="w-full border text-sm">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-2">#</th>
                    <th class="p-2">Mã</th>
                    <th class="p-2">Loại</th>
                    <th class="p-2">Giá trị</th>
                    <th class="p-2">Trạng thái</th>
                    <th class="p-2">User mới</th>
                    <th class="p-2">Bắt đầu</th>
                    <th class="p-2">Kết thúc</th>
                    <th class="p-2 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($vouchers as $voucher)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-2">{{ $voucher->id }}</td>
                        <td class="p-2 font-medium">{{ $voucher->code }}</td>
                        <td class="p-2">{{ ucfirst($voucher->type) }}</td>
                        <td class="p-2">{{ $voucher->value }}</td>
                        <td class="p-2">
                            <span class="px-2 py-1 rounded text-xs font-semibold {{ $voucher->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $voucher->is_active ? 'Bật' : 'Tắt' }}
                            </span>
                        </td>
                        <td class="p-2">{{ $voucher->only_for_new_user ? '✔' : '' }}</td>
                        <td class="p-2">{{ optional($voucher->start_date)->format('d/m/Y') }}</td>
                        <td class="p-2">{{ optional($voucher->end_date)->format('d/m/Y') }}</td>
                        <td class="p-2 text-right space-x-2">
                            <a href="{{ route('admin.vouchers.edit', $voucher->id) }}"
                               class="text-blue-600 hover:underline">Sửa</a>
                            <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST"
                                  class="inline-block" onsubmit="return confirm('Xóa mã này?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center p-4">Không có dữ liệu</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $vouchers->links() }}
    </div>
</div>
@endsection
