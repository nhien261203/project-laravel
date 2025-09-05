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
use Spatie\Permission\Models\Role;

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
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Bạn không có quyền truy cập dashboard');
        }
        $categories = Category::all();
        $brands = Brand::whereHas('products')->get();
        $statuses = $this->dashboard->getAvailableStatuses();


        // Lấy đầy đủ danh mục cha + con có đơn hàng
        $categoryIdsWithOrders = Category::whereHas('products.variants.orderItems')
            ->pluck('id')
            ->toArray();

        $parentIds = Category::whereIn('id', $categoryIdsWithOrders)
            ->pluck('parent_id')
            ->filter()
            ->unique()
            ->toArray();

        $allIds = array_unique(array_merge($categoryIdsWithOrders, $parentIds));

        $cateForOrder = Category::whereIn('id', $allIds)->get();

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
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'category_id' => 'nullable|exists:categories,id',
            'limit' => 'nullable|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ: ' . $validator->errors()->first()
            ], 422);
        }

        $limit = $request->input('limit', 5);
        $categoryId = $request->input('category_id');

        // Nếu có ngày thì dùng, không thì để null
        $start = $request->filled('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $end   = $request->filled('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;

        // Gọi hàm xử lý
        $products = $this->dashboard->getTopSellingProducts($limit, $start, $end, $categoryId);

        return response()->json([
            'labels' => $products->pluck('name'),
            'values' => $products->pluck('total_sold'),
            'label' => "Top {$limit} sản phẩm bán chạy",
            'categories' => $products->pluck('category_name'),
            'start_date' => $start?->toDateString(),
            'end_date' => $end?->toDateString(),
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

    public function monthlyTopProducts(Request $request)
    {
        $year = $request->input('year', now()->year);
        $limit = (int) $request->input('limit', 5);
        $categoryId = $request->input('category_id');

        $data = $this->dashboard->getMonthlyTopProducts($year, $limit, $categoryId);

        return response()->json($data);
    }

    // public function stockAlert(Request $request)
    // {
        
    //     $lowStockThreshold = $request->input('threshold', 5);
    //     $categoryId = $request->input('category_id'); // lọc danh mục
    //     $perPage = $request->input('per_page', 10);
    //     $sortOrder = $request->input('sort', 'asc');

    //     $lowStockProducts = $this->dashboard->getLowStockProducts($lowStockThreshold, $categoryId, $perPage,$sortOrder);

    //     return response()->json([
    //         'status'    => 'success',
    //         'threshold' => $lowStockThreshold,
    //         'data'      => $lowStockProducts
    //     ]);
    // }

    public function stockAll(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Bạn không có quyền truy cập dashboard');
        }
        $threshold  = $request->input('threshold', 5);
        $categoryId = $request->input('category_id');
        $perPage    = $request->input('per_page', 10);
        $sortOrder  = $request->input('sort', 'asc');

        $products   = $this->dashboard->getLowStockProducts(
            $threshold,
            $categoryId,
            $perPage,
            $sortOrder
        );

        $categories = Category::all();

        return view('admin.stock', compact(
            'products',
            'categories',
            'threshold',
            'categoryId',
            'sortOrder',
            'categories'
        ));
    
    }
}
