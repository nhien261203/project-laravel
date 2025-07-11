@extends('layout.admin')

@section('content')
<div class="container py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">🧾 Chi tiết đơn hàng #{{ $order->code }}</h1>

    {{-- Thông tin khách hàng --}}
    <div class="bg-white p-6 rounded shadow mb-6 space-y-2">
        <h2 class="text-xl font-semibold text-gray-700 mb-3">👤 Thông tin khách hàng</h2>
        <p><strong>Họ tên:</strong> {{ $order->customer_name }}</p>
        <p><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
        <p><strong>Email:</strong> {{ $order->customer_email ?? 'Không có' }}</p>
        <p><strong>Địa chỉ:</strong> {{ $order->customer_address }}</p>
        <p><strong>Ghi chú:</strong> {{ $order->note ?? 'Không có' }}</p>
    </div>

    {{-- Trạng thái và cập nhật --}}
    <div class="bg-white p-6 rounded shadow mb-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-3">⚙️ Cập nhật đơn hàng</h2>

        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            {{-- Trạng thái đơn hàng --}}
            <div>
                <label for="status" class="block font-medium text-gray-700 mb-1">Trạng thái đơn hàng:</label>
                <select name="status" id="status" class="border w-full rounded px-3 py-2">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>

            {{-- Trạng thái thanh toán --}}
            <div>
                <label for="payment_status" class="block font-medium text-gray-700 mb-1">Trạng thái thanh toán:</label>
                <select name="payment_status" id="payment_status" class="border w-full rounded px-3 py-2">
                    <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                    💾 Lưu thay đổi
                </button>
            </div>
        </form>
    </div>

    {{-- Danh sách sản phẩm --}}
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">🛒 Sản phẩm trong đơn</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left border">
                <thead class="bg-gray-100 text-gray-700 font-semibold">
                    <tr>
                        <th class="border p-3">Ảnh</th>
                        <th class="border p-3">Sản phẩm</th>
                        <th class="border p-3">Số lượng</th>
                        <th class="border p-3">Giá</th>
                        <th class="border p-3">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr class="border-t">
                            <td class="border p-2">
                                <img src="{{ asset('storage/' . $item->image) }}"
                                     alt="{{ $item->product_name }}"
                                     class="w-16 h-16 object-cover rounded border" />
                            </td>
                            <td class="border p-3 align-top">
                                <div class="font-medium text-gray-800">{{ $item->product_name }}</div>
                                <div class="text-gray-500 text-sm">
                                    @if ($item->color) Màu: {{ $item->color }} @endif
                                    @if ($item->color && $item->storage) | @endif
                                    @if ($item->storage) Bộ nhớ: {{ $item->storage }} @endif
                                </div>
                            </td>
                            <td class="border p-3 align-top">{{ $item->quantity }}</td>
                            <td class="border p-3 text-red-600 align-top">
                                {{ number_format($item->price, 0, ',', '.') }}₫
                            </td>
                            <td class="border p-3 text-red-600 font-semibold align-top">
                                {{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Tổng tiền --}}
        <div class="text-right mt-6 text-xl font-bold text-gray-800">
            Tổng tiền: {{ number_format($order->total_amount, 0, ',', '.') }}₫
        </div>
    </div>
</div>
@endsection
