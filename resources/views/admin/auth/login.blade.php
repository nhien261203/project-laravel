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
