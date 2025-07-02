
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Quên mật khẩu</title>
    @vite('resources/css/app.css') {{-- Tailwind CSS --}}
</head>
<body>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="w-full max-w-md">
        <div class="bg-white border border-gray-200 shadow-lg rounded-xl p-8">
            <div class="text-center mb-6">
                <div class="flex justify-center mb-3">
                    <img src="https://cdn-icons-png.flaticon.com/512/565/565547.png" class="h-12" alt="lock icon">
                </div>
                <h2 class="text-2xl font-bold text-gray-800">🔒 Quên mật khẩu</h2>
                <p class="text-sm text-gray-500 mt-1">Nhập email để đặt lại mật khẩu của bạn</p>
            </div>

            @if(session('status'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4 text-sm text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        placeholder="example@gmail.com"
                        required
                        value="{{ old('email') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                    />
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-md font-semibold transition duration-200"
                >
                    📩 Gửi liên kết đặt lại mật khẩu
                </button>
            </form>

            <div class="text-center mt-6">
                <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">
                    ← Quay lại đăng nhập
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>

