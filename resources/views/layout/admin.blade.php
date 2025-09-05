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


    
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

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
    <div id="sidebarWrapper" class="fixed md:static z-40 w-64 bg-[#242a33] shadow-lg h-full overflow-y-auto transform -translate-x-full md:translate-x-0 transition-all duration-500 flex flex-col" data-collapsed="false">

        <div class="p-6 text-xl font-bold border-b">
            <span class="sidebar-text text-white">🌟 Nexus Admin</span>
        </div>

        <nav class="flex-1 p-4 space-y-2 text-white">
            @role('admin')
            <a href="{{ route('admin.dashboard') }}" 
            class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-500"
            data-route="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> <span class="sidebar-text">Dashboard</span>
            </a>
            @endrole

            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-500" data-route>
                <i class="fas fa-user"></i> <span class="sidebar-text">Users</span>
            </a>
            {{-- <a href="#" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-500">
                <i class="fas fa-chart-bar"></i> <span class="sidebar-text">Reports</span>
            </a> --}}
            <a href="{{ route('admin.products.index') }}" 
            class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-500"
            data-route="{{ route('admin.products.index') }}">
                <i class="fas fa-box"></i> <span class="sidebar-text">Products</span>
            </a>
            @role('admin')
            <a href="{{ route('admin.stock-all') }}" 
            class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-500"
            data-route="{{ route('admin.stock-all') }}">
                <i class="fas fa-warehouse"></i> <span class="sidebar-text">Stocks</span>
            </a>
            @endrole
            <a href="{{ route('admin.categories.index') }}" 
            class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-500"
            data-route="{{ route('admin.categories.index') }}"
            >
                <i class="fas fa-tags"></i> <span class="sidebar-text">Categories</span>
            </a>
            <a href="{{ route('admin.brands.index') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-500"
            data-route="{{ route('admin.brands.index') }}">
                <i class="fas fa-industry"></i> <span class="sidebar-text">Brands</span>
            </a>
            <a href="{{ route('admin.banners.index') }}" 
            class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-500"
            data-route="{{ route('admin.banners.index') }}">
                <i class="fa-solid fa-images"></i> <span class="sidebar-text">Banners</span>
            </a>

            <a href="{{ route('admin.blogs.index') }}" 
            class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-500"
            data-route="{{ route('admin.blogs.index') }}">
                <i class="fa-solid fa-newspaper"></i> <span class="sidebar-text">Blogs</span>
            </a>

            

            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-500" data-route>
                <i class="fas fa-receipt"></i> <span class="sidebar-text">Orders</span>
            </a>
            <a href="{{ route('admin.comments.index') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-500" data-route>
                <i class="fa-solid fa-comments"></i> <span class="sidebar-text">Comments Blog</span></a>

            <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-500"
            data-route="{{ route('admin.reviews.index') }}">
                <i class="fa-solid fa-star"></i> <span class="sidebar-text">Reviews Product</span>
            </a>

            <a href="{{ route('admin.contacts.index') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-500" data-route>
                <i class="fas fa-envelope"></i><span class="sidebar-text">Contacts</span></a>

            <a href="{{ route('chat.admin.index') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-500" data-route>
                <i class="fas fa-headset mr-2"></i> <span class="sidebar-text">Chat Customer</span></a>
            </a>

            <a href="{{ route('admin.vouchers.index') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-500" data-route>
                <i class="fa-solid fa-ticket"></i> <span class="sidebar-text">Vouchers</span></a>
            
            @can('view log')
                <a href="{{ route('admin.logs.index') }}" 
                class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-500"
                data-route="{{ route('admin.logs.index') }}">
                    <i class="fa-solid fa-screwdriver-wrench"></i> <span class="sidebar-text">Log Actions</span>
                </a>
            @endcan

            

        </nav>

        <!-- Button thu gọn -->
        <div class="p-4 border-t hidden md:block">
            <button id="collapseSidebarBtn" class="w-full flex items-center justify-center gap-2 text-sm text-white hover:text-indigo-600 transition duration-200 group">
                <i id="collapseIcon" class="fas fa-angle-double-left transition-transform duration-300 group-hover:rotate-180"></i>
                <span class="sidebar-text">Thu gọn</span>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col flex-1 h-full overflow-hidden">

        <!-- Header -->
        <header class="bg-gray-200 shadow p-6 flex items-center justify-between flex-shrink-0">
            <button id="toggleSidebar" class="text-2xl text-gray-500 md:hidden">
                <i class="fas fa-bars"></i>
            </button>
            <div class="text-lg font-bold text-center w-full">Admin Dashboard</div>
            <div class="flex items-center gap-4">
                <i class="fas fa-bell text-gray-500"></i>

                <div class="relative" id="avatarDropdownWrapper">
                    <!-- Avatar -->
                    <img id="avatarToggle"
                        src="{{ asset('storage/' . (Auth::user()->avatar ?? 'avatars/default.jpg')) }}"
                        alt="Avatar"
                        class="w-9 h-9 rounded-full object-cover cursor-pointer border-1 border-white shadow" />


                    <!-- Dropdown -->
                    <div
                        id="avatarDropdown"
                        class="absolute right-0 mt-2 w-72 bg-white border rounded-lg shadow-lg z-50 hidden"
                    >
                        {{-- Header Avatar --}}
                        <div class="flex items-center gap-3 px-4 py-4 bg-blue-600 rounded-t-lg text-white">
                            <img src="{{ asset('storage/' . (Auth::user()->avatar ?? 'avatars/default.jpg')) }}"
                                class="w-12 h-12 rounded-full object-cover border-2 border-white shadow" alt="Avatar">
                            <div class="overflow-hidden">
                                <div class="font-semibold text-base truncate max-w-[180px]">
                                    {{ Auth::user()->name }}
                                </div>
                                <div class="text-sm opacity-80 truncate max-w-[180px] whitespace-nowrap overflow-hidden">
                                    {{ Auth::user()->email }}
                                </div>
                            </div>
                        </div>

                        {{-- Menu Items --}}
                        <a href="{{ route('admin.profile.show') }}"
                        class="flex items-center gap-3 px-4 py-3 text-gray-800 hover:bg-gray-100 text-sm">
                            <i class="fas fa-user text-blue-600 w-5"></i> <span>Thông tin cá nhân</span>
                        </a>

                        <a href="{{ route('admin.password.form') }}"
                        class="flex items-center gap-3 px-4 py-3 text-gray-800 hover:bg-gray-100 text-sm">
                            <i class="fas fa-key text-yellow-500 w-5"></i> <span>Thay đổi mật khẩu</span>
                        </a>

                        <form action="{{ route('admin.logout') }}" method="POST" class="border-t">
                            @csrf
                            <button type="submit"
                                    class="flex items-center gap-3 px-4 py-3 w-full text-left text-red-600 hover:bg-gray-100 text-sm">
                                <i class="fas fa-sign-out-alt w-5"></i> <span>Đăng xuất</span>
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
        <footer class="bg-gray-200 text-center text-sm text-gray-500 p-4 flex-shrink-0 shadow-inner">
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


{{-- lay mau cho section hien tai --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const currentUrl = window.location.href.split('?')[0]; // loại bỏ query string nếu có
        document.querySelectorAll('nav a[data-route]').forEach(link => {
            if (currentUrl === link.href || currentUrl.startsWith(link.href)) {
                link.classList.add('bg-gray-500', 'text-white');
            }
        });
    });
</script>



</html>
