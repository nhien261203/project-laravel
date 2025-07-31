<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $oldSessionId = session()->getId(); 
            $googleUser = Socialite::driver('google')->user();

            // Tìm theo google_id
            $finduser = User::where('google_id', $googleUser->id)->first();

            if ($finduser) {
                Auth::login($finduser);
            } else {
                // Tìm theo email
                $existingUser = User::where('email', $googleUser->email)->first();

                if ($existingUser) {
                    // Gán google_id nếu chưa có
                    if (!$existingUser->google_id) {
                        $existingUser->update(['google_id' => $googleUser->id]);
                    }

                    Auth::login($existingUser);
                } else {
                    // Tạo user mới
                    $newUser = User::create([
                        'name'       => $googleUser->name,
                        'email'      => $googleUser->email,
                        'google_id'  => $googleUser->id,
                        'password'   => bcrypt('123456dummy'),
                    ]);

                    // Gán quyền mặc định nếu chưa có quyền nào
                    if ($newUser->roles()->count() === 0) {
                        $newUser->assignRole('user');
                    }

                    Auth::login($newUser);
                }
            }

            // // Merge cart & recent product
            // $sessionId = session()->getId();
            app(\App\Repositories\Cart\CartRepositoryInterface::class)
                ->mergeCart(Auth::id(), $oldSessionId);

            app(\App\Repositories\UserRecentProduct\UserRecentProductRepositoryInterface::class)
                ->mergeRecentViewed(Auth::id(), $oldSessionId);

            session(['cart_merged' => true]);

            return redirect()->intended('/')->with('success', 'Đăng nhập Google thành công!');
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Đăng nhập Google thất bại: ' . $e->getMessage());
        }
    }

    public function handleGoogleCallbackForAdmin()
    {
        try {
            $oldSessionId = session()->getId(); 
            $googleUser = Socialite::driver('google')->user();

            // Tìm user theo google_id
            $finduser = User::where('google_id', $googleUser->id)->first();

            if (!$finduser) {
                // Nếu không tìm thấy theo google_id, tìm theo email
                $finduser = User::where('email', $googleUser->email)->first();

                if ($finduser) {
                    // Nếu đã có tài khoản email nhưng chưa có google_id => cập nhật google_id
                    if (!$finduser->google_id) {
                        $finduser->update(['google_id' => $googleUser->id]);
                    }
                } else {
                    // Nếu chưa có user nào cả => tạo mới
                    $finduser = User::create([
                        'name'       => $googleUser->name,
                        'email'      => $googleUser->email,
                        'google_id'  => $googleUser->id,
                        'password'   => bcrypt('123456dummy'), // chỉ tạo dummy nếu hoàn toàn mới
                    ]);
                }
            }

            // Gán quyền 'staff' nếu chưa có 'admin' hoặc 'staff'
            if (!$finduser->hasAnyRole(['admin', 'staff'])) {
                $finduser->assignRole('staff');
            }

            // Đăng nhập
            Auth::login($finduser);

            // Gọi merge sau khi login
        
            app(\App\Repositories\Cart\CartRepositoryInterface::class)
                ->mergeCart(Auth::id(), $oldSessionId);

            app(\App\Repositories\UserRecentProduct\UserRecentProductRepositoryInterface::class)
                ->mergeRecentViewed(Auth::id(), $oldSessionId);

            session(['cart_merged' => true]);

            return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập Google (admin) thành công!');
        } catch (Exception $e) {
            return redirect('/admin/login')->with('error', 'Đăng nhập Google thất bại: ' . $e->getMessage());
        }
    }
}
