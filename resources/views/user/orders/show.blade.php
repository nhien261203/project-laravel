@extends('layout.user_profile')

@section('user_profile_content')
<div class="container pt-15 pb-10">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">📦 Chi tiết đơn hàng</h2>

    <div class="bg-white shadow-md rounded-xl p-6 space-y-6">
        {{-- thông tin đơn hàng --}}
        <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-700">
            <div>
                <p><strong>Mã đơn hàng:</strong> <span class="text-blue-600 font-medium">#{{ $order->code }}</span></p>
                <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Trạng thái:</strong> <span class="font-semibold text-blue-600">{{ ucfirst($order->status) }}</span></p>
            </div>
            <div>
                <p><strong>Thanh toán:</strong>
                    <span class="{{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600' }} font-semibold">
                        {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                    </span>
                </p>
                <p><strong>Địa chỉ giao hàng:</strong></p>
                <p>{{ $order->address_detail }}, {{ $order->ward_name }}, {{ $order->district_name }}, {{ $order->province_name }}</p>
            </div>
        </div>

        <div class="border-t pt-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">🛒 Danh sách sản phẩm</h3>

            <div class="space-y-4">
                @foreach ($order->items as $item)
                    <div class="flex items-start gap-4 border border-gray-100 p-4 rounded-lg">
                        <div class="w-24 h-24 flex-shrink-0 border rounded overflow-hidden">
                            <img src="{{ asset('storage/' . $item->image) }}"
                                class="w-full h-full object-contain"
                                alt="{{ $item->product_name }}">
                        </div>

                        <div class="flex-1 space-y-1">
                            <h4 class="font-semibold text-gray-800">
                                {{ $item->product_name }}
                            </h4>
                            <p class="text-sm text-gray-600">
                                @if ($item->color)
                                    Màu: {{ $item->color }}
                                @endif
                                @if ($item->storage)
                                    | Bộ nhớ: {{ $item->storage }}
                                @endif
                            </p>
                            <p class="text-sm text-gray-600">
                                Số lượng: {{ $item->quantity }}
                            </p>

                            <p class="text-sm text-gray-600">
                                Giá: <strong class="text-red-600">{{ number_format($item->price, 0, ',', '.') }}₫</strong>
                            </p>

                            {{-- <p class="text-sm text-gray-500">
                                Thành tiền: <strong>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫</strong>
                            </p> --}}
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        {{-- <div class="border-t pt-4 text-right">
            <p class="text-xl font-bold text-gray-800">
                Tổng đơn: {{ number_format($order->total_amount, 0, ',', '.') }}₫
            </p>
        </div> --}}
        <div class="border-t pt-4 text-right space-y-1 text-sm text-gray-700">
            @if ($order->voucher_code)
                <p class="text-sm text-gray-600">
                    Mã giảm giá: <span class="font-semibold text-blue-600">{{ $order->voucher_code }}</span>
                </p>
                <p class="text-sm text-gray-600">
                    Giảm giá: <span class="text-red-500 font-semibold">-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span>
                </p>
            @endif

            @if ($order->subtotal && $order->subtotal != $order->total_amount)
                <p class="text-sm text-gray-600">
                    Tạm tính: <span class="font-medium">{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
                </p>
            @endif

            <p class="text-xl font-bold text-gray-800">
                Tổng đơn: {{ number_format($order->total_amount, 0, ',', '.') }}₫
            </p>

            {{-- Hủy đơn --}}
            @if ($order->status === 'pending' && $order->payment_status === 'unpaid')
                <form method="POST" action="{{ route('user.orders.cancel', $order->id) }}" class="mt-4 text-right">
                    @csrf
                    <button type="submit"
                        onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')"
                        class="inline-block px-4 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition">
                        Hủy đơn hàng
                    </button>
                </form>
            @endif
        </div>

    </div>
</div>
@endsection
