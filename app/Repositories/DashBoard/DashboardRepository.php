<?php

namespace App\Repositories\Dashboard;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getOrderStatistics($startDate, $endDate, $status = null)
    {
        $query = Order::whereBetween('created_at', [$startDate, $endDate]);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }


    public function getRevenueStatistics($startDate, $endDate)
    {
        return Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COALESCE(SUM(total_amount), 0) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function getUserStatistics($startDate, $endDate)
    {
        return User::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
    public function getAvailableStatuses()
    {
        return DB::table('orders')
            ->select('status')
            ->distinct()
            ->pluck('status');
    }
    public function getProductCountByCategory($brandId = null)
    {
        $categories = Category::with('parent')
            ->whereHas('products', function ($q) use ($brandId) {
                if ($brandId) {
                    $q->where('brand_id', $brandId);
                }
            })
            ->withCount(['products as product_count' => function ($q) use ($brandId) {
                if ($brandId) {
                    $q->where('brand_id', $brandId);
                }
            }])
            ->get();

        $result = $categories->map(function ($cat) {
            $parentName = $cat->parent ? $cat->parent->name : null;
            $label = $parentName ? "{$parentName} - {$cat->name}" : $cat->name;

            return [
                'category' => $label,
                'total' => $cat->product_count,
            ];
        });

        return $result->sortByDesc('total')->values();
    }


    // DashboardRepository.php
    public function getMonthlyRevenueAndUsers($year = null)
    {
        $year = $year ?? now()->year;

        // Doanh thu mỗi tháng
        $revenues = DB::table('orders')
            ->selectRaw('MONTH(created_at) as month, COALESCE(SUM(total_amount), 0) as total')
            ->whereYear('created_at', $year)
            ->where('status', 'completed')
            ->groupBy('month')
            ->pluck('total', 'month');

        // Người dùng đăng ký mới mỗi tháng
        $users = DB::table('users')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('total', 'month');

        return [
            'revenues' => $revenues,
            'users' => $users,
        ];
    }

    public function getTopSellingProducts($limit = 5, $start = null, $end = null, $categoryId = null)
    {
        $query = DB::table('order_items')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products.id',
                'products.name',
                'products.slug',
                'categories.name as category_name',
                DB::raw('SUM(order_items.quantity) as total_sold')
            )
            // ->whereNull('orders.deleted_at') // nếu có soft delete
            ->where('orders.status', 'completed')
            ->groupBy('products.id', 'products.name', 'products.slug')
            ->orderByDesc('total_sold');

        // Lọc theo thời gian
        if ($start && $end) {
            $query->whereBetween('orders.created_at', [$start, $end]);
        }

        // Lọc theo danh mục
        if ($categoryId) {
            // Tìm cả danh mục con của categoryId
            $categoryIds = Category::where('id', $categoryId)
                ->orWhere('parent_id', $categoryId)
                ->pluck('id')
                ->toArray();

            $query->whereIn('products.category_id', $categoryIds);
        }


        // Nếu có limit, mới giới hạn
        if ($limit !== null) {
            $query->take($limit);
        }

        return $query->take($limit)->get();
    }

    public function getMonthlyOrders($year, $status = null)
    {
        $query = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $year);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();
    }


    public function getMonthlyTopProducts($year = null, $limit = 5, $categoryId = null)
    {
        $year = $year ?? now()->year;

        //Lấy top sản phẩm theo tổng số bán trong năm
        $topProducts = DB::table('order_items')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('products.id', 'products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->where('orders.status', 'completed')
            ->whereYear('orders.created_at', $year)
            ->when($categoryId, function ($query, $categoryId) {
                $query->where('products.category_id', $categoryId);
            })
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->take($limit)
            ->get();

        $datasets = [];

        foreach ($topProducts as $product) {
            //  Tính số lượng bán theo từng tháng
            $monthlyData = array_fill(1, 12, 0); // từ tháng 1 đến 12

            $monthlySales = DB::table('order_items')
                ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
                ->join('products', 'product_variants.product_id', '=', 'products.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.status', 'completed')
                ->whereYear('orders.created_at', $year)
                ->where('products.id', $product->id)
                ->when($categoryId, function ($query, $categoryId) {
                    $query->where('products.category_id', $categoryId);
                })
                ->select(DB::raw('MONTH(orders.created_at) as month'), DB::raw('SUM(order_items.quantity) as total'))
                ->groupBy(DB::raw('MONTH(orders.created_at)'))
                ->pluck('total', 'month');

            foreach ($monthlySales as $month => $total) {
                $monthlyData[(int) $month] = (int) $total;
            }

            $datasets[] = [
                'label' => $product->name,
                'data' => array_values($monthlyData), // giữ đúng thứ tự từ 1 -> 12
                'backgroundColor' => $this->getRandomColor()
            ];
        }

        return [
            'labels' => ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
            'datasets' => $datasets
        ];
    }

    protected function getRandomColor()
    {
        $colors = ['#F87171', '#60A5FA', '#34D399', '#FBBF24', '#A78BFA', '#F472B6', '#2DD4BF', '#FCD34D', '#818CF8', '#4ADE80'];
        return $colors[array_rand($colors)];
    }
}
