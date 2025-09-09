@extends('layout.admin')

@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Tạo Người Dùng Mới</h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Tên người dùng:</label>
                <input type="text" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" id="name" name="name" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email:</label>
                <input type="email" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" id="email" name="email" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-medium mb-2">Quyền hạn:</label>
                <div class="flex flex-wrap gap-4">
                    @foreach ($roles as $role)
                        <div class="flex items-center">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded-md" id="{{ $role->name }}" name="role[]" value="{{ $role->name }}">
                            <label class="ml-2 text-gray-700" for="{{ $role->name }}">{{ $role->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <button type="submit" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">Tạo tài khoản</button>
                <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-gray-600 transition duration-300">Quay lại</a>
            </div>
        </form>
    </div>
@endsection
