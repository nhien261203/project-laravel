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
        $query = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as category, COUNT(*) as total')
            ->groupBy('categories.name');

        if ($brandId) {
            $query->where('products.brand_id', $brandId);
        }


        // dd($result);
        return $query->get();
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
            ->select(
                'products.id',
                'products.name',
                'products.slug',
                
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
            $query->where('products.category_id', $categoryId);
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

    
    // public function getLatestDateHavingData()
    // {
    //     $latestOrder = \App\Models\Order::orderByDesc('created_at')->value('created_at');
    //     $latestRevenue = \App\Models\Order::orderByDesc('created_at')->value('created_at'); // nếu doanh thu tính từ Order
    //     $latestUser = \App\Models\User::orderByDesc('created_at')->value('created_at');

    //     $dates = array_filter([$latestOrder, $latestRevenue, $latestUser]);

    //     if (empty($dates)) {
    //         return null;
    //     }

    //     return collect($dates)->sortDesc()->first(); // lấy ngày mới nhất trong 3 bảng
    // }


}
