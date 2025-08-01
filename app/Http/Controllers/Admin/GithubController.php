<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class GithubController extends Controller
{
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }
    public function handleGithubCallback()
    {
        try {
            $oldSessionId = session()->getId(); 
            $githubUser = Socialite::driver('github')->user();

            // Tìm user theo github_id
            $finduser = User::where('github_id', $githubUser->id)->first();

            if (!$finduser) {
                // Nếu không tìm thấy theo github_id, tìm theo email
                $finduser = User::where('email', $githubUser->email)->first();

                if ($finduser) {
                    // Nếu đã có tài khoản email nhưng chưa có github_id => cập nhật github_id
                    if (!$finduser->github_id) {
                        $finduser->update(['github_id' => $githubUser->id]);
                    }
                } else {
                    // Nếu chưa có user nào cả => tạo mới
                    $finduser = User::create([
                        'name'       => $githubUser->name,
                        'email'      => $githubUser->email,
                        'active' => true, // mặc định
                        'github_id'  => $githubUser->id,
                        'password'   => bcrypt('123456dummy'), // chỉ tạo dummy nếu hoàn toàn mới
                    ]);
                }
            }

            // Gán quyền 'staff' nếu chưa có quyen
            if ($finduser->roles()->count() === 0) {
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

            return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập Github (admin) thành công!');
        } catch (Exception $e) {
            return redirect('/admin/login')->with('error', 'Đăng nhập Github thất bại: ' . $e->getMessage());
        }
    }
}
