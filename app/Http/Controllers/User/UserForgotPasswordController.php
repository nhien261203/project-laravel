<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('user.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        Mail::to($request->email)->send(new ResetPasswordMail($token, $request->email));

        return back()->with('success', 'Liên kết đặt lại mật khẩu đã được gửi đến email của bạn.');
    }

    public function showResetForm(Request $request)
    {
        return view('user.auth.reset-password', [
            'token' => $request->token,
            'email' => $request->email,
        ]);
    }

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

        if (now()->diffInMinutes($check->created_at) > 60) {
            return back()->with('error', 'Liên kết đã hết hạn. Vui lòng gửi lại.');
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập.');
    }
}
