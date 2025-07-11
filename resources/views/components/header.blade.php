{{-- HEADER --}}
    <div class="bg-[#515154] duration-200 w-full z-40 shadow-xl fixed top-0 left-0 right-0">
        <div class="container mx-auto flex justify-between items-center">
            {{-- Logo + Menu --}}
            <div class="flex items-center justify-between gap-6 w-full lg:w-auto ">
                <button id="btnOpenSidebar" class="lg:hidden">
                    <i class="fas fa-bars text-white text-xl"></i>
                </button>

                <a href="/" class="text-white font-semibold tracking-wider text-2xl uppercase sm:text-3xl mx-auto lg:mx-0">
                    Nexus
                </a>

                {{-- Menu desktop --}}
                <ul class="hidden lg:flex items-center gap-4">
                    {{-- Điện thoại --}}
                    <li>
                        <a href="{{ route('product.phone') }}" class="inline-block py-4 px-4 text-white hover:bg-gray-400" data-route>Điện thoại</a>
                    </li>

                    {{-- Laptop --}}
                    <li>
                        <a href="{{ route('product.laptop') }}" class="inline-block py-4 px-4 text-white hover:bg-gray-400" data-route>Laptop</a>
                    </li>

                    {{-- Đồng hồ --}}
                    {{-- <li>
                        <a href="#" class="inline-block py-4 px-4 text-white hover:bg-gray-400">Đồng hồ</a>
                    </li> --}}

                    {{-- Phụ kiện có dropdown --}}
                    <li class="relative group cursor-pointer">
                        <a href="{{ route('product.accessory') }}" class="flex items-center gap-1 text-white py-4 px-4 hover:bg-gray-400" data-route>
                            Phụ kiện
                            <i class="fas fa-caret-down group-hover:rotate-180 transition-transform"></i>
                        </a>
                        <div class="absolute hidden group-hover:block w-[200px] bg-white rounded-md shadow-md z-50">
                            <ul class="px-2 py-2 space-y-2">
                                <li><a href="{{ route('product.accessory.mobile') }}" class="block p-2 text-gray-600 hover:bg-gray-100" data-route>Phụ kiện di động</a></li>
                                <li><a href="{{ route('product.accessory.audio') }}" class="block p-2 text-gray-600 hover:bg-gray-100" data-route>Thiết bị âm thanh</a></li>
                            </ul>
                        </div>
                    </li>

                    <li>
                        <a href="{{ route('blogs.index') }}" class="inline-block py-4 px-4 text-white hover:bg-gray-400" data-route>Chuyện công nghệ</a>
                    </li>

                    <li>
                        <a href="" class="inline-block py-4 px-4 text-white hover:bg-gray-400" >Về chúng tôi</a>
                    </li>
                </ul>

            </div>

            {{-- Search + Cart + User --}}
            <div class="flex items-center gap-4">
                <i id="btnOpenSearch" class="fas fa-search text-xl text-white cursor-pointer hover:text-slate-300"></i>

                <a href="{{ route('cart.index') }}" class="relative py-3">
                    <i class="fas fa-shopping-cart text-white text-xl hover:text-slate-300"></i>
                    @if($cartQty > 0)
                        <div class="w-4 h-4 bg-red-500 text-white rounded-full absolute top-0 left-[9px] flex items-center justify-center text-xs">
                            {{ $cartQty }}
                        </div>
                    @endif
                </a>


                {{-- User Dropdown --}}
                <div class="relative group py-5 cursor-pointer">
                    <i class="far fa-user text-white text-xl hover:text-slate-300"></i>
                    <div class="absolute right-0 mt-3 w-[200px] bg-white rounded-md shadow-md z-50 hidden group-hover:block">
                        <ul class="py-2 text-sm text-gray-700">
                            @guest
                                <li><a  class="block px-4 py-2 hover:bg-gray-100 text-blue-600 font-semibold">Tạo tài khoản</a></li>
                                <li><a href="{{ route('login') }}"  class="block px-4 py-2 hover:bg-gray-100">Đăng nhập</a></li>
                            @else
                                <li><a  class="block px-4 py-2 hover:bg-gray-100">Thông tin</a></li>
                                <li><a  class="block px-4 py-2 hover:bg-gray-100">Đơn hàng</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
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
                <form action="{{ route('product.search') }}" method="GET" id="searchForm">
                    <input
                        type="text"
                        name="q"
                        placeholder="Bạn cần tìm kiếm gì ..."
                        class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none"
                        required
                    />
                </form>
            </div>
        </div>

        {{-- Sidebar overlay --}}
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

        {{-- Mobile Sidebar --}}
        <div id="mobileSidebar"
             class="fixed top-0 left-0 h-full w-64 bg-[#222] z-50 transform -translate-x-full transition-transform duration-300">
            <div class="p-4 flex justify-between items-center border-b border-white/10">
                <span class="text-white text-xl font-semibold">Nexus</span>
                <button id="btnCloseSidebar" class="text-white text-2xl hover:text-red-400">&times;</button>
            </div>

            <ul class="flex flex-col gap-2 p-4">
                {{-- Điện thoại --}}
                <li><a href="{{ route('product.phone') }}" class="block text-white py-2 border-b border-white/10" >Điện thoại</a></li>

                {{-- Laptop --}}
                <li><a href="{{ route('product.laptop') }}" class="block text-white py-2 border-b border-white/10" >Laptop</a></li>

                {{-- Đồng hồ --}}
                {{-- <li><a href="#" class="block text-white py-2 border-b border-white/10">Đồng hồ</a></li> --}}

                {{-- Phụ kiện có submenu --}}
                <li>
                    <button class="w-full flex items-center justify-between text-white py-2 border-b border-white/10 toggle-submenu">
                        <span>Phụ kiện</span>
                        <i class="fas fa-caret-down caret-icon"></i>
                    </button>
                    <ul class="pl-4 mt-2 hidden submenu">
                        <li><a href="#" class="block text-white/80 py-1 text-sm">Phụ kiện di động</a></li>
                        <li><a href="#" class="block text-white/80 py-1 text-sm">Thiết bị âm thanh</a></li>
                    </ul>
                </li>

                <li><a href="" class="block text-white py-2 border-b border-white/10">Chuyện công nghệ</a></li>
                <li><a href="" class="block text-white py-2 border-b border-white/10">Về chúng tôi</a></li>
            </ul>

        </div>
    </div>

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const currentUrl = window.location.href.split('?')[0];
            document.querySelectorAll('a[data-route]').forEach(link => {
                const href = link.href;
                if (currentUrl === href || currentUrl.startsWith(href)) {
                    link.classList.add('bg-gray-400', 'text-white');
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const input = document.querySelector('#searchForm input[name="q"]');
            input.addEventListener("keydown", function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    document.getElementById("searchForm").submit();
                }
            });
        });
    </script>


