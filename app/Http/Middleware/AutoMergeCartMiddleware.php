<?php

namespace App\Http\Middleware;

use App\Repositories\Cart\CartRepositoryInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AutoMergeCartMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected $cartRepo;

    public function __construct(CartRepositoryInterface $cartRepo)
    {
        $this->cartRepo = $cartRepo;
    }

    public function handle(Request $request, Closure $next)
    {
        // Nếu chưa đăng nhập hoặc đã merge rồi thì bỏ qua
        if (!Auth::check() || session()->has('cart_merged')) {
            return $next($request);
        }

        $userId = Auth::id();
        $sessionId = $request->session()->getId();

        // Gọi hàm mergeCart trong repository 
        $this->cartRepo->mergeCart($userId, $sessionId);

        // Đánh dấu đã merge
        session(['cart_merged' => true]);

        return $next($request);
    }
}
