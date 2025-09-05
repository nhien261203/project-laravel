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

            if ($finduser) {
                // Kiểm tra nếu tài khoản bị vô hiệu hóa
                if (!$finduser->active) {
                    return redirect('/admin/login')->with('error', 'Tài khoản của bạn đã bị vô hiệu hóa.');
                }
            } else {
                $finduser = User::where('email', $githubUser->getEmail())->first();

                if ($finduser) {
                    if (!$finduser->active) {
                        return redirect('/admin/login')->with('error', 'Tài khoản của bạn đã bị vô hiệu hóa.');
                    }

                    if (empty($finduser->github_id)) {
                        $finduser->update(['github_id' => $githubUser->getId()]);
                    }
                } else {
                    // Nếu chưa có user nào cả => tạo mới
                    $finduser = User::create([
                        'name'       => $githubUser->getName() ?? $githubUser->getNickname() ?? 'No Name',
                        'email'      => $githubUser->getEmail(),
                        'active'     => true,
                        'github_id'  => $githubUser->getId(),
                        // 'password'   => bcrypt('123456dummy'),
                    ]);
                }
            }
            // Gán quyền 'staff' nếu chưa có quyen
            if ($finduser->roles()->count() === 0) {
                $finduser->assignRole('user');
            }

            // Đăng nhập
            Auth::login($finduser);
            if (!$finduser->hasAnyRole(['admin', 'staff'])) {
                Auth::logout();
                return redirect('/admin/login')->with('error', 'Tài khoản của bạn chưa được cấp quyền truy cập trang quản trị.');
            }

            // Gọi merge sau khi login
            app(\App\Repositories\Cart\CartRepositoryInterface::class)
                ->mergeCart(Auth::id(), $oldSessionId);

            app(\App\Repositories\UserRecentProduct\UserRecentProductRepositoryInterface::class)
                ->mergeRecentViewed(Auth::id(), $oldSessionId);

            session(['cart_merged' => true]);

            return redirect('/admin/products')->with('success', 'Đăng nhập Github (admin) thành công!');
        } catch (Exception $e) {
            return redirect('/admin/login')->with('error', 'Đăng nhập Github thất bại: ' . $e->getMessage());
        }
    }
}
