@extends('layout.admin')

@section('title', 'Thống kê Dashboard')

@section('content')
<div class="p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-semibold mb-6">Thống kê</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Đơn hàng --}}
        <div class="bg-gray-50 p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-4">Đơn hàng</h2>
            <div class="flex flex-wrap items-center gap-2 mb-4">
            <input type="date" id="orders_start" class="border rounded px-2 py-1 w-full md:w-auto">
            <input type="date" id="orders_end" class="border rounded px-2 py-1 w-full md:w-auto">

            <select id="orders_status" class="border rounded px-2 py-1 w-full md:w-auto">
                <option value="">Tất cả trạng thái</option>
                <option value="pending">Chờ xử lý</option>
                <option value="processing">Đang xử lý</option>
                <option value="completed">Hoàn tất</option>
                <option value="cancelled">Đã hủy</option>
            </select>

            <button onclick="loadChart('orders')" class="bg-blue-600 text-white px-4 py-1 rounded">Lọc</button>
        </div>

            <canvas id="orderChart" height="200"></canvas>
        </div>

        {{-- Doanh thu --}}
        <div class="bg-gray-50 p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-4">Doanh thu</h2>
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <input type="date" id="revenues_start" class="border rounded px-2 py-1 w-full md:w-auto">
                <input type="date" id="revenues_end" class="border rounded px-2 py-1 w-full md:w-auto">
                <button onclick="loadChart('revenues')" class="bg-green-600 text-white px-4 py-1 rounded">Lọc</button>
            </div>
            <canvas id="revenueChart" height="200"></canvas>
        </div>

        {{-- Người dùng --}}
        <div class="bg-gray-50 p-4 rounded shadow md:col-span-2">
            <h2 class="text-lg font-semibold mb-4">Người dùng mới</h2>
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <input type="date" id="users_start" class="border rounded px-2 py-1 w-full md:w-auto">
                <input type="date" id="users_end" class="border rounded px-2 py-1 w-full md:w-auto">
                <button onclick="loadChart('users')" class="bg-purple-600 text-white px-4 py-1 rounded">Lọc</button>
            </div>
            <canvas id="userChart" height="200"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let orderChart, revenueChart, userChart;

    const chartRefs = {
        orders: { id: 'orderChart', chart: () => orderChart, set: c => orderChart = c },
        revenues: { id: 'revenueChart', chart: () => revenueChart, set: c => revenueChart = c },
        users: { id: 'userChart', chart: () => userChart, set: c => userChart = c },
    };

    function renderChart(chartType, labels, values, labelText) {
        const ctx = document.getElementById(chartRefs[chartType].id).getContext('2d');

        const config = {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: labelText,
                    data: values,
                    borderColor: chartType === 'revenues' ? 'green' : chartType === 'users' ? 'purple' : 'blue',
                    backgroundColor: 'rgba(0, 0, 0, 0.05)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        };

        const oldChart = chartRefs[chartType].chart();
        if (oldChart) oldChart.destroy();

        const newChart = new Chart(ctx, config);
        chartRefs[chartType].set(newChart);
    }

    function fetchChartData(chartType, startDate, endDate) {
        let url = `/admin/dashboard/statistics?start_date=${startDate}&end_date=${endDate}`;

        // Nếu là orders thì lấy thêm status
        if (chartType === 'orders') {
            const status = document.getElementById('orders_status').value;
            if (status) url += `&status=${status}`;
        }

        return fetch(url)
            .then(res => res.json())
            .then(data => {
                const info = data[chartType];
                renderChart(chartType, info.labels, info.values, info.label);
            });
    }

    function loadChart(chartType) {
        const start = document.getElementById(`${chartType}_start`).value;
        const end = document.getElementById(`${chartType}_end`).value;
        fetchChartData(chartType, start, end);
    }

    function setDefaultDates(idPrefix, start, end) {
        const today = new Date();
        const sevenDaysAgo = new Date();
        sevenDaysAgo.setDate(today.getDate() - 7);

        document.getElementById(`${idPrefix}_start`).value = start ?? sevenDaysAgo.toISOString().slice(0, 10);
        document.getElementById(`${idPrefix}_end`).value = end ?? today.toISOString().slice(0, 10);
    }

    document.addEventListener('DOMContentLoaded', function () {
        setDefaultDates('orders');
        setDefaultDates('revenues');
        setDefaultDates('users');

        loadChart('orders');
        loadChart('revenues');
        loadChart('users');
    });
</script>
@endsection
