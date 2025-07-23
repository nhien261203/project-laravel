<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Str;

class UserOrderController extends Controller
{
    protected $orderRepo;

    public function __construct(OrderRepositoryInterface $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    /**
     * Danh sách đơn hàng của người dùng
     */
    public function index()
    {
        $orders = Auth::user()->orders()->with('items')->latest()->get();

        return view('user.orders.index', compact('orders'));
    }

    /**
     * Trang chi tiết đơn hàng
     */
    public function show($id)
    {
        $order = $this->orderRepo->find($id);

        // Kiểm tra quyền truy cập
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.orders.show', compact('order'));
    }

    /**
     * Đặt hàng từ giỏ hàng
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Bạn cần đăng nhập để đặt hàng.');
        }

        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email',
            // 'customer_address' => 'required|string|max:500',
            'note' => 'nullable|string|max:1000',
            'province_code'     => 'required|string|max:10',
            'province_name'     => 'required|string|max:100',
            'district_code'     => 'required|string|max:10',
            'district_name'     => 'required|string|max:100',
            'ward_code'         => 'required|string|max:10',
            'ward_name'         => 'required|string|max:100',
            'address_detail'    => 'required|string|max:255', // số nhà, tên đường
            'payment_method' => 'required|string|in:cod,vnpay'
        ]);

        try {
            $order = $this->orderRepo->createOrderFromCart(Auth::id(), $data);

            if ($data['payment_method'] === 'vnpay') {
                return redirect()->route('user.orders.vnpay', ['id' => $order->id, 'redirect' => true]);
            }

            return redirect()->route('user.orders.show', $order->id)
                ->with('success', 'Đặt hàng thành công!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Đặt hàng thất bại: ' . $e->getMessage());
        }
    }

    public function cancel($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'pending' || $order->payment_status !== 'unpaid') {
            return redirect()->back()->with('error', 'Chỉ có thể hủy đơn đang chờ xử lý và chưa thanh toán.');
        }

        $order->status = 'cancelled';
        $order->save();

        return redirect()->back()->with('success', 'Đơn hàng đã được hủy.');
    }
    public function vnpayPayment(Request $request, $id)
    {
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Chỉ thanh toán nếu đơn đang chờ thanh toán
        if ($order->payment_status !== 'unpaid') {
            return back()->with('error', 'Đơn hàng đã được thanh toán hoặc không hợp lệ.');
        }

        // Config VNPAY
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('user.orders.vnpay.return');

        $vnp_TmnCode = "YQVA6LHM";
        $vnp_HashSecret = "NEHGRPOYU0ZMF3HCNH8QXCFUJIQ6IXUN";

        // Dữ liệu thanh toán
        $vnp_TxnRef = $order->id . '-' . time(); // hoặc mã ngẫu nhiên duy nhất
        $vnp_OrderInfo = 'Thanh toán đơn hàng #' . $order->id;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $order->total_amount * 100; // Nhân 100 theo yêu cầu VNPAY
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
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

        ksort($inputData);
        $query = [];
        foreach ($inputData as $key => $value) {
            $query[] = urlencode($key) . '=' . urlencode($value);
        }

        $hashdata = implode('&', $query);
        $vnp_SecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= '?' . implode('&', $query) . '&vnp_SecureHash=' . $vnp_SecureHash;

        // Redirect đến VNPAY
        if ($request->has('redirect')) {
            return redirect($vnp_Url);
        }

        return response()->json([
            'code' => '00',
            'message' => 'success',
            'data' => $vnp_Url,
        ]);
    }
    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = "NEHGRPOYU0ZMF3HCNH8QXCFUJIQ6IXUN";

        $inputData = [];
        foreach ($request->all() as $key => $value) {
            if (Str::startsWith($key, 'vnp_')) {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? null;
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);

        ksort($inputData);
        $hashData = implode('&', array_map(function ($key) use ($inputData) {
            return urlencode($key) . '=' . urlencode($inputData[$key]);
        }, array_keys($inputData)));

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash !== $vnp_SecureHash) {
            return redirect()->route('user.orders.index')->with('error', 'Xác thực thanh toán thất bại.');
        }

        // Tách order_id từ vnp_TxnRef
        $txnRef = $inputData['vnp_TxnRef'] ?? '';
        $orderId = explode('-', $txnRef)[0] ?? null;

        if (!$orderId) {
            return redirect()->route('user.orders.index')->with('error', 'Không tìm thấy đơn hàng.');
        }

        $order = Order::find($orderId);

        if (!$order || $order->user_id !== Auth::id()) {
            return redirect()->route('user.orders.index')->with('error', 'Bạn không có quyền xem đơn hàng này.');
        }

        // Xử lý trạng thái thanh toán
        $vnp_ResponseCode = $inputData['vnp_ResponseCode'] ?? '';
        $vnp_TransactionStatus = $inputData['vnp_TransactionStatus'] ?? '';

        if ($vnp_ResponseCode === '00' && $vnp_TransactionStatus === '00') {
            $order->payment_status = 'paid';
            $order->save();

            return redirect()->route('user.orders.show', $order->id)
                ->with('success', 'Thanh toán thành công đơn hàng ');
        } else {
            return redirect()->route('user.orders.show', $order->id)
                ->with('error', 'Thanh toán không thành công.');
        }
    }
}
