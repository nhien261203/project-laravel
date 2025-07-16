@extends('layout.user_profile')

@section('user_profile_content')
<div class="container pt-20 pb-10">
    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-8">Đơn hàng của bạn</h2>

    @if ($orders->isEmpty())
        <div class="bg-white p-6 rounded shadow text-center">
            <p class="text-gray-600 text-lg mb-4">Bạn chưa có đơn hàng nào.</p>
            <a href="{{ route('home') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                🛍️ Tiếp tục mua sắm
            </a>
        </div>
    @else
        <div class="space-y-6">
            @foreach ($orders as $order)
                <div class="bg-white rounded shadow p-6">
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700">Mã đơn hàng: #{{ $order->code }}</h3>

                            <p class="text-sm text-gray-500">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <a href="{{ route('user.orders.show', $order->id) }}" class="text-blue-600 hover:underline text-sm">Xem chi tiết →</a>
                    </div>

                    <div class="text-sm text-gray-600">
                        <p>Trạng thái đơn hàng: <strong>{{  $order->status }}</strong></p>
                        <p>Thanh toán: <strong>{{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}</strong></p>
                        <p>Tổng tiền: <span class="text-red-600 font-semibold">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span></p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
