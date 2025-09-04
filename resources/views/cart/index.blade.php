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
    animation: scaleIn 0.8s ease;
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
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
        <div class="flex items-center text-sm text-gray-600 space-x-2 mb-4">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Trang chủ</a>
            <span class="text-gray-400">›</span>
            <span class="text-gray-800 font-medium">Giỏ hàng</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- Cột trái: Danh sách sản phẩm --}}
        <div class="md:col-span-1 space-y-6 ">
            @foreach ($cart->items as $item)
                @php
                    $product = $item->variant?->product;

                    // Xác định đường dẫn chi tiết dựa trên loại sản phẩm ( is_accessory trong model product)
                    $detailRoute = $product?->is_accessory
                        ? route('product.detailAccessory', ['slug' => $item->snapshot_product_slug])
                        : route('product.detail', ['slug' => $item->snapshot_product_slug]);
                @endphp

                <div class="flex flex-col md:flex-row bg-white shadow rounded p-4 gap-4">
                    <div class="w-full md:w-28 h-28 flex-shrink-0 border rounded overflow-hidden">
                        <a href="{{  $detailRoute }}"><img src="{{ asset('storage/' . $item->snapshot_image) }}" alt="Ảnh sản phẩm" class="w-full h-full object-contain"></a>
                    </div>

                    <div class="flex-1 space-y-1">
                        <a href="{{  $detailRoute }}"><h3 class="text-lg font-semibold text-gray-800">{{ $item->snapshot_product_name }}</h3></a>
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
                                <form method="POST" action="{{ route('cart.update', $item->product_variant_id) }}" 
                                    class="flex items-center border rounded-lg border-gray-300 mt-2 qty-form ">
                                    @csrf 
                                    @method('PUT')

                                    <!-- Nút trừ -->
                                    <button type="button" 
                                            class="qty-btn-minus text-sm text-gray-700 px-3 py-1 border-r border-gray-500">
                                        -
                                    </button>

                                    <!-- Ô số -->
                                    <input type="text" 
                                        name="quantity" 
                                        value="{{ $item->quantity }}" 
                                        class="w-8 text-center border-none qty-input focus:outline-none focus:ring-0" 
                                        readonly>

                                    <!-- Nút cộng -->
                                    <button type="button" 
                                            class="qty-btn-plus text-sm text-gray-700 px-3 py-1 border-l border-gray-500">
                                        +
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('cart.remove', $item->product_variant_id) }}">
                                    @csrf @method('DELETE')
                                    <button type="button" class="text-red-500 cursor-pointer text-xl btn-delete mt-3">&times;</button>
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

            <div class="flex justify-center">
                @auth
                    <button onclick="toggleCheckoutForm(true)"
                        class="w-1/3 text-center bg-blue-600 text-white font-semibold py-3 mt-3 rounded hover:bg-blue-700 transition">
                        Xác nhận đơn hàng
                    </button>
                @endauth

                @guest
                    <div class="text-center mt-3 text-red-600 font-semibold">
                        Vui lòng <a href="{{ route('login') }}" class="underline hover:text-red-700">đăng nhập</a> để mua hàng
                    </div>
                @endguest
            </div>

            <div id="mobileCheckoutForm" class="hidden">

                <div class="checkout-box bg-white shadow-xl rounded-lg p-6 w-full max-w-xl mt-10">
                    <div class="flex justify-between items-center mb-4">
                        <div class="text-lg font-semibold text-gray-700">Thông tin đặt hàng</div>
                        <button onclick="toggleCheckoutForm(false)"
                            class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-100 text-gray-600 text-xl hover:bg-gray-200 transition">
                            ×
                        </button>
                    </div>


                    
                    <p class="text-gray-600 mb-4">Vui lòng điền thông tin bên dưới để hoàn tất đơn hàng.</p>


                    <form method="POST" id="checkoutForm" action="{{ route('user.orders.store') }}" class="space-y-4">
                        @csrf

                        {{-- @if ($errors->any())
                            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                                <ul class="text-sm list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif --}}
                        <div>
                            <label class="block text-sm font-semibold mb-1">Họ tên</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                                class="w-full border rounded px-3 py-2" required>
                            @error('customer_name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-1">Số điện thoại</label>
                            <input type="text" name="customer_phone" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}"
                                class="w-full border rounded px-3 py-2" required>
                            @error('customer_phone')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-1">Email</label>
                            <input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                                class="w-full border rounded px-3 py-2" required>
                            @error('customer_email')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- <div>
                            <label class="block text-sm font-semibold mb-1">Địa chỉ nhận hàng</label>
                            <textarea name="customer_address" rows="2" class="w-full border rounded px-3 py-2" >{{ old('customer_address') }}</textarea>
                        </div> --}}

                        <div class="flex flex-col md:flex-row gap-4">
                            
                            <div>
                                <label class="block text-sm font-semibold mb-1">Tỉnh / Thành phố</label>
                                <select name="province_code" id="province" class="w-full border rounded px-3 py-2 text-sm" required></select>
                                @error('province_code')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror

                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-1">Quận / Huyện</label>
                                <select name="district_code" id="district" class="w-full border rounded px-3 py-2 text-sm" required></select>
                                @error('district_code')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror

                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-1">Phường / Xã</label>
                                <select name="ward_code" id="ward" class="w-full border rounded px-3 py-2 text-sm" required></select>
                                @error('ward_code')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-1">Địa chỉ chi tiết (số nhà, tên đường...)</label>
                            <input type="text" name="address_detail" class="w-full border rounded px-3 py-2 text-sm" value="{{ old('address_detail') }}" required>
                            @error('address_detail')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        
                        <input type="hidden" name="province_name" id="province_name">
                        <input type="hidden" name="district_name" id="district_name">
                        <input type="hidden" name="ward_name" id="ward_name">

                        <div>
                            <label class="block text-sm font-semibold mb-1">Ghi chú (tuỳ chọn)</label>
                            <textarea name="note" rows="2" class="w-full border rounded px-3 py-2">{{ old('note') }}</textarea>
                            @error('note')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        Swal.fire({
            icon: 'success',
            title: 'Đã sao chép mã thành công!',
            toast: true,
            position: 'top-end',
            timer: 2000,
            showConfirmButton: false
            
        });
    }).catch(() => {
        Swal.fire({
            icon: 'error',
            title: 'Không thể sao chép mã',
            text: 'Vui lòng thử lại!',
            toast: false,
            position: 'top-end',
            timer: 2500,
            showConfirmButton: false
        });
    });
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const oldProvince = "{{ old('province_code') }}";
    const oldDistrict = "{{ old('district_code') }}";
    const oldWard = "{{ old('ward_code') }}";
    let hasRestoredOld = false;

    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');

    loadProvinces().then(() => {
        if (oldProvince) {
            provinceSelect.value = oldProvince;
            provinceSelect.dispatchEvent(new Event('change'));
        }
    });

    provinceSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('province_name').value = selectedOption.getAttribute('data-name') || '';

        loadDistricts(this.value).then(() => {
            if (oldDistrict && !hasRestoredOld) {
                districtSelect.value = oldDistrict;
                districtSelect.dispatchEvent(new Event('change'));
            }
        });
    });

    districtSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('district_name').value = selectedOption.getAttribute('data-name') || '';

        loadWards(this.value).then(() => {
            if (oldWard && !hasRestoredOld) {
                wardSelect.value = oldWard;
                wardSelect.dispatchEvent(new Event('change'));
                hasRestoredOld = true;
            }
        });
    });

    wardSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('ward_name').value = selectedOption.getAttribute('data-name') || '';
    });
});

async function loadProvinces() {
    const provinceSelect = document.getElementById('province');
    provinceSelect.innerHTML = '';

    const loadingOption = new Option('Đang tải danh sách tỉnh...', '');
    loadingOption.disabled = true;
    loadingOption.selected = true;
    provinceSelect.appendChild(loadingOption);

    try {
        const res = await fetch('https://provinces.open-api.vn/api/p/');
        const provinces = await res.json();

        provinceSelect.innerHTML = '';
        provinceSelect.appendChild(new Option('-- Chọn tỉnh --', ''));

        provinces.forEach(p => {
            const option = new Option(p.name, p.code);
            option.setAttribute('data-name', p.name);
            provinceSelect.appendChild(option);
        });
    } catch (error) {
        provinceSelect.innerHTML = '';
        const errorOption = new Option('Lỗi khi tải tỉnh', '');
        errorOption.disabled = true;
        errorOption.selected = true;
        provinceSelect.appendChild(errorOption);
        console.error('Lỗi loadProvinces:', error);
    }
}

async function loadDistricts(provinceCode) {
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');

    districtSelect.innerHTML = '';
    const loadingOption = new Option('Đang tải quận/huyện...', '');
    loadingOption.disabled = true;
    loadingOption.selected = true;
    districtSelect.appendChild(loadingOption);

    wardSelect.innerHTML = '';
    wardSelect.appendChild(new Option('-- Chọn phường/xã --', ''));

    try {
        const res = await fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`);
        const data = await res.json();

        districtSelect.innerHTML = '';
        districtSelect.appendChild(new Option('-- Chọn quận/huyện --', ''));

        data.districts.forEach(d => {
            const option = new Option(d.name, d.code);
            option.setAttribute('data-name', d.name);
            districtSelect.appendChild(option);
        });
    } catch (error) {
        districtSelect.innerHTML = '';
        const errorOption = new Option('Lỗi khi tải quận/huyện', '');
        errorOption.disabled = true;
        errorOption.selected = true;
        districtSelect.appendChild(errorOption);
        console.error('Lỗi loadDistricts:', error);
    }
}

async function loadWards(districtCode) {
    const wardSelect = document.getElementById('ward');

    wardSelect.innerHTML = '';
    const loadingOption = new Option('Đang tải phường/xã...', '');
    loadingOption.disabled = true;
    loadingOption.selected = true;
    wardSelect.appendChild(loadingOption);

    try {
        const res = await fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`);
        const data = await res.json();

        wardSelect.innerHTML = '';
        wardSelect.appendChild(new Option('-- Chọn phường/xã --', ''));

        data.wards.forEach(w => {
            const option = new Option(w.name, w.code);
            option.setAttribute('data-name', w.name);
            wardSelect.appendChild(option);
        });
    } catch (error) {
        wardSelect.innerHTML = '';
        const errorOption = new Option('Lỗi khi tải phường/xã', '');
        errorOption.disabled = true;
        errorOption.selected = true;
        wardSelect.appendChild(errorOption);
        console.error('Lỗi loadWards:', error);
    }
}
</script>

<script>
function toggleCheckoutForm(show) {
    const form = document.getElementById('mobileCheckoutForm');
    if (show) {
        form.classList.add('mobile-active');
        document.body.style.overflow = 'hidden';
    } else {
        form.classList.remove('mobile-active');
        document.body.style.overflow = '';
    }
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    @if ($errors->any())
        toggleCheckoutForm(true);
    @endif
});
</script>


<script>
// Gửi đơn hàng bằng fetch
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('checkoutForm');
    if (!form) return;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        // Xoá lỗi cũ
        document.querySelectorAll('.error-msg').forEach(el => el.remove());

        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerText;

        submitBtn.disabled = true;
        // submitBtn.innerText = 'Đang xử lý...';

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData
            });

            const data = await res.json();

            if (res.status === 422) {
                showValidationErrors(data.errors);
            } else if (res.ok) {
                window.location.href = data.redirect_url + '?success=1';
            } else {
                alert(data.message || 'Đặt hàng thất bại.');
            }
        } catch (err) {
            alert('Đặt hàng thất bại. Vui lòng thử lại.');
            console.error(err);
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerText = originalText;
        }
    });

    function showValidationErrors(errors) {
        for (const key in errors) {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                const errorText = document.createElement('p');
                errorText.className = 'text-sm text-red-600 mt-1 error-msg';
                errorText.textContent = errors[key][0];
                input.insertAdjacentElement('afterend', errorText);
            }
        }
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('.qty-form');

    forms.forEach(form => {
        const input = form.querySelector('.qty-input');
        const btnMinus = form.querySelector('.qty-btn-minus');
        const btnPlus = form.querySelector('.qty-btn-plus');

        // Hàm cập nhật trạng thái nút trừ
        function updateMinusButton() {
            btnMinus.disabled = (parseInt(input.value) <= 1);
            btnMinus.classList.toggle('opacity-50', parseInt(input.value) <= 1); // làm mờ khi disabled
        }

        // Khởi tạo lần đầu
        updateMinusButton();

        btnMinus.addEventListener('click', () => {
            let qty = parseInt(input.value) || 1;
            if (qty > 1) {
                qty--;
                input.value = qty;
                form.submit();
                updateMinusButton();
            }
        });

        btnPlus.addEventListener('click', () => {
            let qty = parseInt(input.value) || 1;
            qty++;
            input.value = qty;
            form.submit();
            updateMinusButton();
        });
    });
});

</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const qtyInputs = document.querySelectorAll('.auto-update-qty');

    qtyInputs.forEach(input => {
        input.addEventListener('change', function () {
            const form = this.closest('form');
            if(form) form.submit();
        });
    });
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function () {
                const form = this.closest('form');

                Swal.fire({
                    title: 'Xác nhận xoá',
                    text: "Bạn có chắc muốn xoá sản phẩm này khỏi giỏ hàng?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: ' Xoá',
                    cancelButtonText: 'Huỷ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.requestSubmit();
                    }
                });
            });
        });
    });
</script>

