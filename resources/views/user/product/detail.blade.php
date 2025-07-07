@extends('layout.user')

@section('content')
<div class="container py-6 bg-white rounded shadow">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Hình ảnh --}}
        <div>
            @php
                $defaultVariant = $product->variants->first();
                $defaultImages = $defaultVariant->images;
                $fallbackImages = $product->variants->firstWhere(fn($v) => !$v->images->isEmpty())?->images;
            @endphp

            {{-- Ảnh chính --}}
            <div id="mainImage" class="border rounded-lg overflow-hidden shadow-md">
                @if($defaultImages->count())
                    <img src="{{ asset('storage/' . $defaultImages->first()->image_path) }}" class="w-full h-80 md:h-[420px] object-contain bg-white" id="previewImage">
                @elseif($fallbackImages && $fallbackImages->count())
                    <img src="{{ asset('storage/' . $fallbackImages->first()->image_path) }}" class="w-full h-80 md:h-[420px] object-contain bg-white" id="previewImage">
                @else
                    <div class="w-full h-80 bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
                        Không có ảnh
                    </div>
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

                @if($defaultVariant->original_price && $defaultVariant->original_price > $defaultVariant->price)
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

            {{-- Bộ nhớ --}}
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

            {{-- Thông tin kỹ thuật --}}
            <div class="mt-5 bg-gray-100 p-4 rounded-lg space-y-1 text-sm md:text-base" id="variantDetails">
                <p><strong>Màu:</strong> <span id="detailColor">{{ $defaultVariant->color }}</span></p>
                <p><strong>Bộ nhớ:</strong> <span id="detailStorage">{{ $defaultVariant->storage }}</span></p>
                <p><strong>Màn hình:</strong> <span id="detailScreen">{{ $defaultVariant->screen }}</span></p>
                <p><strong>Chip:</strong> <span id="detailChip">{{ $defaultVariant->chip }}</span></p>
                <p><strong>Pin:</strong> <span id="detailBattery">{{ $defaultVariant->battery }}</span></p>
                <p><strong>Hệ điều hành:</strong> <span id="detailOS">{{ $defaultVariant->os }}</span></p>
                <p><strong>Khối lượng:</strong> <span id="detailWeight">{{ $defaultVariant->weight }}</span></p>
            </div>
        </div>
    </div>
</div>

{{-- Script --}}
<script>
    const variants = @json($product->variants);
    let selectedColor = '{{ $defaultVariant->color }}';
    let selectedStorage = '{{ $defaultVariant->storage }}';

    window.onload = () => {
        updateOptions();
        updateVariantDisplay();
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
        buttons.forEach(btn => {
            btn.classList.remove('bg-gray-800', 'text-white', 'ring', 'ring-gray-400');
        });
        const activeBtn = [...buttons].find(b => b.dataset.color === value || b.dataset.storage === value);
        if (activeBtn) {
            activeBtn.classList.add('bg-gray-800', 'text-white', 'ring', 'ring-gray-400');
        }
    }

    function updateOptions() {
        const availableStorages = variants.filter(v => v.color === selectedColor).map(v => v.storage);
        const storageButtons = document.querySelectorAll('#storageOptions button');
        storageButtons.forEach(btn => {
            btn.classList.toggle('hidden', !availableStorages.includes(btn.dataset.storage));
        });
        if (!availableStorages.includes(selectedStorage)) {
            selectedStorage = availableStorages[0];
        }

        const availableColors = variants.filter(v => v.storage === selectedStorage).map(v => v.color);
        const colorButtons = document.querySelectorAll('#colorOptions button');
        colorButtons.forEach(btn => {
            btn.classList.toggle('hidden', !availableColors.includes(btn.dataset.color));
        });
        if (!availableColors.includes(selectedColor)) {
            selectedColor = availableColors[0];
        }

        setActiveButton('#colorOptions', selectedColor);
        setActiveButton('#storageOptions', selectedStorage);
    }

    function updateVariantDisplay() {
        const variant = variants.find(v => v.color === selectedColor && v.storage === selectedStorage);
        if (!variant) return;

        // Giá bán
        document.getElementById('variantPrice').innerText = new Intl.NumberFormat().format(variant.price) + '₫';

        // Giá gốc
        const originalPriceEl = document.getElementById('variantOriginalPrice');
        if (variant.original_price && variant.original_price > variant.price) {
            if (originalPriceEl) {
                originalPriceEl.innerText = new Intl.NumberFormat().format(variant.original_price) + '₫';
                originalPriceEl.classList.remove('hidden');
            } else {
                const el = document.createElement('span');
                el.id = 'variantOriginalPrice';
                el.className = 'text-gray-400 line-through text-sm ml-2';
                el.innerText = new Intl.NumberFormat().format(variant.original_price) + '₫';
                document.getElementById('variantPrice').after(el);
            }
        } else if (originalPriceEl) {
            originalPriceEl.classList.add('hidden');
        }

        // % Sale
        const salePercentEl = document.getElementById('variantSalePercent');
        if (variant.sale_percent && variant.sale_percent > 0) {
            if (salePercentEl) {
                salePercentEl.innerText = '-' + variant.sale_percent + '%';
                salePercentEl.classList.remove('hidden');
            } else {
                const el = document.createElement('span');
                el.id = 'variantSalePercent';
                el.className = 'text-xs text-green-600 font-semibold bg-green-100 px-2 py-0.5 rounded ml-2';
                el.innerText = '-' + variant.sale_percent + '%';
                document.getElementById('variantPrice').after(el);
            }
        } else if (salePercentEl) {
            salePercentEl.classList.add('hidden');
        }

        // Chi tiết kỹ thuật
        document.getElementById('detailColor').innerText = variant.color ?? '';
        document.getElementById('detailStorage').innerText = variant.storage ?? '';
        document.getElementById('detailScreen').innerText = variant.screen ?? '';
        document.getElementById('detailChip').innerText = variant.chip ?? '';
        document.getElementById('detailBattery').innerText = variant.battery ?? '';
        document.getElementById('detailOS').innerText = variant.os ?? '';
        document.getElementById('detailWeight').innerText = variant.weight ?? '';

        // Ảnh
        const previewImage = document.getElementById('previewImage');
        const thumbnailWrapper = document.getElementById('thumbnailWrapper');

        if (variant.images.length > 0) {
            previewImage.src = '/storage/' + variant.images[0].image_path;
            const thumbs = variant.images.map(img => {
                return `<img src="/storage/${img.image_path}" class="w-20 h-20 object-cover rounded-lg border cursor-pointer hover:scale-105 transition" onclick="changeMainImage('/storage/${img.image_path}')">`;
            });
            thumbnailWrapper.innerHTML = thumbs.join('');
        } else if (variant.fallback_image) {
            previewImage.src = '/storage/' + variant.fallback_image;

            const fallback = variants.find(v => v.images.length > 0);
            if (fallback) {
                const thumbs = fallback.images.map(img => {
                    return `<img src="/storage/${img.image_path}" class="w-20 h-20 object-cover rounded-lg border cursor-pointer hover:scale-105 transition" onclick="changeMainImage('/storage/${img.image_path}')">`;
                });
                thumbnailWrapper.innerHTML = thumbs.join('');
            }
        }
    }
</script>
@endsection
