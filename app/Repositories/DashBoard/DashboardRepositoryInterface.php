<?php

namespace App\Repositories\Dashboard;

interface DashboardRepositoryInterface
{
    public function getOrderStatistics($startDate, $endDate, $status = null);
    public function getRevenueStatistics($startDate, $endDate);
    public function getUserStatistics($startDate, $endDate);
    // public function getLatestDateHavingData();
    public function getAvailableStatuses();
    public function getProductCountByCategory($brandId = null);
    public function getMonthlyRevenueAndUsers($year = null);
    public function getTopSellingProducts($limit = 5, $start = null, $end = null, $categoryId = null);
    public function getMonthlyTopProducts($year = null, $limit = 5, $categoryId = null);

    public function getMonthlyOrders($year, $status = null);
    public function getLowStockProducts(
        int $threshold = 5,
        ?int $categoryId = null,
        int $perPage = 10,
        string $sortOrder = 'asc'
    );
}
