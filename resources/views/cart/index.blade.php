@extends('layout.user')

@section('content')
<div class="container py-10">
    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-8">🛒 Giỏ hàng của bạn</h2>

    @if ($cart->items->isEmpty())
        <div class="bg-white shadow rounded p-8 text-center">
            <p class="text-gray-600 text-lg mb-4">Chưa có sản phẩm nào trong giỏ hàng.</p>
            <a href="{{ route('home') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                🛍️ Tiếp tục mua sắm
            </a>
        </div>
    @else
        <div class="grid md:grid-cols-3 gap-8">
            {{-- Cột trái: Danh sách sản phẩm (chiếm 2/3) --}}
            <div class="md:col-span-2 space-y-6">
                @foreach ($cart->items as $item)
                    <div class="flex flex-col md:flex-row bg-white shadow rounded p-4 gap-4">
                        {{-- Hình ảnh --}}
                        <div class="w-full md:w-28 h-28 flex-shrink-0 border rounded overflow-hidden">
                            <img src="{{ asset('storage/' . $item->snapshot_image) }}" alt="Ảnh sản phẩm" class="w-full h-full object-cover">
                        </div>

                        {{-- Thông tin --}}
                        <div class="flex-1 space-y-1">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $item->snapshot_product_name }}</h3>
                            <p class="text-sm text-gray-600">
                                Màu: <strong>{{ $item->snapshot_color }}</strong> |
                                Bộ nhớ: <strong>{{ $item->snapshot_storage }}</strong>
                            </p>
                            <p class="text-sm text-gray-600">Chip: {{ $item->snapshot_chip }}</p>

                            {{-- Giá & số lượng --}}
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mt-3">
                                <div>
                                    <span class="text-lg font-bold text-red-600">
                                        {{ number_format($item->snapshot_price, 0, ',', '.') }}₫
                                    </span>
                                    @if($item->snapshot_original_price > $item->snapshot_price)
                                        <span class="text-sm text-gray-400 line-through ml-2">
                                            {{ number_format($item->snapshot_original_price, 0, ',', '.') }}₫
                                        </span>
                                    @endif
                                </div>

                                {{-- Số lượng + Xoá --}}
                                <div class="flex items-center gap-3 mt-2 sm:mt-0">
                                    {{-- Cập nhật --}}
                                    <form method="POST" action="{{ route('cart.update', $item->product_variant_id) }}" class="flex items-center gap-2">
                                        @csrf @method('PUT')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                            class="w-16 border rounded px-2 py-1 text-center text-sm">
                                        <button type="submit" class="text-sm text-blue-600 hover:underline">Cập nhật</button>
                                    </form>

                                    {{-- Xoá --}}
                                    <form method="POST" action="{{ route('cart.remove', $item->product_variant_id) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline text-sm">❌ Xoá</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Cột phải: Tổng tiền & thanh toán --}}
            <div>
                @php
                    $total = $cart->items->sum(fn($item) => $item->snapshot_price * $item->quantity);
                @endphp

                <div class="bg-white p-6 rounded shadow-md space-y-4 sticky top-6">
                    <h3 class="text-lg font-semibold text-gray-700">Tạm tính</h3>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($total, 0, ',', '.') }}₫</p>

                    <a 
                       class="block w-full text-center bg-green-600 text-white font-semibold py-3 rounded hover:bg-green-700 transition">
                        Đặt hàng
                    </a>

                    <a href="{{ route('home') }}"
                       class="block w-full text-center border border-gray-300 text-gray-700 py-3 rounded hover:bg-gray-100 transition">
                        Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
