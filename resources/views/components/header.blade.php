{{-- HEADER --}}
    <div class="bg-[#515154] duration-200 w-full z-40 shadow-xl fixed top-0 left-0 right-0 ">
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
                    {{-- <li>
                        <a href="{{ route('product.phone') }}" class="inline-block py-4 px-4 text-white hover:bg-gray-400" data-route>Điện thoại</a>
                    </li> --}}

                    {{-- Laptop --}}
                    {{-- <li>
                        <a href="{{ route('product.laptop') }}" class="inline-block py-4 px-4 text-white hover:bg-gray-400" data-route>Laptop</a>
                    </li> --}}

                    {{-- Đồng hồ --}}
                    {{-- <li>
                        <a href="#" class="inline-block py-4 px-4 text-white hover:bg-gray-400">Đồng hồ</a>
                    </li> --}}

                    @foreach($categories as $category)
                        {{-- Kiểm tra nếu có danh mục con thì thêm dropdown --}}
                        @if($category->children->isNotEmpty())
                            <li class="relative group cursor-pointer">
                                <a href="{{ route('product.category', $category->slug) }}" class="flex items-center gap-1 text-white py-4 px-4 hover:bg-gray-400" data-route>
                                    {{ $category->name }}
                                    <i class="fas fa-caret-down group-hover:rotate-180 transition-transform"></i>
                                </a>
                                <div class="absolute hidden group-hover:block w-[200px] bg-white rounded-md shadow-md z-50">
                                    <ul class="px-2 py-2 space-y-2">
                                        @foreach($category->children as $child)
                                            <li><a href="{{ route('product.category', $child->slug) }}" class="block p-2 text-gray-600 hover:bg-gray-100" data-route>{{ $child->name }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        @else
                            {{-- Nếu không có danh mục con thì hiển thị bình thường --}}
                            <li>
                                <a href="{{ route('product.category', $category->slug) }}" class="inline-block py-4 px-4 text-white hover:bg-gray-400" data-route>
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endif
                    @endforeach

                    {{-- Phụ kiện có dropdown --}}
                    {{-- Thay thế khối cứng Phụ kiện bằng logic động --}}
                    {{-- Phụ kiện có dropdown --}}
{{-- Thay thế khối cứng Phụ kiện bằng logic động --}}
@if(isset($accessory) && $accessory->children->isNotEmpty())
    <li class="relative group cursor-pointer">
        {{-- Sử dụng route "product.accessory" cho danh mục cha "Phụ kiện" --}}
        <a href="{{ route('product.accessory') }}" class="flex items-center gap-1 text-white py-4 px-4 hover:bg-gray-400" data-route>
            {{ $accessory->name }}
            <i class="fas fa-caret-down group-hover:rotate-180 transition-transform"></i>
        </a>
        <div class="absolute hidden group-hover:block w-[200px] bg-white rounded-md shadow-md z-50">
            <ul class="px-2 py-2 space-y-2">
                @foreach($accessory->children as $child)
                    <li>
                        {{-- Sử dụng route "product.category.accessory" cho các danh mục con --}}
                        <a href="{{ route('product.category.accessory', $child->slug) }}" class="block p-2 text-gray-600 hover:bg-gray-100" data-route>
                            {{ $child->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </li>
@endif

                    <li>
                        <a href="{{ route('blogs.index') }}" class="inline-block py-4 px-4 text-white hover:bg-gray-400" data-route>Chuyện công nghệ</a>
                    </li>

                    <li>
                        <a href="{{ route('about') }}" class="inline-block py-4 px-4 text-white hover:bg-gray-400" data-route>Về chúng tôi</a>
                    </li>
                </ul>

            </div>

            {{-- Search + Cart + User --}}
            <div class="flex items-center gap-4">
                <i id="btnOpenSearch" class="fas fa-search text-xl text-white cursor-pointer hover:text-slate-300"></i>

                <a href="{{ route('cart.index') }}" class="relative py-3">
                    <i class="fas fa-shopping-cart text-white text-xl hover:text-slate-300"></i>
                    {{-- @if($cartQty > 0)
                        <div class="w-4 h-4 bg-red-500 text-white rounded-full absolute top-0 left-[9px] flex items-center justify-center text-xs">
                            {{ $cartQty }}
                        </div>
                    @endif --}}
                    @if($cartQty > 0)
                        <div id="cartQtyHeader" class="w-4 h-4 bg-red-500 text-white rounded-full absolute top-0 left-[9px] flex items-center justify-center text-xs">
                            {{ $cartQty }}
                        </div>
                    @else
                        <div id="cartQtyHeader" class="hidden w-4 h-4 bg-red-500 text-white rounded-full absolute top-0 left-[9px] flex items-center justify-center text-xs">
                            0
                        </div>
                    @endif
                </a>

                {{-- <a href="{{ route('favorites.index') }}" class="relative py-3">
                    <i class="fas fa-heart text-white text-xl hover:text-slate-300"></i>
                    <div id="favoriteQtyHeader" class="hidden w-4 h-4 bg-red-500 text-white rounded-full absolute top-0 left-[9px] flex items-center justify-center text-xs">
                        0
                    </div>
                </a> --}}
                {{-- User Dropdown --}}
                <div class="relative group py-5 cursor-pointer">
                    <i class="far fa-user text-white text-xl hover:text-slate-300"></i>
                    <div class="absolute right-0 mt-3 w-[200px] bg-white rounded-md shadow-md z-50 hidden group-hover:block">
                        <ul class="py-2 text-sm text-gray-700">
                            @guest
                                <li><a href="{{ route('register') }}" class="block px-4 py-2 hover:bg-gray-100 text-blue-600 font-semibold">Tạo tài khoản</a></li>
                                <li><a href="{{ route('login') }}"  class="block px-4 py-2 hover:bg-gray-100">Đăng nhập</a></li>
                            @else
                                {{-- <li><a href="{{ route('password.form') }}" class="block px-4 py-2 hover:bg-gray-100">Đổi mật khẩu</a></li> --}}
                                {{-- <li><a href="{{ route('user.orders.index') }}" class="block px-4 py-2 hover:bg-gray-100">Đơn hàng</a></li> --}}
                                <li><a href="{{route('user.profile')}}" class="block px-4 py-2 hover:bg-gray-100">Thông tin</a></li>
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
                    <div class="relative w-full max-w-lg mx-auto">
                        <input
                            type="text"
                            id="search-input"
                            name="q"
                            placeholder="Bạn cần tìm sản phẩm gì ..."
                            class="w-full text-sm border border-gray-300 rounded px-3 py-2 pr-10 focus:outline-none"
                            autocomplete="off"
                        />

                        <!-- Suggest box -->
                        <div id="suggestBox" class="absolute left-0 right-0 bg-white border border-gray-300 mt-1 rounded shadow-lg hidden z-50 max-h-96 overflow-y-auto">
                            <!-- Kết quả AJAX sẽ đẩy vào đây -->
                        </div>
                        {{-- <button
                            type="button"
                            id="voice-search-btn"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-600 hover:text-black"
                            title="Tìm kiếm bằng giọng nói"
                        >
                            🎤
                        </button> --}}
                    </div>
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
                {{-- <li><a href="{{ route('product.phone') }}" class="block text-white py-2 border-b border-white/10" >Điện thoại</a></li> --}}

                {{-- Laptop --}}
                {{-- <li><a href="{{ route('product.laptop') }}" class="block text-white py-2 border-b border-white/10" >Laptop</a></li> --}}

                {{-- Đồng hồ --}}
                {{-- <li><a href="#" class="block text-white py-2 border-b border-white/10">Đồng hồ</a></li> --}}

                @foreach($categories as $category)
                    @if($category->children->isNotEmpty())
                        <li>
                            <button class="w-full flex items-center justify-between text-white py-2 border-b border-white/10 toggle-submenu">
                                <a href="{{ route('product.category', $category->slug) }}"><span>{{ $category->name }}</span></a>
                                <i class="fas fa-caret-down caret-icon"></i>
                            </button>
                            <ul class="pl-4 mt-2 hidden submenu">
                                @foreach($category->children as $child)
                                    <li><a href="{{ route('product.category', $child->slug) }}" class="block text-white/80 py-1 text-sm">{{ $child->name }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li><a href="{{ route('product.category', $category->slug) }}" class="block text-white py-2 border-b border-white/10">{{ $category->name }}</a></li>
                    @endif
                @endforeach

                {{-- Phụ kiện có submenu --}}
                @if(isset($accessory) && $accessory->children->isNotEmpty())
                    <li>
                        <button class="w-full flex items-center justify-between text-white py-2 border-b border-white/10 toggle-submenu">
                            <a href="{{ route('product.accessory') }}"><span>{{ $accessory->name }}</span></a>
                            <i class="fas fa-caret-down caret-icon"></i>
                        </button>
                        <ul class="pl-4 mt-2 hidden submenu">
                            @foreach($accessory->children as $child)
                                <li><a href="{{ route('product.category.accessory', $child->slug) }}" class="block text-white/80 py-1 text-sm">{{ $child->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endif

                <li><a href="{{ route('blogs.index') }}" class="block text-white py-2 border-b border-white/10">Chuyện công nghệ</a></li>
                <li><a href="{{ route('about') }}" class="block text-white py-2 border-b border-white/10">Về chúng tôi</a></li>
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
    <script>
        function updateCartQty() {
            fetch('{{ route("cart.count") }}')
                .then(res => res.json())
                .then(data => {
                    const qtyEl = document.getElementById('cartQtyHeader');
                    if (qtyEl) {
                        qtyEl.innerText = data.count;
                        qtyEl.classList.toggle('hidden', data.count === 0);
                    }
                });
        }

    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById('search-input');
        const searchForm = document.getElementById('searchForm');
        const searchOverlay = document.getElementById('searchOverlay');
        const btnOpenSearch = document.getElementById('btnOpenSearch');
        // const btnVoice = document.getElementById('voice-search-btn');

        // Mở khung tìm kiếm khi click kính lúp
        btnOpenSearch.addEventListener('click', function () {
            searchOverlay.classList.remove('hidden');
            searchOverlay.classList.add('flex');

            setTimeout(() => searchInput.focus(), 200);
        });

        // Nhấn Enter để tìm
        searchInput.addEventListener("keydown", function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchForm.submit();
            }
        });

        // Voice Search
        // btnVoice.addEventListener('click', function () {
        //     const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

        //     if (!SpeechRecognition) {
        //         alert('Trình duyệt của bạn không hỗ trợ tìm kiếm bằng giọng nói.');
        //         return;
        //     }

        //     const recognition = new SpeechRecognition();
        //     recognition.lang = 'vi-VN';
        //     recognition.interimResults = false;
        //     recognition.maxAlternatives = 1;

        //     recognition.start();

        //     recognition.onstart = function () {
        //         searchInput.placeholder = "🎙️ Đang nghe...";
        //     };

        //     recognition.onresult = function (event) {
        //         const transcript = event.results[0][0].transcript;
        //         searchInput.value = transcript;
        //         searchInput.placeholder = "Bạn cần tìm sản phẩm gì ...";

        //         // Delay 1000ms để người dùng có thể chỉnh sửa thêm nếu muốn
        //         setTimeout(() => {
        //             searchForm.submit();
        //         }, 1000);
        //     };

        //     recognition.onerror = function (event) {
        //         console.error('Lỗi nhận diện:', event.error);
        //         alert('Không thể nhận diện giọng nói. Vui lòng thử lại.');
        //         searchInput.placeholder = "Bạn cần tìm sản phẩm gì ...";
        //     };

        //     recognition.onend = function () {
        //         searchInput.placeholder = "Bạn cần tìm sản phẩm gì ...";
        //     };
        // });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById('search-input');
    const suggestBox = document.getElementById('suggestBox');
    let timeout = null;

    searchInput.addEventListener('input', function () {
        clearTimeout(timeout);
        const query = this.value.trim();

        if (!query) {
            suggestBox.classList.add('hidden');
            suggestBox.innerHTML = '';
            return;
        }

        timeout = setTimeout(() => {
            fetch(`{{ route('product.searchSuggest') }}?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    if (!data.length) {
                        suggestBox.innerHTML = '<div class="p-3 text-gray-500 text-sm">Không tìm thấy sản phẩm</div>';
                    } else {
                        suggestBox.innerHTML = data.map(item => {
                            const price = Number(item.price).toLocaleString('vi-VN') + '₫';
                            const originalPrice = item.original_price > item.price 
                                ? `<span class="line-through text-gray-400 ml-2 text-xs">${Number(item.original_price).toLocaleString('vi-VN')}₫</span>` 
                                : '';
                            const sale = item.sale_percent 
                                ? `<span class="ml-2 text-xs text-green-600 font-semibold bg-green-100 px-1 rounded">-${item.sale_percent}%</span>` 
                                : '';
                            const img = item.image 
                                ? `<img src="/storage/${item.image}" class="w-12 h-12 object-contain flex-shrink-0 rounded">` 
                                : `<div class="w-12 h-12 bg-gray-100 flex items-center justify-center text-gray-400 text-xs">No Image</div>`;

                            const url = (item.category_slug && ['dien-thoai','laptop'].includes(item.category_slug))
                                ? `/products/${item.slug}`
                                : `/phu-kien/${item.slug}`;

                            return `
                                <a href="${url}" class="flex items-center gap-3 p-2 hover:bg-gray-100 border-b last:border-none transition">
                                    ${img}
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-gray-700">${item.name}</div>
                                        <div class="flex items-center text-red-500 mt-1 font-semibold text-sm">
                                            ${price} ${originalPrice} ${sale}
                                        </div>
                                    </div>
                                </a>
                            `;
                        }).join('');

                    }
                    suggestBox.classList.remove('hidden');
                });
        }, 250); // delay 250ms
    });

    // Ẩn suggest khi click ra ngoài
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !suggestBox.contains(e.target)) {
            suggestBox.classList.add('hidden');
        }
    });
});

</script>



{{-- <script>

 async function updateFavoriteQty() {
        try {
            const res = await fetch('/favorites/count');
            if (res.ok) {
                const data = await res.json();
                const qtyEl = document.getElementById('favoriteQtyHeader');
                if (qtyEl) {
                    qtyEl.innerText = data.count;
                    qtyEl.classList.toggle('hidden', data.count === 0);
                }
            }
        } catch (e) {
            console.error('Lỗi khi load số lượng yêu thích:', e);
        }
    }

    updateFavoriteQty();
    setInterval(updateFavoriteQty, 3000); // mỗi 3s cập nhật số lượng


    // ----- TOGGLE YÊU THÍCH -----
    async function toggleFavorite(productId) {
        try {
            const btn = document.querySelector(`.favorite-btn[data-product-id="${productId}"]`);
            if(!btn) return;

            const isFavorited = btn.classList.contains('text-red-500');

            if (isFavorited) {
                const res = await fetch(`/favorites/by-product/${productId}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                if (res.ok) {
                    btn.classList.remove('text-red-500', 'border-red-500');
                    btn.classList.add('text-gray-400', 'border-gray-300');
                    updateFavoriteQty();
                }
            } else {
                const res = await fetch(`/favorites`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ product_id: productId })
                });
                if (res.ok) {
                    btn.classList.add('text-red-500', 'border-red-500');
                    btn.classList.remove('text-gray-400', 'border-gray-300');
                    updateFavoriteQty();
                }
            }
        } catch(err) {
            console.error(err);
        }
    }

    
    async function highlightFavorites() {
        @if(auth()->check())
            try {
                const res = await fetch(`/favorites`);
                if (res.ok) {
                    const favorites = await res.json();
                    favorites.forEach(fav => {
                        const btn = document.querySelector(`.favorite-btn[data-product-id="${fav.product_id}"]`);
                        if (btn) {
                            btn.classList.add('text-red-500','border-red-500');
                            btn.classList.remove('text-gray-400','border-gray-300');
                        }
                    });
                }
            } catch(e) {
                console.error('Lỗi highlightFavorites:', e);
            }
        @endif
    }

    highlightFavorites();


    // ----- GỌI HIGHLIGHT SAU KHI RENDER LẠI DANH SÁCH SẢN PHẨM (AJAX / phân trang) -----
    window.renderProductList = function(html) {
        document.getElementById('productListContainer').innerHTML = html;
        highlightFavorites(); // tô đỏ lại các favorite button mới
    }

</script> --}}




