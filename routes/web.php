<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

// Trang chủ – cho tất cả (user cũng vào được)
Route::get('/', function () {
    return view('layout.user');
})->middleware('role:user|staff|admin');

// 🟩 Admin - toàn quyền (chỉ admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

    Route::resource('brands', BrandController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);

    Route::prefix('products/{product}/variants')->name('products.variants.')->group(function () {
        Route::get('/', [ProductVariantController::class, 'index'])->name('index');
        Route::post('/', [ProductVariantController::class, 'store'])->name('store');
        Route::get('create', [ProductVariantController::class, 'create'])->name('create');
        Route::get('{variant}/edit', [ProductVariantController::class, 'edit'])->name('edit');
        Route::put('{variant}', [ProductVariantController::class, 'update'])->name('update');
        Route::delete('{variant}', [ProductVariantController::class, 'destroy'])->name('destroy');
        Route::get('{variant}', [ProductVariantController::class, 'show'])->name('show');
        Route::delete('images/{image}', [ProductVariantController::class, 'deleteImage'])->name('images.delete');
    });
});

// 🟩 Staff – chỉ vào sản phẩm
Route::middleware(['auth', 'role:staff|admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class)->only(['index', 'create', 'store', 'edit', 'update']);
});

// 🟩 Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// 🟩 Quên mật khẩu
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// 🟩 Test gửi mail
Route::get('/test-mail', function () {
    Mail::raw('Test email from Laravel', function ($msg) {
        $msg->to('youremail@gmail.com')->subject('Test Mail');
    });
    return 'Gửi mail xong!';
});
