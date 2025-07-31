<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    @vite('resources/css/app.css') {{-- Tailwind CSS --}}
    <!-- alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <div class="relative">
                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="Nhập mật khẩu"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-4 py-2 pr-10 focus:ring-blue-500 focus:border-blue-500"
                            required
                        />
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                            <i id="eyeIcon" class="fa-solid fa-eye"></i>
                        </button>
                    </div>

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

                {{-- Đăng nhập mạng xã hội --}}
                <div class="flex flex-col gap-2">
                    <a href="{{ route('redirect.google') }}"
                       class="flex items-center justify-center gap-2 w-full border py-2 rounded-md hover:bg-gray-100">
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21.8 10.1H12v3.9h5.7c-.8 2.1-3 3.5-5.7 3.5-3.3 0-6-2.7-6-6s2.7-6 6-6c1.6 0 3 .6 4.1 1.7l2.9-2.9C17.7 2.6 15 1.5 12 1.5 6.8 1.5 2.5 5.8 2.5 11s4.3 9.5 9.5 9.5 9-3.6 9-9c0-.6-.1-1.2-.2-1.4z"/>
                        </svg>
                        Đăng nhập với Google
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
{{-- trang thai alert --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Thành công',
            text: '{{ session('success') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2500
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: '{{ session('error') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    @endif
</script>
<script>
    function togglePassword() {
        const input = document.getElementById("password");
        const icon = document.getElementById("eyeIcon");

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>

</html>
