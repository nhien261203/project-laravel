<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css') {{-- Tailwind CSS --}}
</head>
<body class="bg-gray-100">

<div class="min-h-screen flex items-center justify-center px-4">
    <div class="flex w-full xl:w-2/3 max-w-4xl shadow-2xl rounded-lg overflow-hidden bg-white">
        
        {{-- Hình bên trái --}}
        <div class="w-1/2 hidden md:block relative">
            <img
                src="https://cdn.tgdd.vn/2022/10/banner/TGDD-540x270.png"
                alt="Electronic Banner"
                class="w-full h-full object-contain"
            />
            <div class="absolute inset-0 bg-gradient-to-tr from-black/60 to-transparent flex items-end p-6">
                <h2 class="text-white text-xl font-semibold">Welcome back to ElectroShop</h2>
            </div>
        </div>

        {{-- Form đăng nhập --}}
        <div class="w-full md:w-1/2 p-8 bg-white">
            <div class="text-center mb-6">
                <img
                    src="https://cdn-icons-png.flaticon.com/512/1041/1041916.png"
                    alt="Logo"
                    class="h-12 mx-auto mb-2"
                />
                <h2 class="text-2xl font-bold text-gray-800">Đăng nhập tài khoản</h2>
                <p class="text-sm text-gray-500">Quản lý đơn hàng, thiết bị và nhiều hơn nữa</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Email or Phone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email hoặc số điện thoại</label>
                    <input
                        type="text"
                        name="login"
                        value="{{ old('login') }}"
                        placeholder="Nhập email hoặc số điện thoại"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                    />
                    @error('login')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mật khẩu --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="Nhập mật khẩu"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                    />
                    @error('password')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember & Forgot --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="mr-2" />
                        <span class="text-sm text-gray-600">Ghi nhớ đăng nhập</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">
                        Quên mật khẩu?
                    </a>
                </div>

                {{-- Đăng nhập --}}
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 font-semibold"
                >
                    Đăng nhập
                </button>

                {{-- Đăng nhập mạng xã hội (chưa có logic) --}}
                <div class="flex flex-col gap-2">
                    <a href="#"
                       class="flex items-center justify-center gap-2 w-full border py-2 rounded-md hover:bg-gray-100">
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21.8 10.1H12v3.9h5.7c-.8 2.1-3 3.5-5.7 3.5-3.3 0-6-2.7-6-6s2.7-6 6-6c1.6 0 3 .6 4.1 1.7l2.9-2.9C17.7 2.6 15 1.5 12 1.5 6.8 1.5 2.5 5.8 2.5 11s4.3 9.5 9.5 9.5 9-3.6 9-9c0-.6-.1-1.2-.2-1.4z"/>
                        </svg>
                        Đăng nhập với Google
                    </a>

                    <a href="#"
                       class="flex items-center justify-center gap-2 w-full border py-2 rounded-md hover:bg-gray-100">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22 12a10 10 0 1 0-11.5 9.9v-7H8v-3h2.5V9.5c0-2.5 1.5-3.9 3.7-3.9 1.1 0 2.3.2 2.3.2v2.5h-1.3c-1.3 0-1.7.8-1.7 1.6V12h3l-.5 3h-2.5v7A10 10 0 0 0 22 12z"/>
                        </svg>
                        Đăng nhập với Facebook
                    </a>
                </div>
            </form>

            <p class="text-sm text-center text-gray-600 mt-6">
                Chưa có tài khoản? 
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Đăng ký ngay</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>
