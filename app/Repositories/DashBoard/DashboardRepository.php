<?php

namespace App\Repositories\Dashboard;

use App\Models\Order;
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
    public function getLatestDateHavingData()
    {
        $latestOrder = \App\Models\Order::orderByDesc('created_at')->value('created_at');
        $latestRevenue = \App\Models\Order::orderByDesc('created_at')->value('created_at'); // nếu doanh thu tính từ Order
        $latestUser = \App\Models\User::orderByDesc('created_at')->value('created_at');

        $dates = array_filter([$latestOrder, $latestRevenue, $latestUser]);

        if (empty($dates)) {
            return null;
        }

        return collect($dates)->sortDesc()->first(); // lấy ngày mới nhất trong 3 bảng
    }
    

}
