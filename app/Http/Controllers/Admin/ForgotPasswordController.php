<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('admin.auth.forgot-password');
    }

    // Gửi mail reset mật khẩu
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        Mail::to($request->email)->send(new ResetPasswordMail($token, $request->email, true));


        return back()->with('success', 'Đã gửi liên kết đặt lại mật khẩu đến email của bạn.');
    }

    // Hiển thị form đặt lại mật khẩu
    public function showResetForm(Request $request)
    {
        return view('admin.auth.reset-password', [
            'token' => $request->token,
            'email' => $request->email,
        ]);
    }

    // Xử lý đặt lại mật khẩu
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $check = DB::table('password_reset_tokens')->where([
            ['email', $request->email],
            ['token', $request->token],
        ])->first();

        if (!$check) {
            return back()->with('error', 'Liên kết không hợp lệ hoặc đã hết hạn.');
        }

        // Kiểm tra token quá 60 phút chưa
        if (now()->diffInMinutes($check->created_at) > 60) {
            return back()->with('error', 'Liên kết đã hết hạn. Vui lòng gửi lại.');
        }

        // Cập nhật mật khẩu
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Xoá token sau khi dùng
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('admin.login')->with('success', 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập.');
    }
}
