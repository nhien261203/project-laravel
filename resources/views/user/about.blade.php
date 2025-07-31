@extends('layout.user')

@section('title', 'Về chúng tôi')

@section('content')

<!-- AOS CSS -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

<!-- Hero banner (Parallax + Overlay) -->
<div class="container">
    <section class="relative bg-fixed bg-center bg-cover text-white h-[75vh] md:h-[90vh] flex items-center justify-center" style="background-image: url('https://plus.unsplash.com/premium_photo-1661521404574-f9ec566e546c?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D')">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="relative z-10 text-center max-w-2xl px-4" data-aos="fade-down">
        <h1 class="text-5xl md:text-6xl font-bold mb-4 leading-tight">Chúng tôi là <span class="text-yellow-400">NexusPhone</span></h1>
        <p class="text-lg md:text-2xl">Mang công nghệ chính hãng đến tận tay bạn với giá tốt nhất</p>
    </div>
</section>


<!-- Giới thiệu -->
<section class="py-20 bg-white">
    <div class="container mx-auto text-center max-w-3xl" data-aos="fade-up">
        <h2 class="text-3xl font-semibold mb-6">Chúng tôi là ai?</h2>
        <p class="text-gray-600 text-lg leading-relaxed">
            NexusPhone chuyên cung cấp điện thoại, laptop và phụ kiện chính hãng. Với đội ngũ tận tâm và dịch vụ uy tín, chúng tôi mang đến trải nghiệm mua sắm tuyệt vời cho khách hàng trên toàn quốc.
        </p>
    </div>
</section>

<!-- Tầm nhìn - Sứ mệnh - Giá trị -->
<section class="py-20 bg-gray-50" style="background-image: url('https://media.istockphoto.com/id/1405339915/video/grey-white-tech-geometric-minimal-abstract-motion-design.jpg?s=640x640&k=20&c=eIMaHvq6aiHhkiD2GFebeHJ2UMfYR6jZg-2yeElIcl0='); background-size: cover; background-repeat: no-repeat; background-position: center;">

    <div class="container mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <!-- Tầm nhìn -->
            <div data-aos="fade-up" data-aos-delay="100">
                <div class="w-16 h-16 mx-auto mb-4 text-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Tầm nhìn</h3>
                <p class="text-gray-600">Trở thành hệ thống bán lẻ công nghệ được yêu thích nhất tại Việt Nam.</p>
            </div>

            <!-- Sứ mệnh -->
            <div data-aos="fade-up" data-aos-delay="200">
                <div class="w-16 h-16 mx-auto mb-4 text-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 19c-2.7-1.8-4.5-4.5-5-8 .5-.6 1.5-1 3-1h.5a9 9 0 014.5 4.5V19c0 1.5-.4 2.5-1 3-.9-.3-1.9-1.1-2-2zM15 3c1.5.5 2.5 1.5 3 3 .5 1.5 0 3.3-1 4.5A8.98 8.98 0 0112 9.5V9c0-1.5.4-2.5 1-3s1.5-1 2-1z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Sứ mệnh</h3>
                <p class="text-gray-600">Mang đến sản phẩm chính hãng, dịch vụ tận tâm, trải nghiệm tốt nhất.</p>
            </div>

            <!-- Giá trị -->
            <div data-aos="fade-up" data-aos-delay="300">
                <div class="w-16 h-16 mx-auto mb-4 text-yellow-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 016.364 6.364L12 20.682 4.318 12.682a4.5 4.5 0 010-6.364z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Giá trị</h3>
                <p class="text-gray-600">Trung thực, uy tín, lấy khách hàng làm trung tâm trong mọi hành động.</p>
            </div>
        </div>
    </div>
</section>

<!-- Cam kết bán hàng -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto">
        <h2 class="text-3xl font-semibold text-center mb-10" data-aos="fade-up">Cam kết bán hàng</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
            <div data-aos="fade-up" data-aos-delay="100">
                <div class="text-yellow-500 text-4xl mb-2">✅</div>
                <p>Sản phẩm chính hãng 100%</p>
            </div>
            <div data-aos="fade-up" data-aos-delay="200">
                <div class="text-blue-500 text-4xl mb-2">🚀</div>
                <p>Giao hàng nhanh toàn quốc</p>
            </div>
            <div data-aos="fade-up" data-aos-delay="300">
                <div class="text-green-500 text-4xl mb-2">🔁</div>
                <p>1 đổi 1 trong 7 ngày</p>
            </div>
            <div data-aos="fade-up" data-aos-delay="400">
                <div class="text-red-500 text-4xl mb-2">🛡️</div>
                <p>Bảo hành theo chính sách hãng</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-16 bg-yellow-100">
    <div class="container mx-auto text-center" data-aos="zoom-in">
        <h2 class="text-2xl font-semibold mb-4">Bạn cần tư vấn hoặc hỗ trợ?</h2>
        <p class="text-gray-700 mb-6">Liên hệ chúng tôi qua Fanpage, Hotline hoặc đến trực tiếp cửa hàng!</p>
        <a href="{{ route('contact.show') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-lg shadow transition">Liên hệ ngay</a>
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
