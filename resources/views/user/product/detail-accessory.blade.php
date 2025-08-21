@extends('layout.user')

@section('content')
<div class="container pt-24 pb-10 rounded shadow">
    <div class="flex items-center text-sm text-gray-600 space-x-2 mb-4">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Trang chủ</a>
        <span class="text-gray-400">›</span>
        <span class="text-gray-800 font-medium">{{ $product->name }}</span>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-[4fr_3fr] gap-6">
        {{-- Hình ảnh --}}
        <div class="bg-white rounded-lg shadow-md p-4">
            {{-- Ảnh lớn --}}
            @php
                $defaultVariant = $product->variants->first();
                $defaultImages = $defaultVariant->images;
                $fallbackImages = $product->variants->firstWhere(fn($v) => !$v->images->isEmpty())?->images;
            @endphp

            <div id="mainImage" class=" overflow-hidden">
                @if($defaultImages->count())
                    <img src="{{ asset('storage/' . $defaultImages->first()->image_path) }}" class="w-full h-80 md:h-[420px] object-contain " id="previewImage">
                @elseif($fallbackImages && $fallbackImages->count())
                    <img src="{{ asset('storage/' . $fallbackImages->first()->image_path) }}" class="w-full h-80 md:h-[420px] object-contain " id="previewImage">
                @else
                    <div class="w-full h-80 bg-gray-100 flex items-center justify-center text-gray-400 text-sm">Không có ảnh</div>
                @endif
            </div>

            {{-- Ảnh nhỏ --}}
            <div class="flex mt-4 gap-3 overflow-x-auto pb-2" id="thumbnailWrapper">
                @foreach(($defaultImages->count() ? $defaultImages : $fallbackImages ?? []) as $img)
                    <img src="{{ asset('storage/' . $img->image_path) }}" class="w-20 h-20 object-cover rounded-lg border cursor-pointer hover:scale-105 transition " onclick="changeMainImage('{{ asset('storage/' . $img->image_path) }}')">
                @endforeach
            </div>
        </div>

        {{-- Thông tin --}}
        <div class="px-6 py-6 bg-white rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold text-gray-800 mb-4 leading-snug">{{ $product->name }}</h1>
            <div class="flex items-center text-sm text-gray-600 mb-4 space-x-3">
                <span>Đã bán: <strong>{{ $totalSold }}</strong></span>
                <span>|</span>
                <span>Đánh giá: <strong>{{ $totalReviews }}</strong> </span>
                <span>|</span>
                <span>⭐ <strong>{{ $averageRating }}</strong>/5</span>
            </div>

            {{-- Giá --}}
            <div class="mb-2">
                <span id="variantPrice" class="text-red-600 text-xl md:text-2xl font-semibold">
                    {{ number_format($defaultVariant->price, 0, ',', '.') }}₫
                </span>
                @if($defaultVariant->original_price > $defaultVariant->price)
                    <span id="variantOriginalPrice" class="text-gray-400 line-through text-sm ml-2">
                        {{ number_format($defaultVariant->original_price, 0, ',', '.') }}₫
                    </span>
                @endif
                @if($defaultVariant->sale_percent > 0)
                    <span id="variantSalePercent" class="text-xs text-green-600 font-semibold bg-green-100 px-2 py-0.5 rounded ml-2">
                        -{{ $defaultVariant->sale_percent }}%
                    </span>
                @endif
            </div>

            {{-- Màu sắc --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Màu sắc:</label>
                <div class="flex flex-wrap gap-2" id="colorOptions">
                    @foreach($colors as $color)
                        @php
                            $isActive = $color === $defaultVariant->color;
                        @endphp
                        <button
                            class="color-option px-4 py-1 border rounded-md text-sm {{ $isActive ? 'bg-gray-800 text-white ring ring-gray-400' : '' }}"
                            data-color="{{ $color }}"
                            onclick="selectColor(this, '{{ $color }}')">
                            {{ $color }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Bộ nhớ (chỉ hiển thị nếu có) --}}
            @if(!empty($storages) && count($storages))
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-1">Bộ nhớ:</label>
                    <div class="flex flex-wrap gap-2" id="storageOptions">
                        @foreach($storages as $storage)
                            @php
                                $isActive = $storage === $defaultVariant->storage;
                            @endphp
                            <button
                                class="storage-option px-4 py-1 border rounded-md text-sm {{ $isActive ? 'bg-gray-800 text-white ring ring-gray-400' : '' }}"
                                data-storage="{{ $storage }}"
                                onclick="selectStorage(this, '{{ $storage }}')">
                                {{ $storage }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Số lượng --}}
            <div class="mb-4 w-max">
                <label for="quantity" class="block text-sm font-semibold mb-1">Số lượng:</label>
                <div class="relative w-32">
                    <input
                    type="number"
                    name="quantity"
                    id="quantity"
                    value="1"
                    min="1"
                    class="w-full rounded-full border border-gray-300 text-center px-6 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    <!-- Nút giảm -->
                    <button
                    type="button"
                    onclick="adjustQty(-1)"
                    class="absolute left-1 top-1/2 transform -translate-y-1/2 bg-gray-200 hover:bg-gray-300 text-lg font-bold rounded-full w-8 h-8 flex items-center justify-center select-none"
                    aria-label="Giảm số lượng"
                    >−</button>

                    <!-- Nút tăng -->
                    <button
                    type="button"
                    onclick="adjustQty(1)"
                    class="absolute right-1 top-1/2 transform -translate-y-1/2 bg-gray-200 hover:bg-gray-300 text-lg font-bold rounded-full w-8 h-8 flex items-center justify-center select-none"
                    aria-label="Tăng số lượng"
                    >+</button>
                </div>
            </div>

            {{-- Nút hành động --}}
            {{-- <form method="POST" action="{{ route('cart.add') }}" class="flex gap-3 items-center">
                @csrf
                <input type="hidden" name="variant_id" id="formVariantId" value="{{ $defaultVariant->id }}">
                <input type="hidden" name="quantity" id="formQuantity" value="1">

                <button type="submit" class="bg-yellow-500 text-white px-6 py-2 rounded hover:bg-yellow-600">
                    Thêm vào giỏ
                </button>

                <button type="button" onclick="buyNow()" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Mua ngay
                </button>
            </form> --}}
            <form id="addToCartForm" class=" items-center" onsubmit="return addToCart(event)">
                @csrf
                <input type="hidden" name="variant_id" id="formVariantId" value="{{ $defaultVariant->id }}">
                <input type="hidden" name="quantity" id="formQuantity" value="1">

                <div class="flex gap-3">
                    <button type="submit" class="flex items-center gap-2 bg-yellow-500 text-white px-6 py-2 rounded hover:bg-yellow-600 transition">
                        
                        <i class="fa-solid fa-cart-shopping"></i>
                        Thêm vào giỏ
                    </button>

                    {{-- Nút mua ngay --}}
                    <button type="button" onclick="buyNow()" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                        Mua ngay
                    </button>
                </div>
            </form>
            
            

            <div class="mt-9 p-3 bg-white border border-yellow-400 rounded-lg text-sm text-gray-700">
                    <p class="mb-2">
                        Nếu có bất kỳ thắc mắc nào, hãy liên hệ với chúng tôi qua 
                        <strong>hai khung chat ở góc màn hình</strong>
                    </p>
                    <p class="text-gray-600 text-xs">Đội ngũ hỗ trợ luôn sẵn sàng 24/7.</p>
            </div>

        </div>
    </div>

    {{-- Mô tả sản phẩm --}}
    <div class="grid w-full md:grid-cols-[4fr_3fr] gap-6">
    {{-- Cột trái: mô tả --}}
        <div>
            @if (!empty($product->description))
                <div class="mt-6 border border-gray-200 rounded-lg shadow-sm p-4 bg-white">
                    <div class="text-center">
                        <h3 class="inline-block mt-1 px-4 py-2 rounded-lg text-black border border-gray-400 transition font-semibold text-sm hover:bg-gray-50">
                            Mô tả sản phẩm
                        </h3>
                    </div>

                    <div id="techSpecWrapper" class="overflow-hidden transition-all duration-300 max-h-[200px]">
                        <div class="prose max-w-none text-sm text-gray-800">
                            {!! $product->description !!}
                        </div>
                    </div>

                    <div class="text-center">
                        <button id="toggleSpecBtn" class="mt-3 p-2 rounded-lg text-blue-600 bg-gray-100 hover:bg-blue-100 transition font-medium text-sm">
                            Đọc thêm
                        </button>
                    </div>
                </div>
            @endif
        </div>

        
        <div></div>
    </div>


    

    {{-- Form đánh giá (chỉ hiển thị nếu user đã mua) --}}
{{-- Form đánh giá (chỉ hiển thị nếu user đã mua) --}}
    <div class="mt-6 grid grid-cols-1 md:grid-cols-[4fr_3fr] gap-6 items-start">
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                Đánh giá về sản phẩm <span class="font-semibold">{{ $product->name }}</span>
            </h3>
            <div class="flex items-center gap-4 mb-4 text-sm text-gray-600">
                <div>
                    ⭐: <span class="font-semibold">{{ $averageRating }}</span> / 5
                </div>
                <div>
                    <span class="font-semibold">{{ $totalReviews }}</span> đánh giá
                </div>
            </div>

            @if ($canReview)
                <button id="toggleReviewForm" 
                        class="mb-4 px-3 py-1.5 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm">
                    ✏ Viết đánh giá
                </button>

                <form id="reviewForm" action="{{ route('user.reviews.store') }}" method="POST" 
                    class="space-y-4 p-3 border border-gray-200 rounded hidden text-sm">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    {{-- Chọn sao --}}
                    <label class="block text-gray-700 font-medium mb-1">Đánh giá sao:</label>
                    <div class="flex items-center space-x-1" id="starRating">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg"
                                data-star="{{ $i }}"
                                class="w-6 h-6 cursor-pointer text-gray-300 hover:text-yellow-400 transition"
                                fill="currentColor"
                                viewBox="0 0 24 24">
                                <path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.886L19.335 24 12 20.013 4.665 24l1.399-8.808L0 9.306l8.332-1.151z"/>
                            </svg>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="">

                    <label for="comment" class="block text-gray-700 font-medium mt-2 mb-1">Nội dung:</label>
                    <textarea name="comment" rows="3" 
                            class="w-full border border-gray-300 rounded p-2 resize-none focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>

                    <button type="submit" 
                            class="bg-blue-600 text-white px-4 py-1.5 rounded hover:bg-blue-700 transition text-sm">
                        Gửi đánh giá
                    </button>
                </form>
            @endif

            {{-- Danh sách đánh giá --}}
            @forelse ($approvedReviews as $review)
                <div class="py-3 border-b last:border-b-0">
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <div class="bg-blue-100 text-blue-700 rounded-full px-2 py-0.5 text-xs font-semibold">
                                {{ $review->user->name }}
                            </div>
                            <div class="flex space-x-0.5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.965a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.965c.3.921-.755 1.688-1.538 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.783.57-1.838-.197-1.538-1.118l1.287-3.965a1 1 0 00-.364-1.118L2.05 9.392c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.965z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        <div class="text-sm text-gray-400 italic">
                            {{ $review->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @if ($review->comment)
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line text-sm">{{ $review->comment }}</p>
                    @endif
                </div>
            @empty
                <p class="text-gray-500 py-4 text-sm">Chưa có đánh giá nào về sản phẩm.<br>Bạn hãy mua hàng để đánh giá sản phẩm này.</p>
            @endforelse

            {{-- Phân trang --}}
            <div class="mt-6 flex justify-center">
                {{ $approvedReviews->links('pagination.custom-user') }}
            </div>
        </div>
        <div></div>
    </div>


    {{-- Sản phẩm đã xem --}}
    @if($recentlyViewed->count())
        <div class="mt-10 bg-white rounded-lg shadow-md p-4">
            <h3 class="text-base font-semibold text-gray-700 mb-4">Sản phẩm bạn đã xem gần đây</h3>

            <!-- Swiper Container -->
            <div class="swiper-container relative overflow-hidden">
                <!-- Wrapper -->
                <div class="swiper-wrapper">
                    @foreach($recentlyViewed as $item)
                        @php
                            $variant = $item->variants->first();
                            $image = $variant && $variant->images->first()
                                ? asset('storage/' . $variant->images->first()->image_path)
                                : 'https://via.placeholder.com/300x300?text=No+Image';
                        @endphp
                        <div class="swiper-slide">
                            <a href="{{ $item->is_accessory ? route('product.detailAccessory', $item->slug) : route('product.detail', $item->slug) }}" class="block bg-white border rounded hover:shadow transition overflow-hidden">
                                <img src="{{ $image }}" class="w-full h-36 md:h-40 object-contain bg-gray-50">
                                <div class="p-2">
                                    <h4 class="text-sm font-semibold text-gray-800hover:text-blue-600 truncate">{{ $item->name }}</h4>
                                    @if($variant)
                                        {{-- Bộ nhớ --}}
                                        <p class="text-xs text-gray-500 mt-1 min-h-[1rem]">
                                            @if($variant && $variant->storage)
                                                Bộ nhớ: {{ $variant->storage }}
                                            @endif
                                        </p>

                                        {{-- Giá và giảm giá --}}
                                        <p class="text-xs mt-1">
                                            <span class="text-red-600 font-semibold">{{ number_format($variant->price, 0, ',', '.') }}₫</span>

                                            @if($variant->original_price > $variant->price)
                                                <span class="text-gray-400 line-through ml-1 text-xs">
                                                    {{ number_format($variant->original_price, 0, ',', '.') }}₫
                                                </span>
                                                @if($variant->sale_percent)
                                                    <span class="ml-1 text-green-600 text-xs bg-green-100 px-1.5 py-0.5 rounded">
                                                        -{{ $variant->sale_percent }}%
                                                    </span>
                                                @endif
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                
                <!-- Nút điều hướng -->
                <div class="swiper-button-prev custom-swiper-btn hidden sm:flex"></div>
                <div class="swiper-button-next custom-swiper-btn hidden sm:flex"></div>

            </div>
        </div>
    @endif
</div>
{{-- style mui ten --}}
<style>
    .custom-swiper-btn {
        @apply absolute top-1/2 -translate-y-1/2 z-10 bg-gray-200/80 text-gray-800 w-10 h-10 rounded-full shadow-md flex items-center justify-center transition hover:bg-gray-300;
    }

    .swiper-button-prev.custom-swiper-btn {
        left: 0.25rem;
    }

    .swiper-button-next.custom-swiper-btn {
        right: 0.25rem;
    }

    /* Icon mũi tên bên trong */
    .custom-swiper-btn::after {
        font-size: 18px;
        font-weight: bold;
    }
</style>


{{-- Scripts --}}
<script>
    const variants = @json($product->variants);
    let selectedColor = '{{ $defaultVariant->color }}';
    let selectedStorage = '{{ $defaultVariant->storage }}';

    window.onload = () => {
        variants.forEach(v => {
            if (v.images && v.images.length > 0) {
                v.images.forEach(img => {
                    const image = new Image();
                    image.src = '/storage/' + img.image_path;
                });
            }
        });
        updateOptions();
        updateVariantDisplay();

        const toggleBtn = document.getElementById('toggleSpecBtn');
        const specWrapper = document.getElementById('techSpecWrapper');
        let expanded = false;

        toggleBtn?.addEventListener('click', function () {
            expanded = !expanded;
            specWrapper.classList.toggle('max-h-[200px]', !expanded);
            this.innerText = expanded ? 'Thu gọn' : 'Đọc thêm';
        });
    };

    function changeMainImage(src) {
        document.getElementById('previewImage').src = src;
    }

    function selectColor(btn, color) {
        selectedColor = color;
        setActiveButton('#colorOptions', color);
        updateOptions();
        updateVariantDisplay();
    }

    function selectStorage(btn, storage) {
        selectedStorage = storage;
        setActiveButton('#storageOptions', storage);
        updateOptions();
        updateVariantDisplay();
    }

    function setActiveButton(containerSelector, value) {
        const buttons = document.querySelectorAll(containerSelector + ' button');
        buttons.forEach(btn => btn.classList.remove('bg-gray-800', 'text-white', 'ring', 'ring-gray-400'));

        const activeBtn = [...buttons].find(b => b.dataset.color === value || b.dataset.storage === value);
        if (activeBtn) {
            activeBtn.classList.add('bg-gray-800', 'text-white', 'ring', 'ring-gray-400');
        }
    }

    function updateOptions() {
        const hasStorage = document.getElementById('storageOptions') !== null;

        if (hasStorage) {
            const availableStorages = variants.filter(v => v.color === selectedColor).map(v => v.storage);
            document.querySelectorAll('#storageOptions button').forEach(btn => {
                btn.classList.toggle('hidden', !availableStorages.includes(btn.dataset.storage));
            });

            if (!availableStorages.includes(selectedStorage)) {
                selectedStorage = availableStorages[0];
            }

            const availableColors = variants.filter(v => v.storage === selectedStorage).map(v => v.color);
            document.querySelectorAll('#colorOptions button').forEach(btn => {
                btn.classList.toggle('hidden', !availableColors.includes(btn.dataset.color));
            });

            if (!availableColors.includes(selectedColor)) {
                selectedColor = availableColors[0];
            }

            setActiveButton('#colorOptions', selectedColor);
            setActiveButton('#storageOptions', selectedStorage);
        } else {
            const availableColors = variants.map(v => v.color);
            document.querySelectorAll('#colorOptions button').forEach(btn => {
                btn.classList.toggle('hidden', !availableColors.includes(btn.dataset.color));
            });

            if (!availableColors.includes(selectedColor)) {
                selectedColor = availableColors[0];
            }

            setActiveButton('#colorOptions', selectedColor);
        }
    }

    function updateVariantDisplay() {
        const variant = variants.find(v => v.color === selectedColor && (!v.storage || v.storage === selectedStorage));
        if (!variant) return;

        document.getElementById('variantPrice').innerText = new Intl.NumberFormat().format(variant.price) + '₫';
        document.getElementById('formVariantId').value = variant.id;

        const originalPriceEl = document.getElementById('variantOriginalPrice');
        if (originalPriceEl) {
            if (variant.original_price && variant.original_price > variant.price) {
                originalPriceEl.classList.remove('hidden');
                originalPriceEl.innerText = new Intl.NumberFormat().format(variant.original_price) + '₫';
            } else {
                originalPriceEl.classList.add('hidden');
            }
        }

        const salePercentEl = document.getElementById('variantSalePercent');
        if (salePercentEl) {
            if (variant.sale_percent) {
                salePercentEl.classList.remove('hidden');
                salePercentEl.innerText = '-' + variant.sale_percent + '%';
            } else {
                salePercentEl.classList.add('hidden');
            }
        }

        const previewImage = document.getElementById('previewImage');
        const thumbnailWrapper = document.getElementById('thumbnailWrapper');

        if (variant.images.length > 0) {
            previewImage.src = '/storage/' + variant.images[0].image_path;
            const thumbs = variant.images.map(img => {
                return `<img src="/storage/${img.image_path}" class="w-20 h-20 object-cover rounded-lg border cursor-pointer hover:scale-105 transition" onclick="changeMainImage('/storage/${img.image_path}')">`;
            });
            thumbnailWrapper.innerHTML = thumbs.join('');
        }
    }

    function adjustQty(change) {
        const qtyInput = document.getElementById('quantity');
        let current = parseInt(qtyInput.value) || 1;
        current = Math.max(1, current + change);
        qtyInput.value = current;
        document.getElementById('formQuantity').value = current;
    }

    document.getElementById('quantity').addEventListener('input', function () {
        document.getElementById('formQuantity').value = this.value;
    });

    function buyNow() {
        const variantId = document.getElementById('formVariantId').value;
        const quantity = document.getElementById('formQuantity').value;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("cart.add") }}';

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';

        const inputVariant = document.createElement('input');
        inputVariant.type = 'hidden';
        inputVariant.name = 'variant_id';
        inputVariant.value = variantId;

        const inputQty = document.createElement('input');
        inputQty.type = 'hidden';
        inputQty.name = 'quantity';
        inputQty.value = quantity;


        const redirectInput = document.createElement('input');
        redirectInput.type = 'hidden';
        redirectInput.name = 'redirect_to_cart';
        redirectInput.value = '1'; //chuyển trang

        form.appendChild(csrf);
        form.appendChild(inputVariant);
        form.appendChild(inputQty);
        form.appendChild(redirectInput);

        document.body.appendChild(form);
        form.submit();
    }

</script>
<script>
    // Lưu vào localStorage
    let viewedProducts = JSON.parse(localStorage.getItem("recently_viewed") || "[]");

    const productId = {{ $product->id }};
    viewedProducts = viewedProducts.filter(id => id !== productId); // loại trùng
    viewedProducts.unshift(productId); // thêm đầu
    viewedProducts = viewedProducts.slice(0, 10); // giữ tối đa 10

    localStorage.setItem("recently_viewed", JSON.stringify(viewedProducts));

    // Gửi vào DB (gọi luôn cho cả khách & user)
    fetch("{{ route('recently.viewed.store', $product->id) }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": '{{ csrf_token() }}',
            "Content-Type": "application/json"
        },
        body: JSON.stringify({})
    });
</script>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    new Swiper('.swiper-container', {
        slidesPerView: 2,
        spaceBetween: 16,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            640: { slidesPerView: 3 },
            768: { slidesPerView: 4 },
            1024: { slidesPerView: 5 },
        },
    });
</script>

{{-- xu li danh gia  --}}
<script>
    const stars = document.querySelectorAll('#starRating svg');
    const ratingInput = document.getElementById('ratingInput');

    const toggleBtn = document.getElementById('toggleReviewForm');
    const reviewForm = document.getElementById('reviewForm');

    toggleBtn?.addEventListener('click', () => {
        if (reviewForm.classList.contains('hidden')) {
        reviewForm.classList.remove('hidden');
        toggleBtn.textContent = '✖ Đóng lại';
        } else {
        reviewForm.classList.add('hidden');
        toggleBtn.textContent = '✏ Viết đánh giá';
        }
    });

    stars.forEach((star, index) => {
        star.addEventListener('click', () => {
            const rating = index + 1;
            ratingInput.value = rating;

            // Highlight sao
            stars.forEach((s, i) => {
                if (i < rating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300');
                }
            });
        });
    });
</script>
<script>
    function addToCart(event) {
    event.preventDefault();

    const variantId = document.getElementById('formVariantId').value;
    const quantity = document.getElementById('formQuantity').value;

    fetch('{{ route('cart.add') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ variant_id: variantId, quantity: quantity })
    })
    .then(response => {
        return response.json().then(data => {
            if (!response.ok) throw data;
            return data;
        });
    })
    .then(data => {
        updateCartQty();

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Đã thêm vào giỏ hàng!',
                timer: 2000,
                toast: true,
                position: 'top-end',
                showConfirmButton: false
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Thêm vào giỏ thất bại!',
            text: error.error || 'Đã xảy ra lỗi, vui lòng thử lại.',
            timer: 3000,
            toast: true,
            position: 'top-end',
            showConfirmButton: false
        });
    });


    return false;
}


</script>


@endsection
