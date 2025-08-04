@php
    $hasPassword = auth()->user()->password !== null;
@endphp
@extends('layout.admin')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    @if (empty(Auth::user()->password))
            <div class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded mb-4">
                Bạn chưa đặt mật khẩu. Vui lòng đặt mật khẩu để có thể đăng nhập bằng email lần sau.
            </div>
        @endif
        <h2 class="text-xl font-bold mb-4">
            {{ $hasPassword ? 'Thay đổi mật khẩu' : 'Đặt mật khẩu lần đầu' }}
        </h2>

        {{-- Thông báo thành công --}}
        {{-- @if (session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif --}}

        {{-- Thông báo lỗi chung --}}
        {{-- @if (session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif --}}

        {{-- Lỗi validate --}}
        {{-- @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}

        <form method="POST" action="{{ route('admin.password.change') }}">
            @csrf

            {{-- Hiện trường mật khẩu cũ nếu đã có --}}
            @if ($hasPassword)
                <div class="mb-4">
                    <label class="block font-medium">Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" class="w-full border mt-1 rounded px-3 py-2" required>
                    @error('current_password')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            <div class="mb-4">
                <label class="block font-medium">Mật khẩu mới</label>
                <input type="password" name="new_password" class="w-full border mt-1 rounded px-3 py-2" required>
                @error('new_password')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-medium">Xác nhận mật khẩu mới</label>
                <input type="password" name="new_password_confirmation" class="w-full border mt-1 rounded px-3 py-2" required>
                @error('new_password_confirmation')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                {{ $hasPassword ? 'Cập nhật' : 'Đặt mật khẩu' }}
            </button>
        </form>
</div>
@endsection
