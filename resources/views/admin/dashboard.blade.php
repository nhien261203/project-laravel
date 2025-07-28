@extends('layout.admin')

@section('title', 'Thống kê Dashboard')

@section('content')
<!-- Global Loading Overlay -->
<div id="global-loading" class="fixed inset-0 bg-white bg-opacity-50 z-[9999] flex items-center justify-center hidden">
    <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-500 border-t-transparent"></div>
</div>

<div class="p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-semibold mb-6">Thống kê</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 ">
        {{-- Đơn hàng --}}
        <div class="bg-gray-50 p-4 rounded shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Đơn hàng</h2>
                <button id="toggleOrderMode"
                    class="text-sm text-blue-600 hover:text-blue-800 hover:underline font-medium transition">
                    <span id="toggleOrderText">Đơn hàng theo mỗi tháng</span>
                </button>
            </div>
            
            <div id="orderDayWrapper">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <input type="date" id="orders_start" class="border rounded px-2 py-1 w-full md:w-auto">
                    <input type="date" id="orders_end" class="border rounded px-2 py-1 w-full md:w-auto">
                    <select id="orders_status" class="border rounded px-2 py-1 w-full md:w-auto">
                        <option value="">Tất cả trạng thái</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button onclick="loadChart('orders')" class="bg-blue-600 text-white px-4 py-1 rounded">Lọc</button>
                </div>

                <canvas id="orderChart" height="200"></canvas>
                <p class="mt-2 text-sm text-gray-600">Tổng đơn hàng: <span id="totalOrders" class="font-semibold">0</span></p>
            </div>

            {{-- Biểu đồ đơn hàng theo tháng --}}
            <div id="orderMonthWrapper" class="hidden mt-4">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <select id="order_month_status" class="border rounded px-2 py-1 w-full md:w-auto">
                        <option value="">Tất cả trạng thái</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button onclick="loadMonthlyOrderChart()" class="bg-blue-500 text-white px-4 py-1 rounded">Lọc</button>
                </div>
                <canvas id="monthlyOrderChart" height="200"></canvas>
                <p class="mt-2 text-sm text-gray-600">
                    Tổng đơn hàng năm: <span id="totalOrdersYear" class="font-semibold">0</span>
                </p>
            </div>

        </div>


        {{-- Biểu đồ tròn sản phẩm theo danh mục --}}
        <div class="bg-gray-50 p-4 rounded shadow ">
            <h2 class="text-lg font-semibold mb-4">Sản phẩm theo danh mục</h2>

                <div class="mb-4 flex gap-2 items-end">
                    <div>

                        <select id="brandFilter" class="w-64 p-2 border rounded">
                            <option value="">-- Tất cả thương hiệu --</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button id="filterPieBtn" class="h-[38px] px-4 py-1 rounded bg-indigo-600 text-white">Lọc</button>
                </div>

            <div class="max-w-[350px] mx-auto">
                <canvas id="categoryPieChart"></canvas>
            </div>

            <p class="mt-2 text-sm text-gray-600">Tổng sản phẩm: <span id="totalProducts" class="font-semibold">0</span></p>

            
        </div>

    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        {{-- Doanh thu --}}
        <div class="bg-gray-50 p-4 rounded shadow relative">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Doanh thu</h2>
                <button id="toggleRevenueMode"
                    class="flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800 hover:underline font-medium transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 19l-7-7 7-7" />
                    </svg>
                    <span id="toggleRevenueText">Doanh thu theo mỗi tháng</span>
                </button>

            </div>

            {{-- Biểu đồ doanh thu theo ngày --}}
            <div id="revenueDayWrapper">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <input type="date" id="revenues_start"  class="border rounded px-2 py-1 w-full md:w-auto">
                    <input type="date" id="revenues_end" class="border rounded px-2 py-1 w-full md:w-auto">
                    <button onclick="loadChart('revenues')" class="bg-green-600 text-white px-4 py-1 rounded">Lọc</button>
                </div>
                <canvas id="revenueChart" height="200"></canvas>
                <p class="mt-2 text-sm text-gray-600">Tổng doanh thu: 
                    <span id="totalRevenues" class="font-semibold">0 ₫</span>
                </p>
            </div>

            {{-- Biểu đồ doanh thu theo 12 tháng (ẩn mặc định) --}}
            <div id="revenueYearWrapper" class="hidden">
                <canvas id="monthlyRevenueChart" height="200"></canvas>
                <p class="mt-2 text-sm text-gray-600">Tổng doanh cả năm: 
                    <span id="totalRevenueYear" class="font-semibold">0 ₫</span>
                </p>
            </div>

        </div>

        <div class="bg-gray-50 p-4 rounded shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Người dùng mới</h2>
                <button id="toggleUserMode"
                    class="text-sm text-purple-600 hover:text-purple-800 hover:underline font-medium transition">
                    <span id="toggleUserText">Người dùng theo mỗi tháng</span>
                </button>
            </div>

            {{-- Biểu đồ theo ngày --}}
            <div id="userDayWrapper">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <input type="date"  id="users_start"  class="border rounded px-2 py-1 w-full md:w-auto">
                    <input type="date" id="users_end"  class="border rounded px-2 py-1 w-full md:w-auto">
                    <button onclick="loadChart('users')" class="bg-purple-600 text-white px-4 py-1 rounded">Lọc</button>
                </div>
                <canvas id="userChart" height="200"></canvas>
                <p class="mt-2 text-sm text-gray-600">
                    Tổng người dùng mới: <span id="totalUsers" class="font-semibold">0</span>
                </p>
            </div>

            {{-- Biểu đồ theo năm (12 tháng) --}}
            <div id="userYearWrapper" class="hidden">
                <canvas id="monthlyUserChart" height="200"></canvas>
                <p class="mt-2 text-sm text-gray-600">
                    Tổng người dùng mới năm: <span id="totalUsersYear" class="font-semibold">0</span>
                </p>
            </div>

            
        </div>
        {{-- Biểu đồ top sản phẩm bán chạy --}}
        <div class="bg-gray-50 p-4 rounded shadow col-span-1 md:col-span-2">
            <h2 class="text-lg font-semibold mb-4">Top sản phẩm bán chạy</h2>

            <div class="flex flex-wrap items-center gap-2 mb-4">
                <input type="date" id="top_start" class="border rounded px-2 py-1 w-full md:w-auto">
                <input type="date" id="top_end" class="border rounded px-2 py-1 w-full md:w-auto">
                <select id="top_category" class="border rounded px-2 py-1 w-full md:w-auto">
                    <option value="">Tất cả danh mục</option>
                    @foreach($cateForOrder as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>

                <input type="number" id="top_limit" min="1" max="15" value="5" class="border rounded px-2 py-1 w-full md:w-auto" placeholder="Số lượng">

                <button onclick="loadTopProductsChart()" class="bg-red-600 text-white px-4 py-1 rounded">Lọc</button>
            </div>


            <canvas id="topProductsChart" height="200"></canvas>
        </div>

    </div>

</div>

<style>
    #revenueDayWrapper, #revenueYearWrapper {
        transition: opacity 0.3s ease;
    };
    @keyframes spin {
    to {
        transform: rotate(360deg);
    }
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }

</style>

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

        // Lấy màu tương ứng
        const color = chartType === 'revenues' ? 'green' : chartType === 'users' ? 'purple' : 'blue';

        const config = {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: labelText,
                    data: values,
                    borderColor: color,
                    backgroundColor: getGradient(ctx, color), // màu gradient
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                animation: {
                    duration: 500, 
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        };

        // Huỷ chart cũ
        const oldChart = chartRefs[chartType].chart();
        if (oldChart) oldChart.destroy();

        // Vẽ mới
        const newChart = new Chart(ctx, config);
        chartRefs[chartType].set(newChart);
    }


    function fetchChartData(chartType, startDate, endDate, oldStart = '', oldEnd = '') {
        const chartDiv = document.getElementById(chartRefs[chartType].id).parentElement;

        showGlobalLoading();

        let url = `/admin/dashboard/statistics?start_date=${startDate}&end_date=${endDate}`;
        if (chartType === 'orders') {
            const status = document.getElementById('orders_status').value;
            if (status) url += `&status=${status}`;
        }

        return fetch(url)
            .then(res => res.json())
            .then(data => {

                if (data.success === false) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Ngày không hợp lệ',
                        text: 'Ngày bắt đầu không được lớn hơn ngày kết thúc!',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000
                    });

                    // gan lai ngay hop le ban dau
                    // if (oldStart) startInput.value = oldStart;
                    // if (oldEnd) endInput.value = oldEnd;
                    return;
                }

                const info = data[chartType];
                const total = info.values.reduce((sum, val) => sum + Number(val), 0);

                if (chartType === 'orders') {
                    document.getElementById('totalOrders').textContent = total;
                } else if (chartType === 'revenues') {
                    document.getElementById('totalRevenues').textContent = total.toLocaleString('vi-VN') + ' ₫';
                } else if (chartType === 'users') {
                    document.getElementById('totalUsers').textContent = total;
                }
                
                renderChart(chartType, info.labels, info.values, info.label);

                const startInput = document.getElementById(`${chartType}_start`);
                const endInput = document.getElementById(`${chartType}_end`);

                // Gán giá trị nếu input chưa có
                if (!startInput.value && data.start_date) {
                    startInput.value = data.start_date.substring(0, 10); // lấy YYYY-MM-DD
                }
                if (!endInput.value && data.end_date) {
                    endInput.value = data.end_date.substring(0, 10);
                }

            })


            // .catch(err => {
            //     Swal.fire({
            //     icon: 'error',
            //     title: 'Lỗi',
            //     text: err.message || 'Đã xảy ra lỗi!',
            //     timer: 2000,
            //     toast: true,
            //     position: 'top-end',
            //     showConfirmButton: false
            //     });

            //     // Gán lại ngày cũ
            //     startInput.value = oldStart;
            //     endInput.value = oldEnd;
            // })
            
            .finally(() => {
                // document.getElementById('global-loading').classList.add('hidden');
                hideGlobalLoading();
                chartDiv.classList.remove('opacity-50', 'pointer-events-none');


            });

    }



    function loadChart(chartType) {
        // const startInput = document.getElementById(`${chartType}_start`);
        // const endInput = document.getElementById(`${chartType}_end`);  

        const start = document.getElementById(`${chartType}_start`).value;
        const end = document.getElementById(`${chartType}_end`).value;

        
        fetchChartData(chartType, start, end);
    }

    document.addEventListener('DOMContentLoaded',  function () {
        loadChart('orders');
        loadChart('revenues');
        loadChart('users');
    });

</script>
<script>
    let pieChartInstance;

    function loadCategoryPieChart(brandId = '') {
        showGlobalLoading();
        fetch(`/admin/dashboard/category-pie?brand_id=${brandId}`)
            .then(res => res.json())
            .then(data => {
                const ctx = document.getElementById('categoryPieChart').getContext('2d');

                // Nếu đã có biểu đồ thì hủy trước khi vẽ lại

                if (pieChartInstance) pieChartInstance.destroy();
                hideGlobalLoading();
                
                const total = data.values.reduce((sum, val) => sum + Number(val), 0);
                document.getElementById('totalProducts').textContent = total;


                pieChartInstance = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: data.label,
                            data: data.values,
                            backgroundColor: [
                                '#60A5FA', '#34D399', '#FBBF24', '#F87171',
                                '#A78BFA', '#F472B6', '#2DD4BF', '#FCD34D',
                                '#818CF8', '#4ADE80'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        animation: {
                            duration: 500, // thời gian hiệu ứng khi vẽ lại
                            easing: 'easeOutQuart' // hiệu ứng mượt
                        },
                        plugins: {
                            legend: { position: 'bottom' },
                            title: {
                                display: true,
                                text: data.label
                            }
                        }
                    }
                });
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Load mặc định tất cả danh mục
        loadCategoryPieChart();

        // Bắt sự kiện thay đổi brand
        document.getElementById('filterPieBtn').addEventListener('click', function () {
            const brandId = document.getElementById('brandFilter').value;
            loadCategoryPieChart(brandId);
        });

    });
</script>
<script>
    let monthlyRevenueChart, monthlyUserChart;

    function loadMonthlySummaryChart() {
        fetch(`/admin/dashboard/monthly-summary`)
            .then(res => res.json())
            .then(data => {
                // tổng sau khi có data
                const totalRevenue = data.revenues.values.reduce((sum, val) => sum + Number(val), 0);
                const totalUsers = data.users.values.reduce((sum, val) => sum + Number(val), 0);

                //  tổng
                document.getElementById('totalRevenueYear').textContent = totalRevenue.toLocaleString('vi-VN') + ' ₫';
                document.getElementById('totalUsersYear').textContent = totalUsers;

                // biểu đồ doanh thu
                const revCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
                if (monthlyRevenueChart) monthlyRevenueChart.destroy();
                monthlyRevenueChart = new Chart(revCtx, {
                    type: 'bar',
                    data: {
                        labels: data.revenues.labels,
                        datasets: [{
                            label: data.revenues.label,
                            data: data.revenues.values,
                            backgroundColor: '#34D399'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });

                //biểu đồ người dùng
                const userCtx = document.getElementById('monthlyUserChart').getContext('2d');
                if (monthlyUserChart) monthlyUserChart.destroy();
                monthlyUserChart = new Chart(userCtx, {
                    type: 'bar',
                    data: {
                        labels: data.users.labels,
                        datasets: [{
                            label: data.users.label,
                            data: data.users.values,
                            backgroundColor: '#6366F1'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        loadMonthlySummaryChart();
    });
</script>
<script>
    let hasLoadedMonthlySummary = false;

    document.getElementById('toggleRevenueMode').addEventListener('click', function () {
    const dayChart = document.getElementById('revenueDayWrapper');
    const yearChart = document.getElementById('revenueYearWrapper');
    const toggleBtn = document.getElementById('toggleRevenueMode');

    const showingDay = !dayChart.classList.contains('hidden');

    if (showingDay) {
        dayChart.classList.add('hidden');
        yearChart.classList.remove('hidden');
        document.getElementById('toggleRevenueText').textContent = 'Doanh thu theo ngày';

    } else {
        yearChart.classList.add('hidden');
        dayChart.classList.remove('hidden');
        document.getElementById('toggleRevenueText').textContent = 'Doanh thu theo năm';

    }
    if (!hasLoadedMonthlySummary) {
        loadMonthlySummaryChart();
        hasLoadedMonthlySummary = true;
    }
});

</script>
<script>

    
    document.getElementById('toggleUserMode').addEventListener('click', function () {
        const dayChart = document.getElementById('userDayWrapper');
        const yearChart = document.getElementById('userYearWrapper');
        const toggleText = document.getElementById('toggleUserText');

        const showingDay = !dayChart.classList.contains('hidden');

        if (showingDay) {
            dayChart.classList.add('hidden');
            yearChart.classList.remove('hidden');
            toggleText.textContent = 'Người dùng theo ngày';
        } else {
            yearChart.classList.add('hidden');
            dayChart.classList.remove('hidden');
            toggleText.textContent = 'Người dùng theo mỗi tháng';
        }

        if (!hasLoadedMonthlySummary) {
            loadMonthlySummaryChart();
            hasLoadedMonthlySummary = true;
        }
    });
</script>
<script>
    let topProductsChart;

    function loadTopProductsChart() {
        const start = document.getElementById('top_start').value;
        const end = document.getElementById('top_end').value;
        showGlobalLoading();

        if (start && end && new Date(start) > new Date(end)) {
            Swal.fire({
                icon: 'warning',
                title: 'Ngày không hợp lệ',
                text: 'Ngày bắt đầu không được lớn hơn ngày kết thúc!',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
            hideGlobalLoading();
            return;
        }

        const categoryId = document.getElementById('top_category').value;
        const limit = document.getElementById('top_limit').value || 5; // mặc định là 5 nếu không nhập

        let url = `/admin/dashboard/top-products?`;

        if (start) url += `start_date=${start}&`;
        if (end) url += `end_date=${end}&`;
        if (categoryId) url += `category_id=${categoryId}&`;
        if (limit) url += `limit=${limit}`;
        
        fetch(url)
            .then(res => res.json())
            .then(data => {
                const ctx = document.getElementById('topProductsChart').getContext('2d');
                if (topProductsChart) topProductsChart.destroy();

                topProductsChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: `Top ${limit} sản phẩm bán chạy`,
                            data: data.values,
                            backgroundColor: '#F87171'
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        animation: {
                            duration: 500, // thời gian hiệu ứng khi vẽ lại
                            easing: 'easeOutQuart' // hiệu ứng mượt
                        },
                        plugins: {
                            legend: { display: false },
                            title: {
                                display: true,
                                text: `Top ${limit} sản phẩm bán chạy`
                            }
                        },
                        scales: {
                            x: { beginAtZero: true }
                        }
                    }
                });
                
            })
            .finally(() => {
                hideGlobalLoading(); // ← Luôn ẩn loading sau khi fetch xong dù thành công hay không
            });
    }


    document.addEventListener('DOMContentLoaded', function () {
        // Gán mặc định 7 ngày gần nhất cho top sản phẩm
        const today = new Date();
        const sevenDaysAgo = new Date();
        sevenDaysAgo.setDate(today.getDate() - 6);

        // Format YYYY-MM-DD
        const formatDate = (date) => {
            return date.toISOString().split('T')[0];
        };

        document.getElementById('top_start').value = formatDate(sevenDaysAgo);
        document.getElementById('top_end').value = formatDate(today);

        // Sau khi gán ngày -> load biểu đồ
        loadTopProductsChart();
    });
    function getGradient(ctx, color = 'blue') {
        const gradient = ctx.createLinearGradient(0, 0, 0, 200);
        const colorMap = {
            blue: ['#3B82F6', '#DBEAFE'],
            green: ['#10B981', '#D1FAE5'],
            purple: ['#8B5CF6', '#EDE9FE'],
            red: ['#EF4444', '#FEE2E2'],
        };

        const [start, end] = colorMap[color] || colorMap.blue;

        gradient.addColorStop(0, start);
        gradient.addColorStop(1, end);
        return gradient;
    }

    function showGlobalLoading() {
        document.getElementById('global-loading').classList.remove('hidden');
    }

    function hideGlobalLoading() {
        document.getElementById('global-loading').classList.add('hidden');
    }

</script>
<script>
    let orderMonthChart;
let hasLoadedOrderMonthChart = false;

document.getElementById('toggleOrderMode').addEventListener('click', function () {
    const dayWrapper = document.getElementById('orderDayWrapper');
    const monthWrapper = document.getElementById('orderMonthWrapper');
    const toggleText = document.getElementById('toggleOrderText');

    const isDayVisible = !dayWrapper.classList.contains('hidden');

    if (isDayVisible) {
        dayWrapper.classList.add('hidden');   // Ẩn cả biểu đồ và bộ lọc ngày
        monthWrapper.classList.remove('hidden');
        toggleText.textContent = 'Đơn hàng theo ngày';
    } else {
        dayWrapper.classList.remove('hidden');
        monthWrapper.classList.add('hidden');
        toggleText.textContent = 'Đơn hàng theo mỗi tháng';
    }

    if (!hasLoadedOrderMonthChart) {
        loadMonthlyOrderChart();
        hasLoadedOrderMonthChart = true;
    }
});


function loadMonthlyOrderChart() {
    showGlobalLoading();
    const status = document.getElementById('order_month_status').value;

    let url = `/admin/dashboard/monthly-orders`;
    if (status) url += `?status=${status}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            const total = data.values.reduce((sum, val) => sum + Number(val), 0);
            document.getElementById('totalOrdersYear').textContent = total;

            const ctx = document.getElementById('monthlyOrderChart').getContext('2d');
            if (orderMonthChart) orderMonthChart.destroy();

            orderMonthChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: data.label,
                        data: data.values,
                        backgroundColor: '#60A5FA'
                    }]
                },
                options: {
                    responsive: true,
                    animation: {
                        duration: 500
                    },
                    plugins: {
                        legend: { display: false },
                        title: { display: true, text: data.label }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        })
        .finally(() => {
            hideGlobalLoading();
        });
}

</script>


@endsection
