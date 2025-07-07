<div class="bg-white my-8 p-4 rounded-lg shadow">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">🔥 Thế giới Iphone trong tầm tay</h2>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
        @foreach($iphoneProducts as $product)
            @php
                $firstVariant = $product->variants->first();
                $firstImage = optional($firstVariant?->images->first())->image_path;
                $price = $firstVariant?->price;
                $originalPrice = $firstVariant?->original_price;

                // Lấy danh sách bộ nhớ từ tất cả biến thể (giả định field là 'storage')
                $storages = $product->variants->pluck('storage')->unique()->filter()->implode(' / ');
            @endphp

            <div class="bg-white shadow rounded-xl overflow-hidden hover:shadow-lg transition">

                <a href="{{ route('product.detail', $product->slug) }}">

                    {{-- Ảnh --}}
                    @if($firstImage)
                        <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $product->name }}" class="w-full h-40 md:h-48 object-contain" loading="lazy">
                    @else
                        <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400">
                            Không có ảnh
                        </div>
                    @endif

                    {{-- Thông tin --}}
                    <div class="p-4">
                        <h3 class="text-sm font-semibold text-gray-800">{{ $product->name }}</h3>

                        {{-- Bộ nhớ --}}
                        <p class="text-xs text-gray-500 mt-1">Bộ nhớ: {{ $storages ?: 'N/A' }}</p>

                        {{-- Giá --}}
                        {{-- Giá --}}
                        @if($price)
                            <div class="mt-2">
                                <span class="text-red-500 font-bold">
                                    {{ number_format($price, 0, ',', '.') }}₫
                                </span>

                                {{-- Giá gốc (gạch) --}}
                                @if($originalPrice && $originalPrice > $price)
                                    <span class="text-sm text-gray-400 line-through ml-2">
                                        {{ number_format($originalPrice, 0, ',', '.') }}₫
                                    </span>
                                @endif

                                {{-- Phần trăm giảm giá --}}
                                @if($product->sale_percent)
                                    <span class="ml-2 text-xs text-green-600 font-semibold bg-green-100 px-2 py-0.5 rounded">
                                        -{{ $product->sale_percent }}%
                                    </span>
                                @endif

                            </div>
                        @else
                            <div class="text-sm text-gray-400 mt-2">Chưa có giá</div>
                        @endif

                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
