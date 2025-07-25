@extends('layout.user')

@section('content')


<style>

#mobileCheckoutForm.mobile-active {
    position: fixed !important;
    inset: 0;
    z-index: 50;
    background-color: rgba(0, 0, 0, 0.9); /* nền đen mờ */
    display: flex !important;
    align-items: center; /* căn giữa chiều cao */
    justify-content: center;
    padding: 2rem 1rem;
    transition: opacity 0.2s ease, transform 0.2s ease;
}

#mobileCheckoutForm .checkout-box {
    background: white;
    width: 100%;
    max-width: 600px;
    max-height: 92vh; /* Giới hạn chiều cao */
    overflow-y: auto;  /* Cho scroll nếu vượt quá */
    padding: 1.5rem;
    border-radius: 0.75rem;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
    margin-top: 0 !important;
}
</style>


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

    

    <div class="max-w-5xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Giỏ hàng của bạn</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- Cột trái: Danh sách sản phẩm --}}
        <div class="md:col-span-1 space-y-6 ">
            @foreach ($cart->items as $item)
                <div class="flex flex-col md:flex-row bg-white shadow rounded p-4 gap-4">
                    <div class="w-full md:w-28 h-28 flex-shrink-0 border rounded overflow-hidden">
                        <img src="{{ asset('storage/' . $item->snapshot_image) }}" alt="Ảnh sản phẩm" class="w-full h-full object-contain">
                    </div>

                    <div class="flex-1 space-y-1">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $item->snapshot_product_name }}</h3>
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
                        @if($item->snapshot_chip)
                            <p class="text-sm text-gray-600">Chip: {{ $item->snapshot_chip }}</p>
                        @endif

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
                            <div class="flex items-center gap-3 mt-2 sm:mt-0">
                                <form method="POST" action="{{ route('cart.update', $item->product_variant_id) }}" class="flex items-center gap-2">
                                    @csrf @method('PUT')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                        class="w-16 border rounded px-2 py-1 text-center text-sm">
                                    <button type="submit" class="text-sm text-blue-600 hover:underline">Cập nhật</button>
                                </form>

                                <form method="POST" action="{{ route('cart.remove', $item->product_variant_id) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline text-sm">Xoá</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Cột phải: Mã giảm giá + Tổng tiền + Nút xác nhận --}}
        <div class="md:col-span-1">
            @if(session('error_voucher'))
                <p class="text-sm text-red-600">{{ session('error_voucher') }}</p>
            @endif
            @if(session('success_voucher'))
                <p class="text-sm text-green-600">{{ session('success_voucher') }}</p>
            @endif

            <form method="POST" action="{{ route('cart.apply-voucher') }}"  class="flex gap-2">
                @csrf
                <input type="text" name="voucher_code" placeholder="Nhập mã giảm giá..."
                    class="flex-1 border rounded px-3 py-2 text-sm">
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                    Áp dụng
                </button>
            </form>

            @if(isset($vouchers) && $vouchers->count())
                <div class="bg-white p-4 rounded shadow space-y-3">
                    <h4 class="text-sm font-semibold text-gray-600">Mã giảm giá đang có:</h4>
                    <div class="flex space-x-4 overflow-x-auto pb-2">
                        @foreach ($vouchers as $voucher)
                            <div class="min-w-[250px] border p-3 rounded flex justify-between items-center hover:bg-gray-50 shrink-0">
                                <div class="text-sm space-y-1">
                                    <p class="font-semibold text-blue-700">{{ $voucher->code }}</p>
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
                                </div>
                                <button onclick="copyToClipboard('{{ $voucher->code }}')"
                                        class="text-blue-600 text-lg hover:scale-110 transition">
                                    📋
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif


            @php
                $total = $cart->items->sum(fn($item) => $item->snapshot_price * $item->quantity);
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

            <div class="bg-gray-50 p-4 rounded border border-gray-200 space-y-2 text-sm mt-3">
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

            <button onclick="toggleCheckoutForm(true)"
                class="w-full text-center bg-blue-600 text-white font-semibold py-3 mt-3 rounded hover:bg-blue-700 transition">
                Xác nhận thanh toán
            </button>
            
            <div id="mobileCheckoutForm"
                class="hidden">



                <div class="checkout-box bg-white shadow-xl rounded-lg p-6 w-full max-w-xl mt-10">
                    <div class="flex justify-between items-center mb-4">
                        <div class="text-lg font-semibold text-gray-700">Thông tin đặt hàng</div>
                        <button onclick="toggleCheckoutForm(false)"
                            class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-100 text-gray-600 text-xl hover:bg-gray-200 transition">
                            ×
                        </button>
                    </div>


                    
                    <p class="text-gray-600 mb-4">Vui lòng điền thông tin bên dưới để hoàn tất đơn hàng.</p>


                    <form method="POST" action="{{ route('user.orders.store') }}" class="space-y-4">
                        @csrf

                        @if ($errors->any())
                            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                                <ul class="text-sm list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
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

                        {{-- <div>
                            <label class="block text-sm font-semibold mb-1">Email</label>
                            <input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                                class="w-full border rounded px-3 py-2">
                        </div> --}}

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
                    

                    <a href="{{ route('home') }}"
                        class="block w-full text-center border border-gray-300 text-gray-700 py-3 rounded hover:bg-gray-100 transition">
                        Tiếp tục mua sắm
                    </a>
                </div>
            </div>
            



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
    document.addEventListener('DOMContentLoaded', function () {
        const oldProvince = "{{ old('province_code') }}";
        const oldDistrict = "{{ old('district_code') }}";
        const oldWard = "{{ old('ward_code') }}";
        let hasRestoredOld = false;

        loadProvinces().then(() => {
            if (oldProvince) {
                const provinceSelect = document.getElementById('province');
                provinceSelect.value = oldProvince;
                provinceSelect.dispatchEvent(new Event('change'));
            }
        });

        document.getElementById('province').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('province_name').value = selectedOption.getAttribute('data-name');

            loadDistricts(this.value).then(() => {
                if (oldDistrict && !hasRestoredOld) {
                    const districtSelect = document.getElementById('district');
                    districtSelect.value = oldDistrict;
                    districtSelect.dispatchEvent(new Event('change'));
                }
            });
        });

        document.getElementById('district').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('district_name').value = selectedOption.getAttribute('data-name');

            loadWards(this.value).then(() => {
                if (oldWard && !hasRestoredOld) {
                    const wardSelect = document.getElementById('ward');
                    wardSelect.value = oldWard;
                    wardSelect.dispatchEvent(new Event('change'));
                    hasRestoredOld = true;
                }
            });
        });

        document.getElementById('ward').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('ward_name').value = selectedOption.getAttribute('data-name');
        });
    });


    async function loadProvinces() {
        const provinceSelect = document.getElementById('province');

        // Hiển thị loading trước khi dữ liệu được load về
        provinceSelect.innerHTML = `<option value="">Đang tải danh sách tỉnh...</option>`;

        try {
            const res = await fetch('https://provinces.open-api.vn/api/p/');
            const provinces = await res.json();

            provinceSelect.innerHTML = `<option value="">-- Chọn tỉnh --</option>`;
            provinces.forEach(p => {
                provinceSelect.innerHTML += `<option value="${p.code}" data-name="${p.name}">${p.name}</option>`;
            });
        } catch (error) {
            provinceSelect.innerHTML = `<option value="">Lỗi khi tải tỉnh</option>`;
            console.error('Lỗi tải tỉnh:', error);
        }
    }

    async function loadDistricts(provinceCode) {
        const districtSelect = document.getElementById('district');
        const wardSelect = document.getElementById('ward');

        // Hiển thị loading
        districtSelect.innerHTML = `<option value="">Đang tải quận/huyện...</option>`;
        wardSelect.innerHTML = `<option value="">-- Chọn phường/xã --</option>`; // reset

        try {
            const res = await fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`);
            const data = await res.json();

            districtSelect.innerHTML = `<option value="">-- Chọn quận/huyện --</option>`;
            data.districts.forEach(d => {
                districtSelect.innerHTML += `<option value="${d.code}" data-name="${d.name}">${d.name}</option>`;
            });
        } catch (error) {
            districtSelect.innerHTML = `<option value="">Lỗi khi tải quận/huyện</option>`;
            console.error('Lỗi loadDistricts:', error);
        }
    }


    async function loadWards(districtCode) {
        const wardSelect = document.getElementById('ward');

        // Hiển thị loading
        wardSelect.innerHTML = `<option value="">Đang tải phường/xã...</option>`;

        try {
            const res = await fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`);
            const data = await res.json();

            wardSelect.innerHTML = `<option value="">-- Chọn phường/xã --</option>`;
            data.wards.forEach(w => {
                wardSelect.innerHTML += `<option value="${w.code}" data-name="${w.name}">${w.name}</option>`;
            });
        } catch (error) {
            wardSelect.innerHTML = `<option value="">Lỗi khi tải phường/xã</option>`;
            console.error('Lỗi loadWards:', error);
        }
    }

</script>

<script>
    function toggleCheckoutForm(show) {
        const form = document.getElementById('mobileCheckoutForm');
        if (show) {
            form.classList.add('mobile-active');
            document.body.style.overflow = 'hidden'; // Ngăn cuộn nền
        } else {
            form.classList.remove('mobile-active');
            document.body.style.overflow = '';
        }
    }

    // Nếu có lỗi từ server, mở lại form sau reload
    @if ($errors->any())
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                toggleCheckoutForm(true);
                document.getElementById('mobileCheckoutForm')?.scrollIntoView({ behavior: 'smooth' });
            }, 50);

        });
    @endif

</script>






