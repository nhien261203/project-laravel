@extends('layout.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow p-6 rounded-lg">
    <h2 class="text-xl font-bold mb-6 text-gray-700">Chi tiết mã giảm giá</h2>

    <div class="space-y-4">
        {{-- Mã --}}
        <div>
            <p class="text-sm text-gray-500">Mã giảm giá</p>
            <p class="font-semibold text-gray-800 text-lg">{{ $voucher->code }}</p>
        </div>

        {{-- Loại + Giá trị --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Loại</p>
                <p class="font-semibold text-gray-800">
                    {{ $voucher->type === 'fixed' ? 'Cố định (VNĐ)' : 'Phần trăm (%)' }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Giá trị</p>
                <p class="font-semibold text-gray-800">
                    {{ number_format($voucher->value) }}{{ $voucher->type === 'percent' ? '%' : ' đ' }}
                </p>
            </div>
        </div>

        {{-- Các giới hạn --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-gray-500">Tối đa / User</p>
                <p class="font-semibold text-gray-800">{{ $voucher->max_usage_per_user ?? 'Không giới hạn' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Đơn hàng tối thiểu</p>
                <p class="font-semibold text-gray-800">
                    {{ $voucher->min_order_amount ? number_format($voucher->min_order_amount).' đ' : 'Không yêu cầu' }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Giảm tối đa</p>
                <p class="font-semibold text-gray-800">
                    {{ $voucher->max_discount ? number_format($voucher->max_discount).' đ' : 'Không giới hạn' }}
                </p>
            </div>
        </div>

        {{-- Cờ và trạng thái --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Áp dụng cho user mới</p>
                <p class="font-semibold text-gray-800">
                    {{ $voucher->only_for_new_user ? 'Có' : 'Không' }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Trạng thái</p>
                <span class="inline-block px-3 py-1 text-sm rounded-full font-semibold 
                    {{ $voucher->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                    {{ $voucher->is_active ? 'Hoạt động' : 'Tạm dừng' }}
                </span>
            </div>
        </div>

        {{-- Ngày bắt đầu / kết thúc --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Bắt đầu</p>
                <p class="font-semibold text-gray-800">
                    {{ $voucher->start_date ? $voucher->start_date->format('d/m/Y H:i') : 'Không giới hạn' }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Kết thúc</p>
                <p class="font-semibold text-gray-800">
                    {{ $voucher->end_date ? $voucher->end_date->format('d/m/Y H:i') : 'Không giới hạn' }}
                </p>
            </div>
        </div>

        {{-- Ngày tạo / cập nhật --}}
        <div class="border-t pt-4 mt-6 text-sm text-gray-500">
            <p>Tạo lúc: {{ $voucher->created_at->format('d/m/Y H:i') }}</p>
            <p>Cập nhật: {{ $voucher->updated_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="flex items-center justify-between mt-8">
        <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" 
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded">
            Chỉnh sửa
        </a>

        <a href="{{ route('admin.vouchers.index') }}" 
            class="text-gray-600 hover:underline text-sm">
            ← Quay lại danh sách
        </a>
    </div>
</div>
@endsection
