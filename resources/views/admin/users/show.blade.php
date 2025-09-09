@extends('layout.admin')

@section('content')
<div class="container mx-auto p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-bold mb-6">Thông tin người dùng</h1>

    {{-- Thông tin cá nhân --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <p><span class="font-semibold">Tên:</span> {{ $user->name }}</p>
            <p><span class="font-semibold">Email:</span> {{ $user->email }}</p>
            <p><span class="font-semibold">SĐT:</span> {{ $user->phone ?? 'chưa có' }}</p>
            <p><span class="font-semibold">Quyền:</span> {{ $user->roles->pluck('name')->implode(', ') }}</p>
        </div>
        <div>
            <p><span class="font-semibold">Tổng đơn hàng:</span> {{ $totalOrders }}</p>
            <p><span class="font-semibold">Tổng tiền đã mua:</span> {{ number_format($totalAmount) }}đ</p>
        </div>
    </div>

    {{-- Danh sách đơn hàng --}}
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-3">Danh sách đơn hàng</h2>
        @if($user->orders->count())
            <div class="overflow-auto rounded border">
                <table class="min-w-full text-sm text-left border-collapse">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-2 border">Mã đơn</th>
                            <th class="px-4 py-2 border">Ngày mua</th>
                            <th class="px-4 py-2 border">Tổng tiền</th>
                            <th class="px-4 py-2 border">Trạng thái</th>
                            <th class="px-4 py-2 border">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-4 py-2">{{ $order->code }}</td>
                                <td class="border px-4 py-2">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="border px-4 py-2">{{ number_format($order->total_amount) }}đ</td>
                                <td class="border px-4 py-2">{{ $order->statusLabel() }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                    class="text-blue-600 hover:underline">Chi tiết</a>
                                </td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 italic">Người dùng chưa mua đơn hàng nào.</p>
        @endif
    </div>

    {{-- Voucher đã sử dụng --}}
    <div>
        <h2 class="text-xl font-semibold mb-3">Voucher đã sử dụng</h2>
        @if($user->vouchers->count())
            <div class="overflow-auto rounded border">
                <table class="min-w-full text-sm text-left border-collapse">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-2 border">Mã voucher</th>
                            <th class="px-4 py-2 border">Mã đơn hàng</th>
                            <th class="px-4 py-2 border">Ngày dùng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->vouchers as $voucher)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-4 py-2">{{ $voucher->code }}</td>
                                <td class="border px-4 py-2">
                                    {{ $orderMap[$voucher->pivot->order_id]->code ?? 'N/A' }}
                                </td>
                               <td class="border px-4 py-2">
                                    {{ $voucher->pivot->used_at ? \Carbon\Carbon::parse($voucher->pivot->used_at)->format('d/m/Y H:i') : '---' }}
                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 italic">Người dùng chưa sử dụng voucher nào.</p>
        @endif
    </div>
</div>
@endsection
