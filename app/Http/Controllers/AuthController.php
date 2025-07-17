<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Hiển thị form đăng nhập
    public function showLogin()
    {
        return view('user.auth.login');
    }

    // Xử lý đăng nhập


    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $loginType => $request->login,
            'password' => $request->password,
        ];

        // Lưu session_id cũ trước khi đăng nhập
        $oldSessionId = $request->session()->getId();

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate(); // Tạo session mới

            // Gọi mergeCart thủ công với session ID cũ
            app(\App\Repositories\Cart\CartRepositoryInterface::class)
                ->mergeCart(Auth::id(), $oldSessionId);

            app(\App\Repositories\UserRecentProduct\UserRecentProductRepositoryInterface::class)
            ->mergeRecentViewed(Auth::id(), $oldSessionId);

            // Đánh dấu đã merge nếu cần
            session(['cart_merged' => true]);

            return redirect('/')->with('success', 'Chào mừng bạn đến với Nexus shop !');
        }

        return back()->with('error', 'Thông tin đăng nhập không chính xác.')->withInput();
    }


    // Hiển thị form đăng ký
    public function showRegister()
    {
        return view('user.auth.register');
    }

    // Xử lý đăng ký
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // Gán quyền "user" mặc định
        $user->assignRole('user');

        return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }

    // Xử lý đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Xoá cờ cart_merged
        session()->forget('cart_merged');

        return redirect()->route('login');
    }


    // doi mat khau
    public function showChangePassword()
    {
        return view('user.auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Mật khẩu đã được thay đổi thành công!');
    }
}
