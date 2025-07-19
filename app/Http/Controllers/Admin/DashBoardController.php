<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Dashboard\DashboardRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashBoardController extends Controller
{
    protected $dashboard;

    public function __construct(DashboardRepositoryInterface $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    // Trang dashboard
    public function index()
    {
        
        return view('admin.dashboard');
    }

    // API trả dữ liệu cho biểu đồ
    public function getStatistics(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $status = $request->input('status');
        
        

        // Nếu chưa chọn ngày thì tự lấy 7 ngay (order/user/revenue)
        if (!$start || !$end) {
            $end = now()->toDateString(); // hôm nay
            $start = now()->subDays(6)->toDateString(); // 7 ngày
        }

        $orders = $this->dashboard->getOrderStatistics($start, $end, $status);
        $revenues = $this->dashboard->getRevenueStatistics($start, $end);
        $users = $this->dashboard->getUserStatistics($start, $end);
        $revenueByCategory = $this->dashboard->getRevenueByCategory($start, $end, $request->input('category_id'));

        return response()->json([
            'startDateDefault' => $start,
            'endDateDefault' => $end,
            'orders' => [
                'labels' => $orders->pluck('date'),
                'values' => $orders->pluck('total'),
                'label' => 'Số lượng đơn hàng'
            ],
            'revenues' => [
                'labels' => $revenues->pluck('date'),
                'values' => $revenues->pluck('total'),
                'label' => 'Doanh thu'
            ],
            'users' => [
                'labels' => $users->pluck('date'),
                'values' => $users->pluck('total'),
                'label' => 'Người dùng mới'
            ],
            'revenueByCategory' => [
                'labels' => $revenueByCategory->pluck('category_name'),
                'values' => $revenueByCategory->pluck('total'),
                'label' => 'Doanh thu theo danh mục'
            ],
            'start_date' => $start,
            'end_date' => $end,
        ]);
    }

    public function revenueByCategory(Request $request)
    {
        $data = $this->dashboard->getRevenueByCategory(
            $request->start_date,
            $request->end_date,
            $request->category_id
        );

        return response()->json($data);
    }

}
