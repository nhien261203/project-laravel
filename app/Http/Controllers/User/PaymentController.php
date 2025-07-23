<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function vnpayPayment(Request $request)
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('user.orders.index'); // ✅ URL hợp lệ
        $vnp_TmnCode = "YQVA6LHM"; // Mã website tại VNPAY
        $vnp_HashSecret = "NEHGRPOYU0ZMF3HCNH8QXCFUJIQ6IXUN"; // Chuỗi bí mật

        $vnp_TxnRef = '1111'; // Mã đơn hàng
        $vnp_OrderInfo = 'thanh toan don hang';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = 200000 * 100; // VND × 100
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $request->ip();

        $inputData = [
            "vnp_Version"    => "2.1.0",
            "vnp_TmnCode"    => $vnp_TmnCode,
            "vnp_Amount"     => $vnp_Amount,
            "vnp_Command"    => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode"   => "VND",
            "vnp_IpAddr"     => $vnp_IpAddr,
            "vnp_Locale"     => $vnp_Locale,
            "vnp_OrderInfo"  => $vnp_OrderInfo,
            "vnp_OrderType"  => $vnp_OrderType,
            "vnp_ReturnUrl"  => $vnp_Returnurl,
            "vnp_TxnRef"     => $vnp_TxnRef,
        ];

        if (!empty($vnp_BankCode)) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        // Bước 1: Sắp xếp key theo thứ tự alphabet
        ksort($inputData);

        // Bước 2: Tạo query string & hash data
        $query = [];
        foreach ($inputData as $key => $value) {
            $query[] = urlencode($key) . '=' . urlencode($value);
        }

        $hashdata = implode('&', $query);
        $vnp_SecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

        // Bước 3: Gắn secure hash vào URL
        $vnp_Url .= '?' . implode('&', $query) . '&vnp_SecureHash=' . $vnp_SecureHash;

        // Trả về URL cho frontend hoặc redirect luôn
        if ($request->has('redirect')) {
            return redirect($vnp_Url);
        }

        return response()->json([
            'code' => '00',
            'message' => 'success',
            'data' => $vnp_Url,
        ]);
    }
    
}
