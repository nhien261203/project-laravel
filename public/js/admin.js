$(document).ready(function () {
    const $sidebar = $('#sidebarWrapper');
    const $overlay = $('#sidebarOverlay');
    const $collapseBtn = $('#collapseSidebarBtn');
    const $collapseIcon = $('#collapseIcon');

    // --- Apply collapsed state on load (desktop only)
    if (window.innerWidth >= 768 && localStorage.getItem('sidebarCollapsed') === 'true') {
        $sidebar.removeClass('w-64').addClass('w-16');
        $('.sidebar-text').addClass('hidden');
        $collapseIcon.removeClass('fa-angle-double-left').addClass('fa-angle-double-right');
        $collapseBtn.find('.sidebar-text').text('');
    }

    // --- Toggle sidebar on mobile
    $('#toggleSidebar').click(function () {
        $sidebar.removeClass('-translate-x-full');
        $overlay.removeClass('hidden');
    });

    // --- Close sidebar on overlay click or Esc key
    $overlay.on('click', closeSidebar);
    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') closeSidebar();
    });

    function closeSidebar() {
        $sidebar.addClass('-translate-x-full');
        $overlay.addClass('hidden');
    }

    // --- Collapse sidebar on desktop
    $collapseBtn.click(function () {
        const isCollapsed = $sidebar.hasClass('w-16');

        if (isCollapsed) {
            $sidebar.removeClass('w-16').addClass('w-64');
            $('.sidebar-text').removeClass('hidden');
            $collapseIcon.removeClass('fa-angle-double-right').addClass('fa-angle-double-left');
            $(this).find('.sidebar-text').text('Thu gọn');
            localStorage.setItem('sidebarCollapsed', 'false');
        } else {
            $sidebar.removeClass('w-64').addClass('w-16');
            $('.sidebar-text').addClass('hidden');
            $collapseIcon.removeClass('fa-angle-double-left').addClass('fa-angle-double-right');
            $(this).find('.sidebar-text').text('');
            localStorage.setItem('sidebarCollapsed', 'true');
        }
    });

    const bar = document.getElementById('salesChart');
    if (bar) {
        new Chart(bar.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Sales',
                    data: [120, 180, 90, 200, 150, 240],
                    backgroundColor: '#4f46e5'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // Chart 2: Line - Revenue Growth
    const line = document.getElementById('lineChart');
    if (line) {
        new Chart(line.getContext('2d'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                datasets: [{
                    label: 'Revenue ($)',
                    data: [1000, 1300, 900, 1700, 1500],
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // Chart 3: Pie - Product Share
    const pie = document.getElementById('pieChart');
    if (pie) {
        new Chart(pie.getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['iPhone', 'Samsung', 'Others'],
                datasets: [{
                    label: 'Product Share',
                    data: [45, 30, 25],
                    backgroundColor: ['#6366f1', '#ec4899', '#f59e0b']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // Chart 4: Horizontal Bar - Page Views
    const horizontal = document.getElementById('horizontalBarChart');
    if (horizontal) {
        new Chart(horizontal.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Home', 'Products', 'Blog', 'About'],
                datasets: [{
                    label: 'Views',
                    data: [400, 250, 300, 180],
                    backgroundColor: '#f97316'
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { beginAtZero: true }
                }
            }
        });
    }
});
