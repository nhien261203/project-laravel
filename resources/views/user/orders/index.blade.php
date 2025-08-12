@extends('layout.user_profile')

@section('user_profile_content')
<div class="container pt-3 pb-10 max-w-5xl mx-auto">

    @if ($orders->isEmpty())
        <div class="min-h-[60vh] flex flex-col items-center justify-center text-center space-y-6 p-6">
            <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" alt="Không có đơn hàng"
                class="w-32 h-32 mx-auto opacity-70">

            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Bạn chưa có đơn hàng nào</h2>
                <p class="text-base text-gray-500 mt-1">Bắt đầu hành trình mua sắm để đặt những sản phẩm yêu thích!</p>
            </div>

            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-md">
                🛍️ <span class="text-lg font-medium">Tiếp tục mua sắm</span>
            </a>
        </div>
    @else
        <h2 class="text-xl font-bold text-gray-900 mb-8">Đơn hàng của bạn</h2>

        {{-- Filter trạng thái dạng card --}}
        @php
            $statuses = [
                '' => 'Tất cả',
                'pending' => 'Chờ xử lý',
                'shipping' => 'Đang vận chuyển',
                'completed' => 'Hoàn thành',
                'cancelled' => 'Đã hủy',
            ];

            $currentStatus = request('status', '');

            $counts = [
                '' => $ordersAll->count(),
                'pending' => $ordersAll->where('status', 'pending')->count(),
                'shipping' => $ordersAll->where('status', 'shipping')->count(),
                'completed' => $ordersAll->where('status', 'completed')->count(),
                'cancelled' => $ordersAll->where('status', 'cancelled')->count(),
            ];
        @endphp

        <div id="statusFilter" class="flex flex-wrap gap-3 mb-8">
            @foreach ($statuses as $key => $label)
                @php
                    $count = $counts[$key] ?? 0;
                    $isDisabled = $count === 0;
                    $isActive = $currentStatus === $key;
                @endphp

                <div
                    data-status="{{ $key }}"
                    class="relative cursor-pointer select-none rounded-md border px-4 py-2
                        text-sm font-medium transition
                        {{ $isActive ? 'bg-blue-600 border-blue-600 text-white shadow' : 'bg-white border-gray-300 hover:bg-gray-50' }}
                        {{ $isDisabled ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}"
                    style="min-width: 110px;"
                    title="{{ $isDisabled ? 'Không có đơn hàng trạng thái này' : '' }}"
                >
                    {{-- Count badge ở góc trên phải --}}
                    <span class="absolute top-[-5px] right-[-5px] bg-white text-xs font-semibold text-blue-600 rounded-full w-5 h-5 flex items-center justify-center shadow-sm
                        {{ $isActive ? 'bg-white text-blue-600' : '' }}">
                        {{ $count }}
                    </span>

                    <span>{{ $label }}</span>
                </div>
            @endforeach
        </div>

        {{-- Danh sách đơn hàng --}}
        <div class="space-y-6">
            @foreach ($orders as $order)
                <div
                    class="bg-white rounded-xl shadow border border-gray-200 hover:shadow-lg transition p-6"
                    title="Click xem chi tiết đơn #{{ $order->code }}"
                >
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Mã đơn hàng: <span class="text-blue-600">#{{ $order->code }}</span></h3>
                            <p class="text-sm text-gray-500">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <a href="{{ route('user.orders.show', $order->id) }}" 
                           class="px-4 py-1.5 border border-red-500 text-red-500 rounded-md hover:bg-red-50 transition font-semibold text-sm">
                           Xem
                        </a>
                    </div>

                    {{-- Thông tin đơn --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm text-gray-700">
                        <div>
                            <p class="font-medium text-gray-600 mb-1">Trạng thái đơn:</p>
                            <p class="text-gray-900 capitalize">{{ $statuses[$order->status] ?? $order->status }}</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-600 mb-1">Thanh toán:</p>
                            <p class="{{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600' }} font-semibold">
                                {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                            </p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-600 mb-1">Tổng tiền:</p>
                            <p class="text-red-600 font-bold text-lg">{{ number_format($order->total_amount, 0, ',', '.') }}₫</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="flex justify-center">
    {{ $orders->appends(request()->except('page'))->links('pagination.custom-user') }}
</div>

{{-- JS để lọc khi click --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterContainer = document.getElementById('statusFilter');
    if (!filterContainer) return;

    filterContainer.querySelectorAll('div[data-status]').forEach(card => {
        card.addEventListener('click', () => {
            if(card.classList.contains('cursor-not-allowed')) return; // disable click nếu ko có đơn
            const status = card.getAttribute('data-status');
            const url = new URL(window.location.href);
            if (status) {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }
            url.searchParams.delete('page'); // reset trang khi đổi filter
            window.location.href = url.toString();
        });
    });
});
</script>

@endsection
