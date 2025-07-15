@extends('layout.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow p-6 rounded-lg">
    <h2 class="text-xl font-bold mb-6 text-gray-700">➕ Tạo mã giảm giá mới</h2>

    <form action="{{ route('admin.vouchers.store') }}" method="POST" class="space-y-5">
        @csrf

        {{-- Mã voucher --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mã giảm giá *</label>
            <input type="text" name="code" value="{{ old('code') }}" placeholder="SALE2025, GIAM50K..."
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
            @error('code') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Loại + Giá trị --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Loại *</label>
                <select name="type" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
                    <option value="">-- Chọn --</option>
                    <option value="fixed" @selected(old('type') == 'fixed')>Cố định (VNĐ)</option>
                    <option value="percent" @selected(old('type') == 'percent')>Phần trăm (%)</option>
                </select>
                @error('type') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Giá trị *</label>
                <input type="number" name="value" value="{{ old('value') }}" min="1"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
                @error('value') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Các giới hạn --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tổng lượt dùng</label>
                <input type="number" name="max_usage" value="{{ old('max_usage') }}" min="1"
                    class="w-full px-4 py-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tối đa / User</label>
                <input type="number" name="max_usage_per_user" value="{{ old('max_usage_per_user') }}" min="1"
                    class="w-full px-4 py-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Đơn hàng tối thiểu (đ)</label>
                <input type="number" name="min_order_amount" value="{{ old('min_order_amount') }}"
                    class="w-full px-4 py-2 border rounded" placeholder="5000000">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Giảm tối đa (đ)</label>
                <input type="number" name="max_discount" value="{{ old('max_discount') }}" min="0"
                    class="w-full px-4 py-2 border rounded">
            </div>
        </div>

        {{-- Áp dụng và thời gian --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Áp dụng cho user mới</label>
                <select name="only_for_new_user" class="w-full px-4 py-2 border rounded">
                    <option value="0" @selected(old('only_for_new_user') == '0')>Không</option>
                    <option value="1" @selected(old('only_for_new_user') == '1')>Có</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái *</label>
                <select name="is_active" class="w-full px-4 py-2 border rounded">
                    <option value="1" @selected(old('is_active') == '1')>Hoạt động</option>
                    <option value="0" @selected(old('is_active') == '0')>Tạm dừng</option>
                </select>
            </div>
        </div>

        {{-- Ngày bắt đầu / kết thúc --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bắt đầu</label>
                <input type="datetime-local" name="start_date" value="{{ old('start_date') }}"
                    class="w-full px-4 py-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kết thúc</label>
                <input type="datetime-local" name="end_date" value="{{ old('end_date') }}"
                    class="w-full px-4 py-2 border rounded">
            </div>
        </div>

        <div class="flex items-center justify-between mt-6">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded">
                💾 Lưu mã
            </button>
            <a href="{{ route('admin.vouchers.index') }}" class="text-gray-600 hover:underline text-sm">
                ← Quay lại danh sách
            </a>
        </div>
    </form>
</div>
@endsection
