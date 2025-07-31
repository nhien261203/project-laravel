@extends('layout.user_profile')

@section('user_profile_content')
<div class="container pt-15 pb-10">
    
    @if ($orders->isEmpty())
        <div class="min-h-[60vh] flex flex-col items-center justify-center text-center space-y-6  p-6">
            <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" alt="Không có đơn hàng"
                class="w-40 h-40 mx-auto opacity-80">

            <div>
                <h2 class="text-xl font-semibold text-gray-800">Bạn chưa có đơn hàng nào</h2>
                <p class="text-sm text-gray-500">Bắt đầu hành trình mua sắm để đặt những sản phẩm yêu thích!</p>
            </div>

            <a href="{{ route('home') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                🛍️ <span>Tiếp tục mua sắm</span>
            </a>
        </div>
    @else
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-8">Đơn hàng của bạn</h2>

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
<div class="flex justify-center">
    {{ $orders->links('pagination.custom-user') }}
</div>

@endsection
