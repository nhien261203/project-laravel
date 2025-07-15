@extends('layout.user')

@section('content')
<div class="pt-24">
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow mt-6">
        <h2 class="text-xl font-bold mb-4">🔒 Thay đổi mật khẩu</h2>

        {{-- @if (session('success'))
            <div class="text-green-600 mb-4">{{ session('success') }}</div>
        @endif --}}

        <form method="POST" action="{{ route('password.change') }}">
            @csrf

            <div class="mb-4">
                <label>Mật khẩu hiện tại</label>
                <input type="password" name="current_password" class="w-full border mt-1 rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label>Mật khẩu mới</label>
                <input type="password" name="new_password" class="w-full border mt-1 rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label>Xác nhận mật khẩu mới</label>
                <input type="password" name="new_password_confirmation" class="w-full border mt-1 rounded px-3 py-2" required>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Cập nhật</button>
        </form>
    </div>
</div>
@endsection
