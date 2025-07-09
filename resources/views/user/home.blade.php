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
                    'image' => 'https://cdn.mobilecity.vn/mobilecity-vn/images/2024/12/dien-thoai-chup-anh-dep-nhat-2025-2.jpg.webp',
                    'link' => route('product.phone') 
                ])

                @include('components.category-card', [
                    'title' => 'Laptop',
                    'image' => 'https://images.pexels.com/photos/3975680/pexels-photo-3975680.jpeg',
                    'link' => route('product.laptop')
                ])

                {{-- @include('components.category-card', [
                    'title' => 'Đồng hồ',
                    'image' => 'https://images.pexels.com/photos/32864808/pexels-photo-32864808.jpeg',
                    'link' => '#'
                ]) --}}

                @include('components.category-card', [
                    'title' => 'Phụ kiện',
                    'image' => 'https://images.pexels.com/photos/3183132/pexels-photo-3183132.jpeg',
                    'link' => '#'
                ])

                @include('components.category-card', [
                    'title' => 'Về chúng tôi',
                    'image' => 'https://images.pexels.com/photos/4158/apple-iphone-smartphone-desk.jpg',
                    'link' => '#'
                ])


            </div>
        </div>

        {{-- section iphone card --}}
        @isset($iphoneProducts)
            @include('components.iphone-section', ['iphoneProducts' => $iphoneProducts])
        @endisset
    </div>

    {{-- Nội dung khác --}}
    <div class="bg-white p-6 rounded shadow mt-10">
        <h2 class="text-2xl font-bold mb-4">👋 Chào mừng bạn đến Nexus</h2>
        <p class="text-gray-700">Đây là trang chủ. Bạn có thể xem sản phẩm mới nhất, danh mục nổi bật, và nhiều hơn.</p>
    </div>
@endsection
