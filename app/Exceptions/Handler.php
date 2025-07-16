<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;


class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    // public function unauthenticated($request, AuthenticationException $exception)
    // {
    //     // Nếu là request từ trình duyệt (không phải API)
    //     if ($request->expectsJson()) {
    //         return response()->json(['message' => 'Bạn cần đăng nhập.'], 401);
    //     }

    //     return redirect()->guest(route('login'))->with('error', 'Bạn cần đăng nhập trước khi thực hiện hành động này.');
    // }
}
