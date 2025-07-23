@extends('layout.user')

@section('content')
<div class="container pt-20 pb-5 overflow-hidden">
    

    

    @if ($cart->items->isEmpty())
        <div class="bg-white flex-grow shadow-md rounded-lg p-6 text-center space-y-5 max-w-md mx-auto">
            <img src="https://happyphone.vn/template/assets/images/crt-empty.png" alt="Giỏ hàng trống" class="w-60 mx-auto opacity-80">
            
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-1">Giỏ hàng của bạn đang trống</h2>
                <p class="text-sm text-gray-500">Bạn chưa thêm sản phẩm nào. Khám phá ngay để lựa chọn món đồ yêu thích!</p>
            </div>

            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                🛍️ <span>Tiếp tục mua sắm</span>
            </a>
        </div>
    @else
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Giỏ hàng của bạn</h1>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            {{-- Cột trái: Danh sách sản phẩm (chiếm 2/3) --}}
            
            {{-- Cột phải: Tổng tiền & thanh toán --}}
            
           

            <div class="col-span-1 md:col-span-2 space-y-6">
                @foreach ($cart->items as $item)
                    <div class="flex flex-col md:flex-row bg-white shadow rounded p-4 gap-4">
                        {{-- Hình ảnh --}}
                        <div class="w-full md:w-28 h-28 flex-shrink-0 border rounded overflow-hidden">
                            <img src="{{ asset('storage/' . $item->snapshot_image) }}" alt="Ảnh sản phẩm" class="w-full h-full object-contain">
                        </div>

                        {{-- Thông tin --}}
                        <div class="flex-1 space-y-1">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $item->snapshot_product_name }}</h3>
                            {{-- Màu + Bộ nhớ --}}
                            <p class="text-sm text-gray-600">
                                @if($item->snapshot_color)
                                    Màu: <strong>{{ $item->snapshot_color }}</strong>
                                @endif

                                @if($item->snapshot_color && $item->snapshot_storage)
                                    |
                                @endif

                                @if($item->snapshot_storage)
                                    Bộ nhớ: <strong>{{ $item->snapshot_storage }}</strong>
                                @endif
                            </p>

                            {{-- Chip --}}
                            @if($item->snapshot_chip)
                                <p class="text-sm text-gray-600">Chip: {{ $item->snapshot_chip }}</p>
                            @endif


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
                                        <button type="submit" class="text-red-500 hover:underline text-sm">Xoá</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if(session('error_voucher'))
                    <p class="text-sm text-red-600">{{ session('error_voucher') }}</p>
                @endif

                @if(session('success_voucher'))
                    <p class="text-sm text-green-600">{{ session('success_voucher') }}</p>
                @endif

                <form method="POST" action="{{ route('cart.apply-voucher') }}" class="flex gap-2 mb-4">
                    @csrf
                    <input type="text" name="voucher_code" placeholder="Nhập mã giảm giá..."
                        class="flex-1 border rounded px-3 py-2 text-sm">
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                        Áp dụng
                    </button>
                </form>

                @if(isset($vouchers) && $vouchers->count())
                    <div class="pt-4 bg-white p-6 rounded shadow-md">
                        <h4 class="text-sm font-semibold text-gray-600">Mã giảm giá đang có:</h4>
                        <div class="flex space-x-4 overflow-x-auto pb-2">
                            @foreach ($vouchers as $voucher)
                                <div class="min-w-[250px] border p-3 rounded flex justify-between items-center hover:bg-gray-50 shrink-0">
                                    <div>
                                        <p class="font-semibold text-blue-700 text-sm">{{ $voucher->code }}</p>
                                        <p class="text-xs text-gray-600">
                                            {{ $voucher->type === 'percent' ? "Giảm {$voucher->value}%" : "Giảm ".number_format($voucher->value, 0, ',', '.')."₫" }}
                                            @if($voucher->min_order_amount)
                                                – Đơn từ {{ number_format($voucher->min_order_amount, 0, ',', '.') }}₫
                                            @endif
                                            @if($voucher->only_for_new_user)
                                                – <span class="text-green-600 font-medium">Khách mới</span>
                                            @endif
                                        </p>
                                        @if($voucher->end_date)
                                            <p class="text-xs text-gray-400">HSD: {{ $voucher->end_date->format('d/m/Y') }}</p>
                                        @endif
                                        @if($voucher->max_discount)
                                            <p class="text-xs text-gray-400">Giảm tối đa: {{ number_format($voucher->max_discount, 0, ',', '.') }}₫</p>
                                        @endif
                                        @if($voucher->max_usage_per_user)
                                            <p class="text-xs text-gray-400">Sử dụng tối đa: {{ $voucher->max_usage_per_user }} lần</p>
                                        @endif
                                    </div>
                                    <button onclick="copyToClipboard('{{ $voucher->code }}')"
                                            class=" text-blue-600 text-lg hover:scale-110 transition">
                                        📋
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        
                    </div>
                @endif

                 @php
                    $total = $cart->items->sum(fn($item) => $item->snapshot_price * $item->quantity);
                @endphp
                @php
                        $voucher = session('applied_voucher');
                        $discount = 0;

                        if ($voucher) {
                            $discount = $voucher['type'] === 'percent'
                                ? floor($total * $voucher['value'] / 100)
                                : $voucher['value'];

                            if (!empty($voucher['max_discount']) && $discount > $voucher['max_discount']) {
                                $discount = $voucher['max_discount'];
                            }

                            $totalAfterDiscount = $total - $discount;
                        }
                    @endphp

                    <div class="bg-gray-50 p-4 rounded border border-gray-200 space-y-2 text-sm">
                    @if($voucher)
                        <div class="flex justify-between">
                            <span class="text-gray-700 font-medium">Tạm tính:</span>
                            <span class="text-gray-900 font-bold">{{ number_format($total, 0, ',', '.') }}₫</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-green-700 font-medium">Mã giảm: {{ $voucher['code'] }}</span>
                            <span class="text-green-700 font-bold">-{{ number_format($discount, 0, ',', '.') }}₫</span>
                        </div>
                        <hr class="my-2 border-gray-300">
                        <div class="flex justify-between text-base">
                            <span class="text-gray-800 font-semibold">Tổng thanh toán:</span>
                            <span class="text-red-600 text-lg font-bold">{{ number_format($totalAfterDiscount, 0, ',', '.') }}₫</span>
                        </div>
                    @else
                        <div class="flex justify-between text-base">
                            <span class="text-gray-800 font-semibold">Tổng thanh toán:</span>
                            <span class="text-red-600 text-lg font-bold">{{ number_format($total, 0, ',', '.') }}₫</span>
                        </div>
                    @endif

                    @if(session('applied_voucher'))
                        <div class="mt-2 text-sm flex items-center justify-between">
                            <span class="text-green-700">
                                Đã áp dụng mã: <strong>{{ session('applied_voucher.code') }}</strong>
                            </span>
                            <form method="POST" action="{{ route('cart.remove-voucher') }}">
                                @csrf
                                <button type="submit" class="text-red-500 hover:underline text-xs">Bỏ mã</button>
                            </form>
                        </div>
                    @endif
                </div>

            </div>

            {{-- <button 
                class="block md:hidden w-full text-center bg-blue-600 text-white font-semibold py-3 rounded hover:bg-blue-700 transition">
                Xác nhận thanh toán
            </button> --}}

            

            <div class="col-span-1 md:col-span-2 space-y-4 md:sticky top-6 pb-3 ">
                {{-- <h3 class="text-lg font-semibold text-gray-700">Tạm tính</h3>
                <p class="text-2xl font-bold text-red-600">{{ number_format($total, 0, ',', '.') }}₫</p> --}}
                {{-- Nhập mã giảm giá --}}

                
                

                <div class="bg-white p-6 rounded shadow-md space-y-4 top-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Thông tin đặt hàng</h3>
                    <p class="text-gray-600 mb-4">Vui lòng điền thông tin bên dưới để hoàn tất đơn hàng.</p>


                    <form method="POST" action="{{ route('user.orders.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold mb-1">Họ tên</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                                class="w-full border rounded px-3 py-2" >
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-1">Số điện thoại</label>
                            <input type="text" name="customer_phone" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}"
                                class="w-full border rounded px-3 py-2" >
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-1">Email</label>
                            <input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                                class="w-full border rounded px-3 py-2">
                        </div>

                        {{-- <div>
                            <label class="block text-sm font-semibold mb-1">Địa chỉ nhận hàng</label>
                            <textarea name="customer_address" rows="2" class="w-full border rounded px-3 py-2" >{{ old('customer_address') }}</textarea>
                        </div> --}}

                        <div class="flex flex-col md:flex-row gap-4">
                            
                            <div>
                                <label class="block text-sm font-semibold mb-1">Tỉnh / Thành phố</label>
                                <select name="province_code" id="province" class="w-full border rounded px-3 py-2 text-sm" ></select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-1">Quận / Huyện</label>
                                <select name="district_code" id="district" class="w-full border rounded px-3 py-2 text-sm" ></select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-1">Phường / Xã</label>
                                <select name="ward_code" id="ward" class="w-full border rounded px-3 py-2 text-sm" ></select>
                            </div>

                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-1">Địa chỉ chi tiết (số nhà, tên đường...)</label>
                            <input type="text" name="address_detail" class="w-full border rounded px-3 py-2 text-sm" value="{{ old('address_detail') }}">
                        </div>

                        
                        <input type="hidden" name="province_name" id="province_name">
                        <input type="hidden" name="district_name" id="district_name">
                        <input type="hidden" name="ward_name" id="ward_name">

                        <div>
                            <label class="block text-sm font-semibold mb-1">Ghi chú (tuỳ chọn)</label>
                            <textarea name="note" rows="2" class="w-full border rounded px-3 py-2">{{ old('note') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Phương thức thanh toán</label>
                            <select name="payment_method" class="w-full border rounded px-3 py-2 text-sm" >
                                <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                                <option value="vnpay">Thanh toán qua VNPay</option>
                            </select>
                        </div>

                        <button type="submit"
                            class="w-full text-center bg-green-600 text-white font-semibold py-3 rounded hover:bg-green-700 transition">
                            Đặt hàng
                        </button>

                        
                            
                            
                            
                        
                    </form>
                    @if ($errors->any())
                        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                            <ul class="text-sm list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

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

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Đã sao chép mã: ' + text);
        }).catch(() => {
            alert('Không thể sao chép mã.');
        });
    }
</script>
<script>
    async function loadProvinces() {
        const res = await fetch('https://provinces.open-api.vn/api/p/');
        const provinces = await res.json();
        const provinceSelect = document.getElementById('province');
        provinceSelect.innerHTML = `<option value="">-- Chọn tỉnh --</option>`;
        provinces.forEach(p => {
            provinceSelect.innerHTML += `<option value="${p.code}" data-name="${p.name}">${p.name}</option>`;
        });
    }

    async function loadDistricts(provinceCode) {
        const res = await fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`);
        const data = await res.json();
        const districtSelect = document.getElementById('district');
        districtSelect.innerHTML = `<option value="">-- Chọn quận/huyện --</option>`;
        data.districts.forEach(d => {
            districtSelect.innerHTML += `<option value="${d.code}" data-name="${d.name}">${d.name}</option>`;
        });
        document.getElementById('ward').innerHTML = `<option value="">-- Chọn phường/xã --</option>`;
    }

    async function loadWards(districtCode) {
        const res = await fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`);
        const data = await res.json();
        const wardSelect = document.getElementById('ward');
        wardSelect.innerHTML = `<option value="">-- Chọn phường/xã --</option>`;
        data.wards.forEach(w => {
            wardSelect.innerHTML += `<option value="${w.code}" data-name="${w.name}">${w.name}</option>`;
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        loadProvinces();

        document.getElementById('province').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('province_name').value = selectedOption.getAttribute('data-name');
            loadDistricts(this.value);
        });

        document.getElementById('district').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('district_name').value = selectedOption.getAttribute('data-name');
            loadWards(this.value);
        });

        document.getElementById('ward').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('ward_name').value = selectedOption.getAttribute('data-name');
        });

    });
</script>



