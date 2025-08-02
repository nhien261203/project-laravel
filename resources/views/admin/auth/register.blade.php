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
    <div class="flex flex-row gap-2">
                    

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

    {{-- Đã có tài khoản --}}
    <p class="text-sm text-center text-gray-400">
        Đã có tài khoản? 
        <a href="{{ route('admin.login') }}" class="text-blue-400 hover:underline">Đăng nhập quản trị</a>
    </p>
</div>

</body>
</html>
