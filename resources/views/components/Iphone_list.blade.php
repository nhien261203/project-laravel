{{-- resources/views/user/product/allIphone_list.blade.php --}}
@if($products->count())
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-6">
        @foreach($products as $product)
            @php
                $firstVariant = $product->variants->first();
                $image = optional($firstVariant?->images->first())->image_path;
                $price = $firstVariant?->price;
                $originalPrice = $firstVariant?->original_price;
            @endphp

            <a href="{{ route('product.detail', $product->slug) }}"
               class="group bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition hover:border-blue-400 relative">
               
                {{-- Ảnh + badge sale --}}
                <div class="relative w-full h-40 md:h-44 bg-white flex items-center justify-center">
                    @if($image)
                        <img src="{{ asset('storage/' . $image) }}" 
                             alt="{{ $product->name }}"
                             class="max-h-full max-w-full object-contain p-2 mt-5">
                    @else
                        <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
                            Không có ảnh
                        </div>
                    @endif

                    @if($product->sale_percent > 0)
                        <span class="absolute top-1 right-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded shadow">
                            -{{ $product->sale_percent }}%
                        </span>
                    @endif
                </div>

                {{-- Nội dung --}}
                <div class="p-4">
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-blue-600 truncate">
                        {{ $product->name }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">{{ $product->all_storages ?? 'N/A' }}</p>

                    @if($price)
                        <div class="mt-2 min-h-[3rem]">
                            <span class="text-red-500 font-bold">
                                {{ number_format($price, 0, ',', '.') }}₫
                            </span>
                            @if($originalPrice && $originalPrice > $price)
                                <span class="text-xs text-gray-400 line-through ml-2">
                                    {{ number_format($originalPrice, 0, ',', '.') }}₫
                                </span>
                            @endif
                        </div>
                    @else
                        <div class="text-sm text-gray-400 mt-2">Chưa có giá</div>
                    @endif
                </div>

                {{-- Nút so sánh --}}
                <button
                    type="button"
                    onclick="event.stopPropagation(); event.preventDefault(); addToCompare({{ $product->id }}, '{{ request()->segment(1) }}')"
                    class="absolute bottom-2 right-2 w-6 h-6 flex items-center justify-center rounded-full border border-blue-500 text-blue-600 hover:bg-blue-500 hover:text-white transition"
                    title="Thêm vào so sánh"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
            </a>
        @endforeach
    </div>
@else
    <p class="text-gray-500 mt-4">Không tìm thấy sản phẩm nào phù hợp với bộ lọc.</p>
@endif
