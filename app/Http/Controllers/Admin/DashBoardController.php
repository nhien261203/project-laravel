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

        // Nếu chưa chọn ngày thì tự lấy ngày gần nhất có dữ liệu (order/user/revenue)
        if (!$start || !$end) {
            $latestDate = $this->dashboard->getLatestDateHavingData();

            if ($latestDate) {
                $start = \Carbon\Carbon::parse($latestDate)->startOfDay()->toDateString();
                $end = \Carbon\Carbon::parse($latestDate)->endOfDay()->toDateString();
            } else {
                // Không có dữ liệu
                return response()->json([
                    'orders' => [],
                    'revenues' => [],
                    'users' => [],
                ]);
            }
        }

        $orders = $this->dashboard->getOrderStatistics($start, $end, $status);
        $revenues = $this->dashboard->getRevenueStatistics($start, $end);
        $users = $this->dashboard->getUserStatistics($start, $end);

        return response()->json([
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
            'start_date' => $start,
            'end_date' => $end,
        ]);
    }
}
