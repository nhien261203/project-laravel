@extends('layout.user')

@section('title', 'Về chúng tôi')

@section('content')

<!-- AOS CSS -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Hero banner -->
<div class="container mx-auto overflow-x-hidden">
    <section class="relative bg-fixed bg-center bg-cover text-white h-[75vh] md:h-[90vh] flex items-center justify-center" style="background-image: url('https://plus.unsplash.com/premium_photo-1661521404574-f9ec566e546c?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D')">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative z-10 text-center max-w-2xl px-4" data-aos="fade-down">
            <h1 class="text-5xl md:text-6xl font-bold mb-4 leading-tight">Chúng tôi là <span class="text-yellow-400">NexusPhone</span></h1>
            <p class="text-lg md:text-2xl">Mang công nghệ chính hãng đến tận tay bạn với giá tốt nhất</p>
        </div>
    </section>

    <!-- Giới thiệu -->
    <!-- Câu chuyện thương hiệu -->
<section class="py-24 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <!-- Hình ảnh -->
            <div data-aos="fade-right">
                <img src="https://images.unsplash.com/photo-1522199710521-72d69614c702?auto=format&fit=crop&w=870&q=80"
                     alt="Câu chuyện thương hiệu"
                     class="w-full rounded-xl shadow-lg" />
            </div>

            <!-- Nội dung -->
            <div data-aos="fade-left" class="text-gray-700">
                <h2 class="text-3xl font-bold mb-4 text-yellow-500">Câu chuyện thương hiệu</h2>
                <p class="text-lg leading-relaxed mb-4">
                    NexusPhone được khởi nguồn từ niềm đam mê công nghệ và khát khao đem đến giá trị thực sự cho người dùng Việt. 
                    Chúng tôi bắt đầu từ một cửa hàng nhỏ, nhưng luôn đặt chữ “Tín” và “Tâm” lên hàng đầu trong từng sản phẩm bán ra.
                </p>
                <p class="text-gray-600">
                    Trải qua nhiều năm phát triển, NexusPhone đã không ngừng đổi mới và mở rộng, trở thành địa chỉ đáng tin cậy của hàng chục nghìn khách hàng yêu công nghệ trên khắp cả nước.
                </p>
            </div>
        </div>
    </div>
</section>


    <!-- Tầm nhìn - Sứ mệnh - Giá trị -->
    <section class="py-20 bg-gray-50" style="background-image: url('https://media.istockphoto.com/id/1405339915/video/grey-white-tech-geometric-minimal-abstract-motion-design.jpg?s=640x640&k=20&c=eIMaHvq6aiHhkiD2GFebeHJ2UMfYR6jZg-2yeElIcl0='); background-size: cover; background-repeat: no-repeat; background-position: center;">
        
        <div class="container mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <!-- Tầm nhìn -->
                <div class="transition transform hover:scale-105 hover:-translate-y-1 duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center rounded-full bg-white/80 backdrop-blur shadow text-blue-500 text-3xl">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Tầm nhìn</h3>
                    <p class="text-gray-600">Trở thành hệ thống bán lẻ công nghệ được yêu thích nhất tại Việt Nam.</p>
                </div>

                <!-- Sứ mệnh -->
                <div class="transition transform hover:scale-105 hover:-translate-y-1 duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center rounded-full bg-white/80 backdrop-blur shadow text-green-500 text-3xl">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Sứ mệnh</h3>
                    <p class="text-gray-600">Mang đến sản phẩm chính hãng, dịch vụ tận tâm, trải nghiệm tốt nhất.</p>
                </div>

                <!-- Giá trị -->
                <div class="transition transform hover:scale-105 hover:-translate-y-1 duration-300" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center rounded-full bg-white/80 backdrop-blur shadow text-yellow-500 text-3xl">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Giá trị</h3>
                    <p class="text-gray-600">Trung thực, uy tín, lấy khách hàng làm trung tâm trong mọi hành động.</p>
                </div>
            </div>
        </div>
    </section>
    

    <!-- Cam kết -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto">
            <h2 class="text-3xl font-semibold text-center mb-10" data-aos="fade-up">Cam kết bán hàng</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                <div class="transition transform hover:scale-105 hover:-translate-y-1 duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-yellow-500 text-4xl mb-2">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <p>Sản phẩm chính hãng 100%</p>
                </div>
                <div class="transition transform hover:scale-105 hover:-translate-y-1 duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-blue-500 text-4xl mb-2">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <p>Giao hàng nhanh toàn quốc</p>
                </div>
                <div class="transition transform hover:scale-105 hover:-translate-y-1 duration-300" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-green-500 text-4xl mb-2">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <p>1 đổi 1 trong 7 ngày</p>
                </div>
                <div class="transition transform hover:scale-105 hover:-translate-y-1 duration-300" data-aos="fade-up" data-aos-delay="400">
                    <div class="text-red-500 text-4xl mb-2">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <p>Bảo hành theo chính sách hãng</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-16 bg-gradient-to-r from-yellow-200 to-yellow-100">
        <div class="container mx-auto text-center" data-aos="zoom-in">
            <h2 class="text-2xl font-semibold mb-4">Bạn cần tư vấn hoặc hỗ trợ?</h2>
            <p class="text-gray-700 mb-6">Liên hệ chúng tôi qua Fanpage, Hotline hoặc đến trực tiếp cửa hàng!</p>
            <a href="{{ route('contact.show') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-lg shadow transition">
                Liên hệ ngay
            </a>
            
        </div>
    </section>
</div>

<!-- AOS JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        AOS.init({ once: true, duration: 1000 });
    });
</script>

@endsection
