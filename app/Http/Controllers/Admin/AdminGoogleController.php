<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\Config;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminGoogleController extends Controller
{
    public function redirectToGoogle()
    {
        // Override config bằng thông tin Google Admin
        Config::set('services.google.client_id', env('GOOGLE_CLIENT_ID_ADMIN'));
        Config::set('services.google.client_secret', env('GOOGLE_CLIENT_SECRET_ADMIN'));
        Config::set('services.google.redirect', env('GOOGLE_REDIRECT_URI_ADMIN'));

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallbackForAdmin()
    {
        try {
            Config::set('services.google.client_id', env('GOOGLE_CLIENT_ID_ADMIN'));
            Config::set('services.google.client_secret', env('GOOGLE_CLIENT_SECRET_ADMIN'));
            Config::set('services.google.redirect', env('GOOGLE_REDIRECT_URI_ADMIN'));

            $oldSessionId = session()->getId(); 

            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();

            // Tìm user theo google_id
            $finduser = User::where('google_id', $googleUser->id)->first();

            if (!$finduser) {
                // Nếu không có google_id, thử theo email
                $finduser = User::where('email', $googleUser->email)->first();

                if ($finduser && !$finduser->google_id) {
                    $finduser->update(['google_id' => $googleUser->id]);
                }

                if (!$finduser) {
                    $finduser = User::create([
                        'name'       => $googleUser->name,
                        'email'      => $googleUser->email,
                        'google_id'  => $googleUser->id,
                        'password'   => bcrypt('123456dummy'),
                    ]);
                }
            }

            // Gán quyền staff nếu chưa có role nào
            if ($finduser->roles()->count() === 0) {
                $finduser->assignRole('staff');
            }

            Auth::login($finduser);

            // Merge cart và recent viewed
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
