@extends('layout.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-6">✏️ Chỉnh sửa thông tin cá nhân</h2>

    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div>
            <label class="block text-gray-700 font-medium">Họ tên *</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                class="form-input w-full" required>
            @error('name')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Số điện thoại</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                class="form-input w-full">
            @error('phone')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Địa chỉ</label>
            <input type="text" name="address" value="{{ old('address', $user->address) }}"
                class="form-input w-full">
            @error('address')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Giới tính</label>
            <select name="gender" class="form-select w-full">
                <option value="">-- Chọn --</option>
                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Khác</option>
            </select>
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Ảnh đại diện</label>
            <input type="file" name="avatar" class="form-input w-full">
            @if($user->avatar)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $user->avatar) }}" class="w-20 h-20 rounded-full object-cover">
                </div>
            @endif
        </div>
        @role('admin')
            <div>
                <label class="block text-gray-700 font-medium">Quyền</label>
                <select name="role" class="form-select w-full">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endrole


        <div class="pt-4 flex justify-between">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded">
                💾 Cập nhật
            </button>
            <a href="{{ route('admin.profile.show') }}" class="text-sm text-gray-600 hover:underline">
                ← Quay lại
            </a>
        </div>
    </form>
</div>
@endsection
