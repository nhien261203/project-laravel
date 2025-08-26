@extends('layout.user')

@section('content')
<div class="container mx-auto pt-20 pb-10 overflow-x-hidden">
    {{-- Breadcrumb --}}
    <div class="flex items-center text-sm text-gray-600 space-x-2 mb-4">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Trang chủ</a>
        <span class="text-gray-400">›</span>
        <span class="text-gray-800 font-medium">Yêu thích</span>
    </div>

    <div class="bg-white p-6 rounded-xl shadow space-y-6 overflow-hidden">
        @if($favorites->count())
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($favorites as $favorite)
                    @php
                        $product = $favorite->product;
                        $variant = $favorite->variant ?? $product->variants->first();
                        $image = optional($variant?->images->first())->image_path;
                        $price = $variant?->price;
                        $originalPrice = $variant?->original_price;
                        $salePercent = $variant?->sale_percent;
                        $storages = $product->variants->pluck('storage')->unique()->implode(', ');
                    @endphp

                    @php
                        $isAccessory = $product->category?->slug === 'phu-kien' 
                            || $product->category?->parent?->slug === 'phu-kien';
                    @endphp

                    <a href="{{ $isAccessory ? route('product.detailAccessory', $product->slug) : route('product.detail', $product->slug) }}"

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

                            @if($salePercent > 0)
                                <span class="absolute top-1 right-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded shadow">
                                    -{{ $salePercent }}%
                                </span>
                            @endif

                        </div>

                        {{-- Nội dung --}}
                        <div class="p-4">
                            <h3 class="text-sm font-semibold text-gray-800 group-hover:text-blue-600 truncate">
                                {{ $product->name }}
                            </h3>
                            
                            @if(!empty($storages))
                                <p class="text-xs text-gray-500 mt-1">{{ $storages }}</p>
                            @endif

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

                        {{-- Nút xóa khỏi yêu thích --}}
                        <button
                            type="button"
                            onclick="event.stopPropagation(); event.preventDefault(); removeFavorite({{ $product->id }}, this)"
                            class="absolute top-2 left-2 w-7 h-7 flex items-center justify-center rounded-full border border-red-500 bg-white text-red-500 hover:bg-red-500 hover:text-white transition favorite-btn text-red-500"
                            data-product-id="{{ $product->id }}"
                            title="Xóa khỏi yêu thích"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 
                                2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 
                                3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 
                                3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        </button>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 mt-4">Bạn chưa có sản phẩm nào trong danh sách yêu thích.</p>
        @endif
    </div>
    {{-- <div class="mt-4 flex justify-center">
    
        {{ $favorites->appends(request()->except('page'))->links('pagination.custom-user') }}
    </div> --}}
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
async function removeFavorite(productId, btn) {
    try {
        const res = await fetch(`/favorites/by-product/${productId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });

        if (res.ok) {
            // Xóa sản phẩm khỏi DOM
            const card = btn.closest('a'); // phần tử <a> bao ngoài button
            card.remove();

            // Thông báo
            Swal.fire({
                icon: 'success',
                title: 'Đã xóa khỏi yêu thích',
                toast: true,
                position: 'top-end',
                timer: 2000,
                showConfirmButton: false
            });

            // Nếu không còn sản phẩm nào thì hiển thị thông báo trống
            if (document.querySelectorAll('.favorite-btn').length === 0) {
                document.querySelector('.bg-white.p-6').innerHTML = 
                    '<p class="text-gray-500 mt-4">Bạn chưa có sản phẩm nào trong danh sách yêu thích.</p>';
            }
        } else {
            Swal.fire({icon: 'error', title: 'Lỗi', text: 'Không thể xóa sản phẩm!'});
        }
    } catch (err) {
        console.error(err);
        Swal.fire({icon: 'error', title: 'Lỗi', text: 'Có lỗi xảy ra khi xóa sản phẩm!'});
    }
}
</script>
@endpush
