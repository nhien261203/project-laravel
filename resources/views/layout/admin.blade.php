<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexus Admin - All In One</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css'])

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/admin.js') }}" defer></script>

    <style>
        html, body {
            height: 100%;
        }
       
        #sidebarWrapper {
            min-height: 100vh;
        }
    </style>
</head>
<body class="h-screen overflow-y-auto flex bg-gray-100 text-gray-800">

    <!-- Sidebar -->
    <div id="sidebarWrapper" class="fixed md:static z-40 w-64 bg-white shadow-lg h-full transform -translate-x-full md:translate-x-0 transition-all duration-500 flex flex-col" data-collapsed="false">
        <div class="p-6 text-xl font-bold border-b">
            <span class="sidebar-text">🌟 Nexus Admin</span>
        </div>

        <nav class="flex-1 p-4 space-y-2 text-gray-700">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-100">
                <i class="fas fa-tachometer-alt"></i> <span class="sidebar-text">Dashboard</span>
            </a>
            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-100">
                <i class="fas fa-user"></i> <span class="sidebar-text">Users</span>
            </a>
            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-100">
                <i class="fas fa-chart-bar"></i> <span class="sidebar-text">Reports</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-100">
                <i class="fas fa-box"></i> <span class="sidebar-text">Products</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-100">
                <i class="fas fa-tags"></i> <span class="sidebar-text">Categories</span>
            </a>
            <a href="{{ route('admin.brands.index') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-100">
                <i class="fas fa-industry"></i> <span class="sidebar-text">Brands</span>
            </a>
            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-100">
                <i class="fas fa-receipt"></i> <span class="sidebar-text">Orders</span>
            </a>
        </nav>

        <!-- Button thu gọn -->
        <div class="p-4 border-t hidden md:block">
            <button id="collapseSidebarBtn" class="w-full flex items-center justify-center gap-2 text-sm text-gray-600 hover:text-indigo-600 transition duration-200 group">
                <i id="collapseIcon" class="fas fa-angle-double-left transition-transform duration-300 group-hover:rotate-180"></i>
                <span class="sidebar-text">Thu gọn</span>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col flex-1 h-full overflow-hidden">

        <!-- Header -->
       <header class="bg-white shadow p-6 flex items-center justify-between flex-shrink-0">
            <button id="toggleSidebar" class="text-2xl text-gray-500 md:hidden">
                <i class="fas fa-bars"></i>
            </button>
            <div class="text-lg font-bold text-center w-full">Admin Dashboard</div>
            <div class="flex items-center gap-4">
                <i class="fas fa-bell text-gray-500"></i>

                <div class="relative" id="avatarDropdownWrapper">
                    <!-- Avatar -->
                    <div id="avatarToggle" class="w-8 h-8 rounded-full bg-gray-300 cursor-pointer"></div>

                    <!-- Dropdown -->
                    <div
                        id="avatarDropdown"
                        class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-50 hidden"
                    >
                        <div class="px-4 py-2 text-sm text-gray-700 border-b">
                            <a href="{{ route('admin.profile.show') }}"> Xin chào, {{ Auth::user()->name }}</a>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button
                                type="submit"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 text-red-600"
                            >
                                <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>


        <!-- Page content -->
        <main class="flex-1  overflow-y-auto p-6 bg-gray-50">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white text-center text-sm text-gray-500 p-4 flex-shrink-0 shadow-inner">
            &copy; {{ date('Y') }} Nexus Admin . Tool Admin All In One.
        </footer>
    </div>

    <!-- Overlay for mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden"></div>
    @stack('scripts')
</body>
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}


{{-- trang thai alert --}}
<script>
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Thành công',
            text: '{{ session('success') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2500
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: '{{ session('error') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    @endif
</script>
{{-- confirm delete --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function () {
                const form = this.closest('form');

                Swal.fire({
                    title: 'Xác nhận xoá',
                    text: "Bạn có chắc muốn xoá mục này?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: ' Xoá',
                    cancelButtonText: 'Huỷ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.requestSubmit();
                    }
                });
            });
        });
    });
</script>
<script>
    $(document).ready(function () {
        // Toggle dropdown khi click avatar
        $('#avatarToggle').on('click', function (e) {
            e.stopPropagation(); // không lan ra document
            $('#avatarDropdown').toggle();
        });

        // Click ngoài dropdown thì ẩn
        $(document).on('click', function (e) {
            if (!$(e.target).closest('#avatarDropdownWrapper').length) {
                $('#avatarDropdown').hide();
            }
        });
    });
</script>


</html>
