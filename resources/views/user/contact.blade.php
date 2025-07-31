@extends('layout.user')
@section('content')

<div class="container pt-10 pb-20">
    <section class="py-16 bg-gray-50 rounded-xl shadow-inner">
        <div class="container mx-auto px-4 grid md:grid-cols-2 gap-10 animate-fadeIn">
            
            {{-- Contact Info --}}
            <div class="pt-0 md:pt-28">
                <h2 class="text-2xl font-bold mb-4 text-blue-700">Bạn cần NEXUSPHONE hỗ trợ? Liên hệ với chúng tôi</h2>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Nếu bạn có bất kỳ câu hỏi hoặc cần hỗ trợ, hãy liên hệ với chúng tôi qua biểu mẫu hoặc thông tin dưới đây. 
                    Chúng tôi luôn sẵn sàng hỗ trợ bạn nhanh chóng và tận tâm.
                </p>

                <ul class="space-y-3 text-gray-700 text-base">
                    
                    <li><strong>📞 Hotline:</strong> 0968 239 407</li>
                    <li><strong>✉ Email:</strong>dovannhien12345@gmail.com</li>
                </ul>
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
</div>

@endsection
