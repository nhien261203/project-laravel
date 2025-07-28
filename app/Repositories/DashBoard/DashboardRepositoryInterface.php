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
    

}
