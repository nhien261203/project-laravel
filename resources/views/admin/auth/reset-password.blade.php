<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt lại mật khẩu - Quản trị</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-gray-900 border border-gray-700 shadow-lg rounded-xl p-8">
        <div class="text-center mb-6">
            <img src="https://cdn-icons-png.flaticon.com/512/3064/3064197.png" class="h-12 mx-auto mb-3" alt="Reset Password">
            <h2 class="text-2xl font-bold">🔐 Đặt lại mật khẩu</h2>
            <p class="text-sm text-gray-400 mt-1">Nhập mật khẩu mới để hoàn tất</p>
        </div>

        @if(session('error'))
            <div class="bg-red-500/10 text-red-400 px-4 py-2 rounded mb-4 text-sm text-center">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.password.update') }}" method="POST" class="space-y-5">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            {{-- Mật khẩu mới --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Mật khẩu mới</label>
                <input type="password" name="password" id="password" required placeholder="••••••••"
                       class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 text-white outline-none">
                @error('password')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nhập lại mật khẩu --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="••••••••"
                       class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 text-white outline-none">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-md font-semibold transition">
                ✅ Đặt lại mật khẩu
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="{{ route('admin.login') }}" class="text-sm text-blue-400 hover:underline">
                ← Quay lại đăng nhập
            </a>
        </div>
    </div>

</body>
</html>
