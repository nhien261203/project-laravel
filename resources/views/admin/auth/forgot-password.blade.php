<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu - Quản trị</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-gray-900 border border-gray-700 shadow-lg rounded-xl p-8">
        <div class="text-center mb-6">
            <img src="https://cdn-icons-png.flaticon.com/512/565/565547.png" class="h-12 mx-auto mb-3" alt="lock icon">
            <h2 class="text-2xl font-bold">🔐 Quên mật khẩu quản trị</h2>
            <p class="text-sm text-gray-400 mt-1">Nhập email để nhận liên kết đặt lại mật khẩu</p>
        </div>

        @if(session('success'))
            <div class="bg-green-500/10 text-green-400 px-4 py-2 rounded mb-4 text-sm text-center">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500/10 text-red-400 px-4 py-2 rounded mb-4 text-sm text-center">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.password.email') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                       placeholder="admin@example.com" required
                       class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 text-white outline-none">
                @error('email')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-md font-semibold transition">
                📩 Gửi liên kết đặt lại mật khẩu
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
