@extends('layout.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">📦 Quản lý đơn hàng</h2>

    {{-- Bộ lọc tìm kiếm --}}
    <form method="GET" class="flex flex-col md:flex-row items-center gap-4 mb-6">
        <input type="text" name="q" value="{{ request('q') }}"
            placeholder="Tìm mã đơn hàng..." 
            class="px-4 py-2 border rounded w-full md:w-1/3" />

        <select name="status" class="px-4 py-2 border rounded">
            <option value="">-- Trạng thái --</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Đang xử lý</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
        </select>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">🔍 Lọc</button>
            <a href="{{ route('admin.orders.index') }}"
            class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">🔄 Reset</a>
        </div>
    </form>


    {{-- Danh sách đơn hàng --}}
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full text-sm text-left text-gray-700">
            <thead class="bg-gray-100 text-gray-700 font-semibold">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Mã đơn</th>
                    <th class="px-4 py-2">Người mua</th>
                    <th class="px-4 py-2">Tổng tiền</th>
                    <th class="px-4 py-2">Trạng thái</th>
                    <th class="px-4 py-2">Thanh toán</th>
                    <th class="px-4 py-2">Ngày đặt</th>
                    <th class="px-4 py-2 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 font-semibold text-blue-700">#{{ $order->code }}</td>
                        <td class="px-4 py-2">
                            {{ $order->customer_name }}<br>
                            <small class="text-gray-500">{{ $order->customer_phone }}</small>
                        </td>
                        <td class="px-4 py-2 text-red-600 font-bold">{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                        <td class="px-4 py-2 capitalize {{ $order->status === 'completed' ? 'text-green-600' : ($order->status === 'cancelled' ? 'text-red-500' : 'text-yellow-600') }}">
                            {{ $order->status }}
                        </td>
                        <td class="px-4 py-2">
                            {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                        </td>
                        <td class="px-4 py-2">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-2 text-right space-x-1">
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                               class="text-blue-600 hover:underline text-sm">Xem</a>
                            <form action="{{ route('admin.orders.destroy', $order->id) }}"
                                  method="POST" class="inline-block"
                                  onsubmit="return confirm('Bạn có chắc muốn xoá đơn hàng này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-sm">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4 text-gray-500">Không có đơn hàng nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Phân trang --}}
    <div class="mt-4">
        {{ $orders->appends(request()->query())->links() }}
    </div>
</div>
@endsection
