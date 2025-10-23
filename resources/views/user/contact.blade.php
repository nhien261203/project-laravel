@extends('layout.user')
@section('content')

<div class="container pt-10 pb-20">
    <section class="py-16 bg-gray-50 rounded-xl hover:shadow-2xl" data-aos="fade-up">
        <div class="container mx-auto px-4 grid md:grid-cols-2 gap-10 animate-fadeIn">
            
            {{-- Contact Info --}}
            <div class="pt-0 md:pt-28">
                <h2 class="text-2xl font-bold mb-4 text-blue-700">Bạn cần NEXUSPHONE hỗ trợ? Liên hệ với chúng tôi</h2>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Nếu bạn có bất kỳ câu hỏi hoặc cần hỗ trợ, hãy liên hệ với chúng tôi qua biểu mẫu hoặc thông tin dưới đây. 
                    Chúng tôi luôn sẵn sàng hỗ trợ bạn nhanh chóng và tận tâm.
                </p>

                
            </div>

            {{-- Contact Form --}}
            <div class="bg-white p-8 rounded-xl shadow-xl hover:shadow-2xl transition duration-300">
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Họ tên --}}
                    <div>
                        <input name="name" type="text" placeholder="Họ tên"
                            value="{{ old('name', auth()->user()->name ?? '') }}"
                            class="w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <input name="email" type="email" placeholder="Email"
                            value="{{ old('email', auth()->user()->email ?? '') }}"
                            class="w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
                        @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Số điện thoại --}}
                    <div>
                        <input name="phone" type="text" placeholder="Số điện thoại"
                            value="{{ old('phone') }}"
                            class="w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
                        @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nội dung --}}
                    <div>
                        <textarea name="message" placeholder="Nội dung" rows="5"
                            class="w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-blue-200 focus:outline-none resize-none">{{ old('message') }}</textarea>
                        @error('message') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit"
                        class="bg-blue-600 text-white w-full py-3 rounded-md font-semibold hover:bg-blue-700 transition hover:scale-[1.02]">
                        Gửi liên hệ
                    </button>
                </form>
            </div>
        </div>
    </section>
    <!-- Liên hệ + Bản đồ -->
<section class="py-16 bg-white rounded-xl hover:shadow-2xl mt-10">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10" data-aos="fade-up">
            <h2 class="text-3xl font-semibold mb-4">Liên hệ & Vị trí cửa hàng</h2>
            <p class="text-gray-700">Tìm đến chúng tôi dễ dàng qua bản đồ dưới đây hoặc liên hệ trực tiếp.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
            <!-- Thông tin liên hệ -->
            <div class="space-y-4" data-aos="fade-right">
                <div class="flex items-start gap-4">
                    <i class="fas fa-map-marker-alt text-yellow-500 text-2xl mt-1"></i>
                    <div>
                        <h3 class="font-bold text-lg">Địa chỉ</h3>
                        <p>Số 53 Triều Khúc, Thanh Xuân, Hà Nội</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <i class="fas fa-phone-alt text-blue-500 text-2xl mt-1"></i>
                    <div>
                        <h3 class="font-bold text-lg">Hotline</h3>
                        <p>+84 912 345 678</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <i class="fas fa-envelope text-green-500 text-2xl mt-1"></i>
                    <div>
                        <h3 class="font-bold text-lg">Email</h3>
                        <p>support@nexusphone.vn</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <i class="fas fa-clock text-red-500 text-2xl mt-1"></i>
                    <div>
                        <h3 class="font-bold text-lg">Giờ mở cửa</h3>
                        <p>Thứ 2 - Thứ 7: 8:00 - 20:00</p>
                    </div>
                </div>
            </div>

            <!-- Bản đồ -->
            <div data-aos="fade-left">
                <div id="map" class="w-full h-96 rounded-xl shadow-lg overflow-hidden"></div>
            </div>
        </div>
    </div>
</section>



</div>
<style>
   
#map {
    position: relative;
    z-index: 1;
}

</style>

@endsection
<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Khởi tạo bản đồ với tọa độ chính xác
    const map = L.map('map').setView([20.9930, 105.8105], 17);

    // Thêm layer OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Thêm marker
    L.marker([20.9930, 105.8105]).addTo(map)
        .bindPopup('<b>NexusPhone</b><br>Số 53 Triều Khúc, Thanh Xuân, Hà Nội.')
        .openPopup();
});
</script>
