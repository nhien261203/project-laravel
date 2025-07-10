@extends('layout.user')

@section('content')
<div class="container pt-24 pb-10 bg-white rounded shadow">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Hình ảnh --}}
        <div>
            @php
                $defaultVariant = $product->variants->first();
                $defaultImages = $defaultVariant->images;
                $fallbackImages = $product->variants->firstWhere(fn($v) => !$v->images->isEmpty())?->images;
            @endphp

            <div id="mainImage" class="border rounded-lg overflow-hidden shadow-md">
                @if($defaultImages->count())
                    <img src="{{ asset('storage/' . $defaultImages->first()->image_path) }}" class="w-full h-80 md:h-[420px] object-contain bg-white" id="previewImage">
                @elseif($fallbackImages && $fallbackImages->count())
                    <img src="{{ asset('storage/' . $fallbackImages->first()->image_path) }}" class="w-full h-80 md:h-[420px] object-contain bg-white" id="previewImage">
                @else
                    <div class="w-full h-80 bg-gray-100 flex items-center justify-center text-gray-400 text-sm">Không có ảnh</div>
                @endif
            </div>

            {{-- Ảnh nhỏ --}}
            <div class="flex mt-4 gap-3 overflow-x-auto pb-2" id="thumbnailWrapper">
                @foreach(($defaultImages->count() ? $defaultImages : $fallbackImages ?? []) as $img)
                    <img src="{{ asset('storage/' . $img->image_path) }}" class="w-20 h-20 object-cover rounded-lg border cursor-pointer hover:scale-105 transition" onclick="changeMainImage('{{ asset('storage/' . $img->image_path) }}')">
                @endforeach
            </div>
        </div>

        {{-- Thông tin --}}
        <div class="px-2">
            <h1 class="text-xl md:text-2xl font-bold mb-3">{{ $product->name }}</h1>

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
                @if($defaultVariant->sale_percent)
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
                        <button class="color-option px-4 py-1 border rounded-md text-sm hover:bg-gray-100" data-color="{{ $color }}" onclick="selectColor(this, '{{ $color }}')">
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
                            <button class="storage-option px-4 py-1 border rounded-md text-sm hover:bg-gray-100" data-storage="{{ $storage }}" onclick="selectStorage(this, '{{ $storage }}')">
                                {{ $storage }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Số lượng --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Số lượng:</label>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="adjustQty(-1)" class="px-3 py-1 bg-gray-200 rounded">−</button>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" class="w-16 text-center border rounded">
                    <button type="button" onclick="adjustQty(1)" class="px-3 py-1 bg-gray-200 rounded">+</button>
                </div>
            </div>

            {{-- Nút hành động --}}
            <form method="POST" action="{{ route('cart.add') }}" class="flex gap-3 items-center">
                @csrf
                <input type="hidden" name="variant_id" id="formVariantId" value="{{ $defaultVariant->id }}">
                <input type="hidden" name="quantity" id="formQuantity" value="1">

                <button type="submit" class="bg-yellow-500 text-white px-6 py-2 rounded hover:bg-yellow-600">
                    🛒 Thêm vào giỏ
                </button>

                <button type="button" onclick="buyNow()" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    ⚡ Mua ngay
                </button>
            </form>
        </div>
    </div>

    {{-- Mô tả sản phẩm --}}
    <div class="mt-6 md:w-1/2 w-full">
        <h3 class="text-base font-semibold text-gray-700 mb-2">📘 Mô tả sản phẩm</h3>

        <div id="techSpecWrapper" class="overflow-hidden transition-all duration-300 max-h-[200px]">
            <div class="prose max-w-none text-sm text-gray-800">
                {!! $product->description !!}
            </div>
        </div>

        <button id="toggleSpecBtn" class="mt-3 p-2 rounded-lg text-blue-600 bg-gray-200 text-sm font-medium focus:outline-none">
            Đọc thêm
        </button>
    </div>
</div>

{{-- Scripts --}}
<script>
    const variants = @json($product->variants);
    let selectedColor = '{{ $defaultVariant->color }}';
    let selectedStorage = '{{ $defaultVariant->storage }}';

    window.onload = () => {
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

        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="variant_id" value="${variantId}">
            <input type="hidden" name="quantity" value="${quantity}">
        `;

        document.body.appendChild(form);
        form.submit();
    }
</script>
@endsection
