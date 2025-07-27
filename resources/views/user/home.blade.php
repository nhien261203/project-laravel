@extends('layout.user')

@section('title', 'Trang chủ - Nexus')

@section('content')
    {{-- Hiển thị Banner nếu có --}}
    @isset($banners)
        @include('components.banner', ['banners' => $banners])
    @endisset

    <div class="container">
        {{-- section category card --}}
        <div class="my-10">
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

                {{-- @include('components.category-card', [
                    'title' => 'Đồng hồ',
                    'image' => 'https://images.pexels.com/photos/32864808/pexels-photo-32864808.jpeg',
                    'link' => '#'
                ]) --}}

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
@endsection
