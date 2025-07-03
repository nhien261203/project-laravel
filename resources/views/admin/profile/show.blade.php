@extends('layout.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-6 mt-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">👤 Thông tin tài khoản</h2>

    <div class="flex flex-col items-center mb-6">
        @if($user->avatar)
            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-24 h-24 rounded-full object-cover shadow mb-2">
        @else
            <div class="w-24 h-24 rounded-full bg-gray-300 flex items-center justify-center text-white text-2xl shadow mb-2">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        @endif
        <p class="text-lg font-semibold text-gray-700">{{ $user->name }}</p>
        <p class="text-sm text-gray-500">{{ $user->email }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <div>
            <label class="block text-gray-600">Số điện thoại:</label>
            <p class="text-gray-800 font-medium">{{ $user->phone ?? 'Chưa có' }}</p>
        </div>

        <div>
            <label class="block text-gray-600">Địa chỉ:</label>
            <p class="text-gray-800 font-medium">{{ $user->address ?? 'Chưa có' }}</p>
        </div>

        <div>
            <label class="block text-gray-600">Giới tính:</label>
            <p class="text-gray-800 font-medium">
                @switch($user->gender)
                    @case('male') Nam @break
                    @case('female') Nữ @break
                    @case('other') Khác @break
                    @default Chưa có
                @endswitch
            </p>
        </div>

        <div class="md:col-span-2">
            <label class="block text-gray-600">Quyền:</label>
            <div class="mt-1">
                @forelse($roles as $role)
                    <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full mr-2">
                        {{ ucfirst($role) }}
                    </span>
                @empty
                    <span class="text-gray-500">Không có quyền</span>
                @endforelse
            </div>
        </div>
    </div>

    <div class="mt-8 text-center">
        <a href="{{ route('admin.profile.edit') }}"
           class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-6 py-2 rounded shadow">
            ✏️ Chỉnh sửa thông tin
        </a>
    </div>
</div>
@endsection
