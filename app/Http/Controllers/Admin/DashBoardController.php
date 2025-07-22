<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Repositories\Dashboard\DashboardRepositoryInterface;
use Carbon\CarbonPeriod;
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
        $categories = Category::all();
        $brands = Brand::whereHas('products')->get();
        $statuses = $this->dashboard->getAvailableStatuses();
        return view('admin.dashboard', compact('statuses', 'categories', 'brands'));
    }


    // API trả dữ liệu biểu đồ tròn
    public function getCategoryPieChart(Request $request)
    {
        $data = $this->dashboard->getProductCountByCategory($request->brand_id);

        $labels = $data->pluck('category')->toArray(); // field đúng
        $values = $data->pluck('total')->toArray();

        return response()->json([
            'labels' => $labels,
            'values' => $values,
            'label' => 'Số lượng sản phẩm theo danh mục'
        ]);
    }

    // API trả dữ liệu cho biểu đồ
    public function getStatistics(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $status = $request->input('status');

        if (!$start || !$end) {
            $end = now()->toDateString();
            $start = now()->subDays(6)->toDateString();
        }

        // Danh sách ngày đầy đủ
        $period = CarbonPeriod::create($start, $end);
        $allDates = collect($period)->map(fn($date) => $date->toDateString());

        // Truy vấn dữ liệu gốc
        $orders = $this->dashboard->getOrderStatistics($start, $end, $status)->keyBy('date');
        $revenues = $this->dashboard->getRevenueStatistics($start, $end)->keyBy('date');
        $users = $this->dashboard->getUserStatistics($start, $end)->keyBy('date');

        // Map lại đầy đủ ngày
        $mappedOrders = $allDates->map(fn($date) => $orders[$date]->total ?? 0);
        $mappedRevenues = $allDates->map(fn($date) => $revenues[$date]->total ?? 0);
        $mappedUsers = $allDates->map(fn($date) => $users[$date]->total ?? 0);

        return response()->json([
            'startDateDefault' => $start,
            'endDateDefault' => $end,
            'orders' => [
                'labels' => $allDates,
                'values' => $mappedOrders,
                'label' => 'Số lượng đơn hàng'
            ],
            'revenues' => [
                'labels' => $allDates,
                'values' => $mappedRevenues,
                'label' => 'Doanh thu'
            ],
            'users' => [
                'labels' => $allDates,
                'values' => $mappedUsers,
                'label' => 'Người dùng mới'
            ],
            'start_date' => $start,
            'end_date' => $end,
        ]);
    }
}
