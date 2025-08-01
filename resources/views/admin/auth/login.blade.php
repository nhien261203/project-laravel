<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập quản trị</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-gray-100">

<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-4xl bg-gray-800 rounded-xl shadow-lg flex overflow-hidden">
        
        {{-- Ảnh trái --}}
        <div class="hidden md:block md:w-1/2 bg-cover bg-center relative" style="background-image: url('https://cdn.tgdd.vn/2022/10/banner/TGDD-540x270.png')">
            <div class="absolute inset-0 bg-black/60 flex items-center justify-center p-6">
                <h2 class="text-2xl font-bold text-white text-center">Hệ thống quản trị <br> ElectroShop</h2>
            </div>
        </div>

        {{-- Form --}}
        <div class="w-full md:w-1/2 p-8 bg-gray-900">
            <div class="text-center mb-6">
                <img src="https://cdn-icons-png.flaticon.com/512/1041/1041916.png" alt="Logo"
                            class="h-12 mx-auto mb-3">
                <h2 class="text-2xl font-bold text-white">Đăng nhập quản trị viên</h2>
                <p class="text-sm text-gray-400">Quản lý sản phẩm, đơn hàng, người dùng...</p>
            </div>

            {{-- Hiển thị lỗi --}}
            @if(session('error'))
                <div class="bg-red-500/10 text-red-400 text-sm p-3 mb-4 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Email or Phone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300">Email hoặc số điện thoại</label>
                    <input type="text" name="login" value="{{ old('login') }}"
                           class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 text-white"
                           placeholder="admin@example.com hoặc 0987654321" required>
                    @error('login')
                        <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300">Mật khẩu</label>
                    <input type="password" name="password"
                           class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 text-white"
                           placeholder="••••••••" required>
                    @error('password')
                        <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember + Forgot --}}
                <div class="flex items-center justify-between text-sm text-gray-400">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="accent-blue-600">
                        Ghi nhớ
                    </label>
                    <a href="{{ route('admin.password.request') }}" class="text-blue-400 hover:underline">
                        Quên mật khẩu?
                    </a>
                </div>

                {{-- Login button --}}
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-md transition">
                    Đăng nhập
                </button>
                <div class="flex flex-row gap-2">
                    {{-- Google Login --}}
                    <a href="{{ route('admin.redirect.google') }}"
                    class="flex items-center justify-center gap-2 flex-1 border py-2 rounded-md hover:bg-gray-400">
                        <svg class="w-5 h-5 text-red-500" viewBox="0 0 48 48">
                            <path fill="#EA4335" d="M24 9.5c3.5 0 6.6 1.2 9 3.6l6.7-6.7C35.2 2.3 29.9 0 24 0 14.6 0 6.5 5.8 2.6 14.1l7.9 6.1C12.3 13.5 17.6 9.5 24 9.5z"/>
                            <path fill="#4285F4" d="M46.5 24.5c0-1.5-.1-3.1-.4-4.5H24v9h12.7c-1.1 3.1-3.4 5.8-6.7 7.6l7.9 6.1c4.6-4.3 7.3-10.6 7.3-18.2z"/>
                            <path fill="#FBBC05" d="M10.5 28.5c-1.1-3.1-1.1-6.4 0-9.5l-7.9-6.1C-0.4 17.6-0.4 30.4 2.6 37.9l7.9-6.1z"/>
                            <path fill="#34A853" d="M24 48c6.5 0 12-2.1 16-5.7l-7.9-6.1c-2.3 1.6-5.3 2.5-8.1 2.5-6.4 0-11.7-4-13.6-9.5l-7.9 6.1C6.5 42.2 14.6 48 24 48z"/>
                        </svg>
                        Đăng nhập Google
                    </a>

                    {{-- GitHub Login --}}
                    <a href="{{ route('admin.redirect.github') }}"
                    class="flex items-center justify-center gap-2 flex-1 border py-2 rounded-md hover:bg-gray-400">
                        <svg class="w-5 h-5 text-gray-800" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M12 0C5.37 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.385.6.113.793-.258.793-.577v-2.021c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.744.082-.729.082-.729 1.205.086 1.84 1.236 1.84 1.236 1.07 1.834 2.809 1.304 3.495.997.107-.775.42-1.305.763-1.605-2.665-.3-5.467-1.334-5.467-5.932 0-1.31.468-2.381 1.235-3.221-.124-.303-.535-1.522.117-3.176 0 0 1.008-.322 3.301 1.23a11.51 11.51 0 013.004-.404c1.02.004 2.045.138 3.004.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.654.242 2.873.118 3.176.77.84 1.234 1.911 1.234 3.221 0 4.61-2.807 5.628-5.48 5.922.43.372.814 1.102.814 2.222v3.293c0 .322.192.694.801.576C20.565 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/>
                        </svg>
                        Đăng nhập Github
                    </a>
                </div>

            </form>

            {{-- Đăng ký --}}
            <p class="text-sm text-center text-gray-400 mt-6">
                Chưa có tài khoản? 
                <a href="{{ route('admin.register') }}" class="text-blue-400 hover:underline">Đăng ký quản trị</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>
