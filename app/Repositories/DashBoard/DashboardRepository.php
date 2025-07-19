<?php

namespace App\Repositories\Dashboard;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Carbon;

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
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
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

    public function getRevenueByCategory(?string $startDate = null, ?string $endDate = null, ?int $categoryId = null)
    {
        
        $query = OrderItem::query()
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->selectRaw('categories.name as category_name, SUM(order_items.quantity * order_items.price) as total_revenue')
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('categories.id', 'categories.name');

        if ($startDate) {
            $query->whereDate('orders.created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('orders.created_at', '<=', $endDate);
        }

        if ($categoryId) {
            $query->where('categories.id', $categoryId);
        }

        return $query->get();
    }
}
