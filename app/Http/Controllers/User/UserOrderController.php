<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Exception;

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
        ]);

        try {
            $order = $this->orderRepo->createOrderFromCart(Auth::id(), $data);

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
}
