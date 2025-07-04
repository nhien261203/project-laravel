<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký quản trị</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-gray-100 flex items-center justify-center min-h-screen">

<div class="w-full max-w-md bg-gray-900 rounded-xl shadow-lg p-8 space-y-6">
    {{-- Header --}}
    <div class="text-center">
        <img src="https://cdn-icons-png.flaticon.com/512/1041/1041916.png"
             alt="Logo" class="h-12 mx-auto mb-3">
        <h2 class="text-2xl font-bold text-white">Đăng ký tài khoản quản trị</h2>
        <p class="text-sm text-gray-400 mt-1">Dành cho admin và nhân viên hệ thống</p>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.register') }}" method="POST" class="space-y-4">
        @csrf

        {{-- Họ và tên --}}
        <div>
            <input type="text" name="name" placeholder="Họ và tên" value="{{ old('name') }}"
                   class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500"
                   required>
            @error('name')
                <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}"
                   class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500"
                   required>
            @error('email')
                <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Mật khẩu --}}
        <div>
            <input type="password" name="password" placeholder="Mật khẩu"
                   class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500"
                   required>
            @error('password')
                <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nhập lại mật khẩu --}}
        <div>
            <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu"
                   class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500"
                   required>
        </div>

        {{-- Đăng ký --}}
        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
            Đăng ký quản trị viên
        </button>
    </form>

    {{-- Đăng ký mạng xã hội (placeholder) --}}
    <div class="flex flex-col gap-2">
        <a href="#" class="flex items-center justify-center gap-2 w-full border border-gray-600 py-2 rounded-lg hover:bg-gray-800 transition">
            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M21.8 10.1H12v3.9h5.7c-.8 2.1-3 3.5-5.7 3.5-3.3 0-6-2.7-6-6s2.7-6 6-6c1.6 0 3 .6 4.1 1.7l2.9-2.9C17.7 2.6 15 1.5 12 1.5 6.8 1.5 2.5 5.8 2.5 11s4.3 9.5 9.5 9.5 9-3.6 9-9c0-.6-.1-1.2-.2-1.4z"/>
            </svg>
            Đăng ký với Google
        </a>

        <a href="#" class="flex items-center justify-center gap-2 w-full border border-gray-600 py-2 rounded-lg hover:bg-gray-800 transition">
            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M22 12a10 10 0 1 0-11.5 9.9v-7H8v-3h2.5V9.5c0-2.5 1.5-3.9 3.7-3.9 1.1 0 2.3.2 2.3.2v2.5h-1.3c-1.3 0-1.7.8-1.7 1.6V12h3l-.5 3h-2.5v7A10 10 0 0 0 22 12z"/>
            </svg>
            Đăng ký với Facebook
        </a>
    </div>

    {{-- Đã có tài khoản --}}
    <p class="text-sm text-center text-gray-400">
        Đã có tài khoản? 
        <a href="{{ route('admin.login') }}" class="text-blue-400 hover:underline">Đăng nhập quản trị</a>
    </p>
</div>

</body>
</html>
