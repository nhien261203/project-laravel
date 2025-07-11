<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white border border-gray-300 shadow-lg rounded-xl p-8">
        <div class="text-center mb-6">
            <img src="https://cdn-icons-png.flaticon.com/512/565/565547.png" class="h-12 mx-auto mb-3" alt="lock icon">
            <h2 class="text-2xl font-bold">🔐 Quên mật khẩu</h2>
            <p class="text-sm text-gray-600 mt-1">Nhập email để nhận liên kết đặt lại mật khẩu</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm text-center">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm text-center">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                       placeholder="user@example.com" required
                       class="w-full px-4 py-2 bg-white border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800 outline-none">
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-md font-semibold transition">
                📩 Gửi liên kết đặt lại mật khẩu
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">
                ← Quay lại đăng nhập
            </a>
        </div>
    </div>

</body>
</html>
