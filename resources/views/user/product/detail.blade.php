@extends('layout.user')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="grid md:grid-cols-2 gap-6">
        {{-- Hình ảnh sản phẩm --}}
        <div>
            @php
                $firstVariant = $product->variants->first();
                $mainImage = $firstVariant->images->first()->image_path ?? $firstVariant->fallback_image ?? null;
            @endphp
            @if ($mainImage)
                <img id="mainImage" src="{{ asset('storage/' . $mainImage) }}" alt="{{ $product->name }}" class="rounded-xl w-full">
            @endif
        </div>

        {{-- Thông tin sản phẩm --}}
        <div>
            <h1 class="text-2xl font-bold mb-4">{{ $product->name }}</h1>

            {{-- Giá và khuyến mãi --}}
            <div class="mb-4">
                <p class="text-gray-700 text-lg">Giá:
                    <span id="priceDisplay" class="text-red-500 font-bold">{{ number_format($firstVariant->price) }} đ</span>
                </p>
                <p id="originalPriceDisplay" class="line-through text-sm text-gray-400">
                    @if ($firstVariant->original_price && $firstVariant->original_price > $firstVariant->price)
                        {{ number_format($firstVariant->original_price) }} đ
                    @endif
                </p>
            </div>

            {{-- Màu sắc --}}
            <div class="mb-4">
                <p class="font-semibold mb-2">Màu sắc:</p>
                <div class="flex gap-2">
                    @foreach ($colors as $color)
                        <button class="color-btn border px-3 py-1 rounded hover:bg-gray-100" data-color="{{ $color }}">{{ $color }}</button>
                    @endforeach
                </div>
            </div>

            {{-- Bộ nhớ --}}
            <div class="mb-4">
                <p class="font-semibold mb-2">Bộ nhớ:</p>
                <div class="flex gap-2">
                    @foreach ($storages as $storage)
                        <button class="storage-btn border px-3 py-1 rounded hover:bg-gray-100" data-storage="{{ $storage }}">{{ $storage }}</button>
                    @endforeach
                </div>
            </div>

            {{-- Nút hành động --}}
            <div class="mt-6 flex gap-4">
                <button class="bg-blue-600 text-white px-6 py-2 rounded-lg">🛒 Thêm vào giỏ</button>
                <button class="bg-green-600 text-white px-6 py-2 rounded-lg">⚡ Mua ngay</button>
            </div>
        </div>
    </div>

    {{-- Danh sách biến thể --}}
    <div class="mt-10">
        <h2 class="text-xl font-bold mb-4">Các phiên bản:</h2>
        <div class="grid md:grid-cols-3 gap-4">
            @foreach ($product->variants as $variant)
                <div class="border p-4 rounded-lg shadow">
                    @php
                        $image = $variant->images->first()->image_path ?? $variant->fallback_image ?? null;
                    @endphp
                    @if ($image)
                        <img src="{{ asset('storage/' . $image) }}" alt="" class="rounded mb-2 w-full">
                    @endif
                    <p class="font-semibold">{{ $variant->color }} / {{ $variant->storage }}</p>
                    <p class="text-red-500 font-bold">{{ number_format($variant->price) }} đ</p>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Script biến thể & xử lý JS --}}
<script>
    const variants = @json($product->variants);
    let selectedColor = null;
    let selectedStorage = null;

    document.querySelectorAll('.color-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            selectedColor = btn.dataset.color;
            highlightSelected('.color-btn', btn);
            updateVariantDisplay();
        });
    });

    document.querySelectorAll('.storage-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            selectedStorage = btn.dataset.storage;
            highlightSelected('.storage-btn', btn);
            updateVariantDisplay();
        });
    });

    function highlightSelected(selector, selectedBtn) {
        document.querySelectorAll(selector).forEach(btn => btn.classList.remove('bg-gray-200'));
        selectedBtn.classList.add('bg-gray-200');
    }

    function updateVariantDisplay() {
        if (!selectedColor || !selectedStorage) return;

        const match = variants.find(v =>
            v.color === selectedColor && v.storage === selectedStorage
        );

        if (match) {
            const price = new Intl.NumberFormat().format(match.price) + ' đ';
            document.getElementById('priceDisplay').textContent = price;

            if (match.original_price && match.original_price > match.price) {
                document.getElementById('originalPriceDisplay').textContent =
                    new Intl.NumberFormat().format(match.original_price) + ' đ';
            } else {
                document.getElementById('originalPriceDisplay').textContent = '';
            }

            const image = match.images?.[0]?.image_path ?? match.fallback_image;
            if (image) {
                document.getElementById('mainImage').src = `/storage/${image}`;
            }
        }
    }
</script>
@endsection
