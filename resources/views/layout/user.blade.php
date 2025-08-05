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
<!--Start of Tawk.to Script-->
@if(auth()->check())
<!-- Nút mở chat -->
{{-- <div id="tawkLauncher"
     class="fixed bottom-4 right-4 z-50 text-3xl cursor-pointer bg-green-600 hover:bg-green-700 text-white w-14 h-14 flex items-center justify-center rounded-full shadow-lg">
    💬
</div> --}}

<script>
    var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();

    Tawk_API.onLoad = function () {
        Tawk_API.hide(); // Ẩn ngay sau khi tải
    };

    $(document).ready(function () {
        $('#tawkLauncher').on('click', function () {
            Tawk_API.maximize(); // Hiện khung chat
        });
    });
</script>
@endif
@if(session('clear_tawk'))
<script>
    // Xóa session cũ của Tawk.to sau khi logout
    localStorage.removeItem('tawkUUID');
</script>
@endif

<!-- Tawk.to Script -->

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    @auth
    Tawk_API.onLoad = function () {
        
        Tawk_API.setAttributes({
            name: "{{ Auth::user()->name }}",
            email: "{{ Auth::user()->email }}",
            hash: "{{ hash_hmac('sha256', Auth::user()->email, 'TawkToPropertyAPIKey') }}"
        }, function (error) {
            if (error) {
                console.error('Tawk.to setAttributes error:', error);
            }
        });
    };
    @endauth
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/68916e44205309192bd914f7/1j1s1df79';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->

</body>
</html>
