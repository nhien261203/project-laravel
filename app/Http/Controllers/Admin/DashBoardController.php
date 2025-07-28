<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Repositories\Dashboard\DashboardRepositoryInterface;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

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
        
         // Lấy danh mục có sản phẩm đã từng được đặt hàng (có trong order_items)
        $cateForOrder = Category::whereHas('products.variants.orderItems')->get();

        return view('admin.dashboard', compact('statuses', 'categories', 'brands', 'cateForOrder'));
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
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'nullable|string|in:pending,processing,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ: ' . $validator->errors()->first()
            ], 422);
        }
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $status = $request->input('status');

        if (empty($start) || empty($end)) {
            $end = now()->endOfDay();
            $start = now()->subDays(6)->startOfDay();
        } else {
            $start = Carbon::parse($start)->startOfDay();
            $end = Carbon::parse($end)->endOfDay();
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
    public function getMonthlySummary(Request $request)
    {
        $year = $request->input('year', now()->year);
        $data = $this->dashboard->getMonthlyRevenueAndUsers($year);

        $months = collect(range(1, 12));
        $labels = $months->map(fn($m) => 'Tháng ' . $m);
        $revenues = $months->map(fn($m) => $data['revenues'][$m] ?? 0);
        $users = $months->map(fn($m) => $data['users'][$m] ?? 0);

        return response()->json([
            'revenues' => [
                'labels' => $labels,
                'values' => $revenues,
                'label' => 'Doanh thu theo tháng',
            ],
            'users' => [
                'labels' => $labels,
                'values' => $users,
                'label' => 'Người dùng mới theo tháng',
            ],
            'year' => $year,
        ]);
    }

    public function getTopSellingProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ: ' . $validator->errors()->first()
            ], 422);
        }

        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $categoryId = $request->input('category_id');
        $limit = $request->input('limit', 5);

        if (!$start || !$end) {
            $end = Carbon::now()->endOfDay();
            $start = Carbon::now()->subDays(6)->startOfDay(); // 7 ngày gần nhất
        } else {
            $start = Carbon::parse($start)->startOfDay();
            $end = Carbon::parse($end)->endOfDay();
        }

        $products = $this->dashboard->getTopSellingProducts($limit, $start, $end, $categoryId);

        return response()->json([
            'labels' => $products->pluck('name'),
            'values' => $products->pluck('total_sold'),
            'label' => 'Top 5 sản phẩm bán chạy',
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
        ]);
    }
    public function getMonthlyOrderSummary(Request $request)
    {
        $year = $request->input('year', now()->year);
        $status = $request->input('status'); // Có thể null

        $data = $this->dashboard->getMonthlyOrders($year, $status); // Gọi từ repository

        $months = collect(range(1, 12));
        $labels = $months->map(fn($m) => 'Tháng ' . $m);
        $values = $months->map(fn($m) => $data[$m] ?? 0);

        return response()->json([
            'labels' => $labels,
            'values' => $values,
            'label' => 'Đơn hàng theo tháng',
            'year' => $year,
        ]);
    }

}
