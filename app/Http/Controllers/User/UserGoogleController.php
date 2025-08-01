<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserGoogleController extends Controller
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
    
}
