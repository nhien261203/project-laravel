@extends('layout.user')

@section('content')
<div class="container py-8 md:flex md:gap-6 pt-20">

    {{-- Sidebar trái --}}
    <div class="w-full md:w-1/4 mb-6 md:mb-0">
        <div class="bg-white shadow rounded p-4 md:sticky md:top-24">
            <ul class="space-y-3 text-sm text-gray-800 font-medium">
                <li><a href="{{ route('user.profile') }}" class="flex items-center gap-2 {{ request()->routeIs('user.profile') ? 'text-blue-600 font-bold' : 'hover:text-blue-600' }}"><i class="fa-solid fa-user"></i> Thông tin tài khoản</a></li>
                <li><a  href="{{ route('user.orders.index') }}" class="flex items-center gap-2 {{ request()->routeIs('user.orders.index') ? 'text-blue-600 font-bold' : 'hover:text-blue-600' }}"><i class="fa-solid fa-eye"></i> Đơn hàng của bạn</a></li>

                <li><a href="{{ route('password.form') }}" class="flex items-center gap-2 {{ request()->routeIs('password.form') ? 'text-blue-600 font-bold' : 'hover:text-blue-600' }}"><i class="fa-solid fa-lock"></i> Đổi mật khẩu</a></li>
                <li><div class="flex items-center gap-2 text-red-600 hover:underline"><i class="fa-solid fa-right-from-bracket"></i> 
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" >Thoát</button>
                    </form>
                </div></li>
            </ul>
        </div>
    </div>

    {{-- Nội dung phải --}}
    <div class="w-full md:w-3/4 bg-white shadow rounded p-6">
        @yield('user_profile_content')
    </div>
</div>
@endsection
