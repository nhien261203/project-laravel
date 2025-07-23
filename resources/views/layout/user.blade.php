<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Nexus.com Điện thoại, Laptop, Đồng hồ, Phụ kiện chính hãng</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Tailwind CSS --}}
    @vite(['resources/css/app.css'])

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- Swiper CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

    <!-- alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">

    {{-- HEADER --}}
    @include('components.header')

    {{-- Main content: bỏ class="min-h-screen"  --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('components.footer')

    {{-- Swiper JS --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    @stack('scripts')

    {{-- Custom JS --}}
    <script>
        $(document).ready(function () {
            // Sidebar
            $('#btnOpenSidebar').on('click', function () {
                $('#mobileSidebar').removeClass('-translate-x-full');
                $('#sidebarOverlay').removeClass('hidden');
            });
            $('#btnCloseSidebar, #sidebarOverlay').on('click', function () {
                $('#mobileSidebar').addClass('-translate-x-full');
                $('#sidebarOverlay').addClass('hidden');
            });

            // Search Overlay
            $('#btnOpenSearch').on('click', function () {
                $('#searchOverlay').css('display', 'flex').hide().fadeIn(200, function () {
                    $('#searchOverlay input').focus();
                });
            });
            $('#searchOverlay').on('click', function () {
                $('#searchOverlay').fadeOut(200);
            });
            $('#searchBox').on('click', function (e) {
                e.stopPropagation();
            });
            $(document).on('keydown', function (e) {
                if (e.key === 'Escape') {
                    $('#searchOverlay').fadeOut(200);
                    $('#mobileSidebar').addClass('-translate-x-full');
                    $('#sidebarOverlay').addClass('hidden');
                }
            });

            // Dropdown in mobile sidebar
            $('.toggle-submenu').on('click', function () {
                const submenu = $(this).next('.submenu');
                submenu.slideToggle(200);
                $(this).find('.caret-icon').toggleClass('rotate-180');
            });
        });
    </script>

    
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

</body>
</html>
