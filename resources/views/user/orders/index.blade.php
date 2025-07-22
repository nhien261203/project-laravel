@extends('layout.user_profile')

@section('user_profile_content')
<div class="container pt-15 pb-10">
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
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition">
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700">Mã đơn hàng: #{{ $order->code }}</h3>

                            <p class="text-sm text-gray-500">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <a href="{{ route('user.orders.show', $order->id) }}" class="px-3 py-1 border border-red-500 text-red-500 rounded-md hover:bg-red-50 transition">Xem</a>
                    </div>

                    {{-- Thông tin đơn --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm text-gray-700">
                        <div>
                            <p class="font-medium text-gray-600">Trạng thái đơn:</p>
                            <p class="text-gray-800">{{ $order->status }}</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-600">Thanh toán:</p>
                            <p class="{{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                            </p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-600">Tổng tiền:</p>
                            <p class="text-red-600 font-semibold">{{ number_format($order->total_amount, 0, ',', '.') }}₫</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
