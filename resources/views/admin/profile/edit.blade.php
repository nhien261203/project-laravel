@extends('layout.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">✏️ Chỉnh sửa thông tin cá nhân</h2>

    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Họ tên --}}
            <div>
                <label class="block text-gray-700 font-medium mb-1">Họ tên *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100" required>
                @error('name')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Số điện thoại --}}
            <div>
                <label class="block text-gray-700 font-medium mb-1">Số điện thoại</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
                @error('phone')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Địa chỉ --}}
            <div>
                <label class="block text-gray-700 font-medium mb-1">Địa chỉ</label>
                <input type="text" name="address" value="{{ old('address', $user->address) }}"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
                @error('address')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Giới tính --}}
            <div>
                <label class="block text-gray-700 font-medium mb-1">Giới tính</label>
                <select name="gender" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
                    <option value="">-- Chọn --</option>
                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                    <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                </select>
            </div>

            {{-- Ảnh đại diện --}}
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-medium mb-1">Ảnh đại diện</label>
                <input type="file" name="avatar" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
                @if($user->avatar)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                             class="w-20 h-20 object-cover rounded-full border shadow">
                    </div>
                @endif
            </div>

            {{-- Quyền (chỉ admin) --}}
            {{-- @role('admin')
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-medium mb-1">Quyền</label>
                <select name="role" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endrole --}}
        </div>

        {{-- Buttons --}}
        <div class="pt-6 flex justify-between">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow">
                💾 Cập nhật
            </button>
            <a href="{{ route('admin.profile.show') }}" class="text-sm text-gray-600 hover:underline">
                ← Quay lại
            </a>
        </div>
    </form>
</div>
@endsection
