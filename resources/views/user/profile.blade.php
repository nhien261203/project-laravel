@extends('layout.user_profile')

@section('user_profile_content')
<div class="pb-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Thông tin tài khoản</h2>

<form>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ">
        <div>
            <label class="block font-medium text-gray-700 mb-1">Họ tên</label>
            <input type="text" class="w-full border rounded px-4 py-2 bg-gray-50" value="{{ $user->name }}" disabled>
        </div>
        <div>
            <label class="block font-medium text-gray-700 mb-1">Email</label>
            <input type="email" class="w-full border rounded px-4 py-2 bg-gray-100 text-gray-600" value="{{ $user->email }}" disabled>
        </div>
        <div>
            <label class="block font-medium text-gray-700 mb-1">Số điện thoại</label>
            <input type="text" class="w-full border rounded px-4 py-2 bg-gray-50" value="{{ $user->phone ?? '' }}" disabled>
        </div>
        {{-- <div>
            <label class="block font-medium text-gray-700 mb-1">Địa chỉ</label>
            <input type="text" class="w-full border rounded px-4 py-2 bg-gray-50" value="{{ $user->address ?? '' }}" disabled>
        </div> --}}
    </div>

    <div class="mt-6">
        <a href="{{ route('user.edit') }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition inline-block">
            Chỉnh sửa thông tin
        </a>
    </div>
</form>
</div>
@endsection
