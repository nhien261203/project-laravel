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
</head>
<body class="bg-gray-100">

    {{-- NAVBAR HEADER --}}
    <div class="bg-[#515154] duration-200 w-full z-40 shadow-xl relative">
        <div class="container mx-auto flex justify-between items-center">
            {{-- Left: Logo + Menu --}}
            <div class="flex items-center justify-between gap-6 w-full lg:w-auto">
                <button id="btnOpenSidebar" class="lg:hidden">
                    <i class="fas fa-bars text-white text-xl"></i>
                </button>

                <a href="#" class="text-white font-semibold tracking-wider text-2xl uppercase sm:text-3xl mx-auto lg:mx-0">
                    Nexus
                </a>

                <ul class="hidden lg:flex items-center gap-4">
                    <li><a href="/#" class="inline-block py-4 px-4 text-white hover:bg-gray-400">Điện thoại</a></li>
                    <li><a href="/#shop" class="inline-block py-4 px-4 text-white hover:bg-gray-400">Laptop</a></li>
                    <li><a href="/#about" class="inline-block py-4 px-4 text-white hover:bg-gray-400">Ipad</a></li>
                    <li><a href="/#about" class="inline-block py-4 px-4 text-white hover:bg-gray-400">Đồng hồ</a></li>
                    <li><a href="/#about" class="inline-block py-4 px-4 text-white hover:bg-gray-400">PC, Màn hình</a></li>

                    <li class="relative group cursor-pointer">
                        <a href="#" class="flex items-center gap-1 text-white py-4 px-4 hover:bg-gray-400">
                            Phụ kiện
                            <i class="fas fa-caret-down group-hover:rotate-180 transition-transform"></i>
                        </a>
                        <div class="absolute hidden group-hover:block w-[200px] bg-white rounded-md shadow-md z-50">
                            <ul class="px-2 py-2 space-y-2">
                                <li><a href="#" class="block p-2 text-gray-600 hover:bg-gray-100">Phụ kiện di động</a></li>
                                <li><a href="#" class="block p-2 text-gray-600 hover:bg-gray-100">Thiết bị âm thanh</a></li>
                                <li><a href="#" class="block p-2 text-gray-600 hover:bg-gray-100">Phụ kiện laptop</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Right: Search + Cart + User --}}
            <div class="flex items-center gap-4">
                <i id="btnOpenSearch" class="fas fa-search text-xl text-white cursor-pointer hover:text-slate-300"></i>

                <button class="relative py-3">
                    <i class="fas fa-shopping-cart text-white text-xl hover:text-slate-300"></i>
                    <div class="w-4 h-4 bg-red-500 text-white rounded-full absolute top-0 left-[9px] flex items-center justify-center text-xs">
                        4
                    </div>
                </button>

                {{-- User Dropdown --}}
                <div class="relative group py-5 cursor-pointer">
                    <i class="far fa-user text-white text-xl hover:text-slate-300"></i>
                    <div class="absolute right-0 mt-3 w-[200px] bg-white rounded-md shadow-md z-50 hidden group-hover:block">
                        <ul class="py-2 text-sm text-gray-700">
                            @guest
                                <li><a class="block px-4 py-2 hover:bg-gray-100 text-blue-600 font-semibold">Tạo tài khoản</a></li>
                                <li><a class="block px-4 py-2 hover:bg-gray-100">Đăng nhập</a></li>
                            @else
                                <li><a class="block px-4 py-2 hover:bg-gray-100">Thông tin</a></li>
                                <li><a class="block px-4 py-2 hover:bg-gray-100">Đơn hàng</a></li>
                                <li>
                                    <form method="POST" action="">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-red-500">Đăng xuất</button>
                                    </form>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search Overlay --}}
        <div id="searchOverlay" class="fixed inset-0 bg-black/70 z-50 hidden items-start justify-center pt-5">
            <div class="bg-white w-[90%] max-w-lg p-3 rounded shadow-lg" id="searchBox">
                <input type="text"
                       placeholder="Bạn cần tìm kiếm gì ..."
                       class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none" />
            </div>
        </div>

        {{-- Mobile Sidebar --}}
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

        <div id="mobileSidebar"
             class="fixed top-0 left-0 h-full w-64 bg-[#222] z-50 transform -translate-x-full transition-transform duration-300">
            <div class="p-4 flex justify-between items-center border-b border-white/10">
                <span class="text-white text-xl font-semibold">Cyzy</span>
                <button id="btnCloseSidebar" class="text-white text-2xl hover:text-red-400">&times;</button>
            </div>
            <ul class="flex flex-col gap-2 p-4">
                <li><a href="/#" class="block text-white py-2 border-b border-white/10">Điện thoại</a></li>
                <li><a href="/#shop" class="block text-white py-2 border-b border-white/10">Laptop</a></li>
                <li><a href="/#about" class="block text-white py-2 border-b border-white/10">Ipad</a></li>
                <li><a href="/#about" class="block text-white py-2 border-b border-white/10">Đồng hồ</a></li>
                <li><a href="/#about" class="block text-white py-2 border-b border-white/10">PC, Màn hình</a></li>
                <li>
                    <button id="toggleQuickLinks" class="w-full flex items-center justify-between text-white py-2 border-b border-white/10">
                        <span>Phụ kiện</span>
                        <i class="fas fa-caret-down" id="quickLinksCaret"></i>
                    </button>
                    <ul id="quickLinksList" class="pl-4 mt-2 hidden">
                        <li><a href="#" class="block text-white/80 py-1 text-sm">Phụ kiện di động</a></li>
                        <li><a href="#" class="block text-white/80 py-1 text-sm">Thiết bị âm thanh</a></li>
                        <li><a href="#" class="block text-white/80 py-1 text-sm">Phụ kiện laptop</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

    {{-- CONTENT --}}
    <main class="container mx-auto mt-6 p-4">
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-2xl font-bold mb-4">👤 Trang người dùng</h2>
            <p class="text-gray-700">Chào mừng bạn đến trang người dùng. Tại đây bạn có thể xem thông tin cá nhân, đơn hàng, cập nhật tài khoản, v.v...</p>
        </div>
    </main>

    {{-- SCRIPTS --}}
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
                $('#searchOverlay')
                    .css('display', 'flex')
                    .hide()
                    .fadeIn(200, function () {
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

            // Phụ kiện dropdown (mobile)
            $('#toggleQuickLinks').on('click', function () {
                $('#quickLinksList').toggleClass('hidden');
                $('#quickLinksCaret').toggleClass('rotate-180');
            });
        });
    </script>

</body>
</html>
