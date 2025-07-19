<?php

namespace App\Repositories\Dashboard;

interface DashboardRepositoryInterface
{
    public function getOrderStatistics($startDate, $endDate, $status = null);
    public function getRevenueStatistics($startDate, $endDate);
    public function getUserStatistics($startDate, $endDate);
    // public function getLatestDateHavingData();
    public function getRevenueByCategory(?string $startDate = null, ?string $endDate = null, ?int $categoryId = null);

}
