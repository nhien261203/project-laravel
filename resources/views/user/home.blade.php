@extends('layout.user')

@section('title', 'Trang chủ - Nexus')

@section('content')
    {{-- Hiển thị Banner nếu có --}}
    @isset($banners)
        @include('components.banner', ['banners' => $banners])
    @endisset

    <div class="container">
        {{-- section category card --}}
        {{-- <div class="my-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @include('components.category-card', [
                    
                    'title' => 'Điện thoại',
                    'image' => 'https://images.pexels.com/photos/1398339/pexels-photo-1398339.jpeg',
                    //'image' => asset('storage/card/phone-img.webp'),
                    'link' => route('product.phone') 
                ])

                @include('components.category-card', [
                    'title' => 'Laptop',
                    'image' => 'https://images.pexels.com/photos/3975680/pexels-photo-3975680.jpeg',
                    //'image' => asset('storage/card/lap.webp'),
                    'link' => route('product.laptop')
                ])

                @include('components.category-card', [
                    'title' => 'Đồng hồ',
                    'image' => 'https://images.pexels.com/photos/32864808/pexels-photo-32864808.jpeg',
                    'link' => '#'
                ])

                @include('components.category-card', [
                    'title' => 'Phụ kiện di động',
                    'image' => 'https://images.pexels.com/photos/3183132/pexels-photo-3183132.jpeg',
                    // 'image' => asset('storage/card/phu-kien.webp'),
                    'link' => route('product.accessory.mobile')
                ])

                @include('components.category-card', [
                    'title' => 'Phụ kiện âm thanh',
                    'image' => 'https://images.pexels.com/photos/32880383/pexels-photo-32880383.jpeg',
                    //'image' => asset('storage/card/am-thanh.webp'),
                    'link' => route('product.accessory.audio')
                ])

            </div>
        </div> --}}
        <div class="my-10 bg-white rounded-2xl shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Danh mục nổi bật</h2>

    {{-- Desktop: Flex tự giãn --}}
    <div class="hidden md:flex flex-wrap justify-center gap-10">
    <!-- Item -->
    <div class="flex-1 flex flex-col items-center justify-center text-center cursor-pointer">
        <a href="{{ route('product.phone') }}" class="group block p-4 rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg">
            <img src="https://cdn2.fptshop.com.vn/unsafe/360x0/filters:format(webp):quality(75)/dien_thoai_ic_cate_6dbc1f8296.png" 
                alt="Điện thoại" 
            class="w-20 h-20 md:w-28 md:h-28 object-contain mb-3 transition-transform duration-300 group-hover:rotate-3 group-hover:scale-110">
            <p class="text-sm font-medium transition-colors duration-300 group-hover:text-blue-600">Điện thoại</p>
        </a>
        
    </div>

    <!-- Item -->
    {{-- <div class="flex-1 flex flex-col items-center justify-center text-center cursor-pointer">
        <img src="https://cdn2.fptshop.com.vn/unsafe/360x0/filters:format(webp):quality(75)/may_tinh_bang_ic_cate_dccb57ff5c.png" 
             alt="Máy tính bảng" 
             class="w-20 h-20 md:w-28 md:h-28 object-contain mb-3">
        <p class="text-sm font-medium">Máy tính bảng</p>
    </div> --}}

    <!-- Item -->
    <div class="flex-1 flex flex-col items-center justify-center text-center cursor-pointer">
        <a href="{{ route('product.laptop') }}" 
        class="group block p-4 rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg">
        
            <img src="https://cdn2.fptshop.com.vn/unsafe/360x0/filters:format(webp):quality(75)/laptop_ic_cate_47e7264bc7.png" 
                alt="Laptop" 
                class="w-20 h-20 md:w-28 md:h-28 object-contain mb-3 transition-transform duration-300 group-hover:rotate-3 group-hover:scale-110">

            <p class="text-sm font-medium transition-colors duration-300 group-hover:text-blue-600">
                Laptop
            </p>
        </a>
    </div>
    <div class="flex-1 flex flex-col items-center justify-center text-center cursor-pointer">
        <a href="{{ route('product.accessory') }}"
        class="group block p-4 rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg">
            <img src="https://cdn2.fptshop.com.vn/unsafe/360x0/filters:format(webp):quality(75)/phu_kien_ic_cate_ecae8ddd38.png" 
                alt="Phụ kiện" 
                class="w-20 h-20 md:w-28 md:h-28 object-contain mb-3 transition-transform duration-300 group-hover:rotate-3 group-hover:scale-110">
            <p class="text-sm font-medium transition-colors duration-300 group-hover:text-blue-600">Phụ kiện</p>
        </a>
        
    </div>

    <!-- Item -->
    <div class="flex-1 flex flex-col items-center justify-center text-center cursor-pointer">
        <a href="{{ route('product.accessory.mobile') }}" class="group block p-4 rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg">
            <img src="https://cdn2.fptshop.com.vn/unsafe/360x0/filters:format(webp):quality(75)/2023_3_29_638157050273684176_HASP-PIN-SAC-DU-PHONG-MAGSAFE-INNOSTYLE-POWERMAG-SWITCH-2IN1-20W-DEN-AVT.jpg" 
                alt="Phụ kiện di động" 
                class="w-20 h-20 md:w-28 md:h-28 object-contain mb-3 transition-transform duration-300 group-hover:rotate-3 group-hover:scale-110">
            <p class="text-sm font-medium transition-colors duration-300 group-hover:text-blue-600">Phụ kiện di động</p>
        </a>
        
    </div>

    <!-- Item -->
    <div class="flex-1 flex flex-col items-center justify-center text-center cursor-pointer">
        <a href="{{ route('product.accessory.audio') }}" class="group block p-4 rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg">
            <img src="https://cdn2.fptshop.com.vn/unsafe/750x0/filters:format(webp):quality(75)/tai_nghe_bluetooth_nhet_tai_jbl_tune_beam_2_den_10_c53899f5d2.png" 
            alt="Thiết bị âm thanh" 
            class="w-20 h-20 md:w-28 md:h-28 object-contain mb-3 transition-transform duration-300 group-hover:rotate-3 group-hover:scale-110">
            <p class="text-sm font-medium transition-colors duration-300 group-hover:text-blue-600">Thiết bị âm thanh</p>
        </a>
        
    </div>
</div>


    {{-- Mobile: Swiper --}}
    <div class="md:hidden">
        <div class="swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide flex flex-col items-center text-center cursor-pointer">
                    <a href="{{ route('product.phone') }}">
                        <img src="https://cdn2.fptshop.com.vn/unsafe/360x0/filters:format(webp):quality(75)/dien_thoai_ic_cate_6dbc1f8296.png" alt="Điện thoại" class="w-17 h-17 object-contain mb-2">
                        <p class="text-sm font-medium">Điện thoại</p>
                    </a>
                    
                </div>
                {{-- <div class="swiper-slide flex flex-col items-center text-center cursor-pointer">
                    <img src="https://cdn2.fptshop.com.vn/unsafe/360x0/filters:format(webp):quality(75)/may_tinh_bang_ic_cate_dccb57ff5c.png" alt="Máy tính bảng" class="w-16 h-16 object-contain mb-2">
                    <p class="text-sm font-medium">Máy tính bảng</p>
                </div> --}}
                <div class="swiper-slide flex flex-col items-center text-center cursor-pointer">
                    <a href="{{ route('product.laptop') }}">
                        <img src="https://cdn2.fptshop.com.vn/unsafe/360x0/filters:format(webp):quality(75)/laptop_ic_cate_47e7264bc7.png" alt="Laptop" class="w-17 h-17 object-contain mb-2">
                        <p class="text-sm font-medium">Laptop</p>
                    </a>
                    
                </div>
                {{-- <div class="swiper-slide flex flex-col items-center text-center cursor-pointer">
                    <img src="https://cdn2.fptshop.com.vn/unsafe/360x0/filters:format(webp):quality(75)/laptop_ic_cate_47e7264bc7.png" alt="Màn hình" class="w-16 h-16 object-contain mb-2">
                    <p class="text-sm font-medium">Màn hình</p>
                </div> --}}
                <div class="swiper-slide flex flex-col items-center text-center cursor-pointer">
                    <a href="{{ route('product.accessory') }}">
                        <img src="https://cdn2.fptshop.com.vn/unsafe/360x0/filters:format(webp):quality(75)/phu_kien_ic_cate_ecae8ddd38.png" alt="Phụ kiện" class="w-17 h-17 object-contain mb-2">
                        <p class="text-sm font-medium">Phụ kiện</p>
                    </a>
                    
                </div>
                <div class="swiper-slide flex flex-col items-center text-center cursor-pointer">
                    <a href="{{ route('product.accessory.mobile') }}">
                        <img src="https://cdn2.fptshop.com.vn/unsafe/360x0/filters:format(webp):quality(75)/2023_3_29_638157050273684176_HASP-PIN-SAC-DU-PHONG-MAGSAFE-INNOSTYLE-POWERMAG-SWITCH-2IN1-20W-DEN-AVT.jpg" alt="Phụ kiện di động" class="w-17 h-17 object-contain mb-2">
                        <p class="text-sm font-medium">Phụ kiện di động</p>
                    </a>
                    
                </div>
                <div class="swiper-slide flex flex-col items-center text-center cursor-pointer">
                    <a href="{{ route('product.accessory.audio') }}">
                        <img src="https://cdn2.fptshop.com.vn/unsafe/750x0/filters:format(webp):quality(75)/tai_nghe_bluetooth_nhet_tai_jbl_tune_beam_2_den_10_c53899f5d2.png" alt="Thiết bị âm thanh" class="w-17 h-17 object-contain mb-2">
                        <p class="text-sm font-medium">Thiết bị âm thanh</p>
                    </a>
                    
                </div>
                {{-- <div class="swiper-slide flex flex-col items-center text-center cursor-pointer">
                    <div class="w-17 h-17 flex items-center justify-center mb-2">
                        <img src="https://cdn2.fptshop.com.vn/unsafe/750x0/filters:format(webp):quality(75)/tai_nghe_bluetooth_nhet_tai_jbl_tune_beam_2_den_10_c53899f5d2.png" alt="Thiết bị âm thanh" class="max-w-full max-h-full object-contain">
                    </div>
                    <p class="text-sm font-medium">Thiết bị âm thanh</p>
                </div> --}}

                {{-- <div class="swiper-slide flex flex-col items-center text-center cursor-pointer">
                    <img src="/images/old-phone.png" alt="Máy cũ giá rẻ" class="w-16 h-16 object-contain mb-2">
                    <p class="text-sm font-medium">Máy cũ giá rẻ</p>
                </div> --}}
            </div>
        </div>
    </div>
        </div>

        {{-- section iphone card --}}
        @isset($iphoneProducts)
            @include('components.iphone-section', ['iphoneProducts' => $iphoneProducts])
        @endisset


        {{-- section laptop card --}}
        @isset($laptopProducts)
            @include('components.laptop-section', ['laptopProducts' => $laptopProducts])
        @endisset

        @isset($latestBlogs)
            @include('components.blog-section', ['blogs' => $latestBlogs])
        @endisset

        @isset($accessoryProducts)
            @include('components.accessory-section', ['accessoryProducts' => $accessoryProducts])
        @endisset

    </div>

    {{-- Nội dung khác --}}
    {{-- <div class="bg-white p-6 rounded shadow mt-10">
        <h2 class="text-2xl font-bold mb-4">👋 Chào mừng bạn đến Nexus</h2>
        <p class="text-gray-700">Đây là trang chủ. Bạn có thể xem sản phẩm mới nhất, danh mục nổi bật, và nhiều hơn.</p>
    </div> --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    new Swiper('.swiper', {
        slidesPerView: 3.5,
        // loop: true,
        spaceBetween: 16,
    });
</script>
@endsection
