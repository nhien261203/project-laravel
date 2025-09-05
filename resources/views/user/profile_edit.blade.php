@extends('layout.user_profile')

@section('user_profile_content')
<h2 class="text-2xl font-bold text-gray-800 mb-6"> Cập nhật thông tin</h2>

<form method="POST" action="{{ route('user.update') }}">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block font-medium text-gray-700 mb-1">Họ tên</label>
            <input type="text" name="name" class="w-full border rounded px-4 py-2" value="{{ old('name', $user->name) }}">
            @error('name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-medium text-gray-700 mb-1">Email</label>
            <input type="email" class="w-full border rounded px-4 py-2 bg-gray-100 text-gray-600" value="{{ $user->email }}" disabled>
        </div>

        <div>
            <label class="block font-medium text-gray-700 mb-1">Số điện thoại</label>
            <input type="text" name="phone" class="w-full border rounded px-4 py-2" value="{{ old('phone', $user->phone) }}">
            @error('phone') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- <div>
            <label class="block font-medium text-gray-700 mb-1">Địa chỉ</label>
            <input type="text" name="address" class="w-full border rounded px-4 py-2" value="{{ old('address', $user->address) }}">
            @error('address') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div> --}}
    </div>

    <div class="mt-6">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
            💾 Lưu thay đổi
        </button>
        <a href="{{ route('user.profile') }}" class="ml-3 text-gray-600 hover:text-black">← Quay lại</a>
    </div>
</form>
@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Lỗi!',
        text: '{{ session('error') }}'
    });
</script>
@endif

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Thành công!',
        text: '{{ session('success') }}'
    });
</script>
@endif

