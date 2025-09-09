@extends('layout.admin')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-lg font-semibold mb-4">Chỉnh sửa người dùng</h2>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block mb-1 font-medium">Tên</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border p-2 rounded bg-gray-100" readonly>
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border p-2 rounded bg-gray-100" readonly>
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium">SĐT</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border p-2 rounded bg-gray-100" readonly>
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium">Quyền</label>
            <select name="role[]" class="w-full border p-2 rounded" multiple>
                @foreach ($roles as $role)
                    <option value="{{ $role }}" {{ $user->hasRole($role) ? 'selected' : '' }}>
                        {{ ucfirst($role) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Chỉ cho phép cập nhật trạng thái nếu không phải admin --}}
        @if (!$user->hasRole('admin'))
        <div class="mb-4">
            <label class="block mb-1 font-medium">Trạng thái tài khoản</label>
            <select name="active" class="w-full border p-2 rounded">
                <option value="1" {{ $user->active ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ !$user->active ? 'selected' : '' }}>Vô hiệu hóa</option>
            </select>
        </div>
        @else
            <div class="mb-4">
                <label class="block mb-1 font-medium">Trạng thái tài khoản</label>
                <input type="text" value="Hoạt động (Admin - không thể thay đổi)" disabled class="w-full border p-2 rounded bg-gray-100 text-gray-700">
            </div>
        @endif

        <button class="bg-blue-500 text-white px-4 py-2 rounded">Cập nhật</button>
    </form>
</div>
@endsection
