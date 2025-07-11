<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Models\Order;

class OrderController extends Controller
{
    protected $orderRepo;

    public function __construct(OrderRepositoryInterface $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->filled('q')) {
            $query->where('code', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }


    public function show($id)
    {
        $order = $this->orderRepo->find($id); // dùng repo
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'required|in:paid,unpaid',
        ]);

        $this->orderRepo->update($id, [
            'status' => $request->status,
            'payment_status' => $request->payment_status,
        ]);

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công');
    }


    public function destroy($id)
    {
        $this->orderRepo->delete($id); // dùng repo
        return redirect()->route('admin.orders.index')->with('success', 'Xóa đơn hàng thành công');
    }
}
