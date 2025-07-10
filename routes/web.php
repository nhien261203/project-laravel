<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ForgotPasswordController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\UserBlogController;
use App\Http\Controllers\User\UserCommentController;
use App\Http\Controllers\User\UserProductController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

// Trang chủ – cho tất cả (user cũng vào được)
// Route::middleware('auto.merge.cart')->group(function () {
   
// });


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dien-thoai', [UserProductController::class, 'phoneCategory'])->name('product.phone');
Route::get('/laptop', [UserProductController::class, 'laptopCategory'])->name('product.laptop');

Route::get('/phu-kien', [UserProductController::class, 'accessoryCategory'])->name('product.accessory');

Route::get('/phu-kien/phu-kien-dong', [UserProductController::class, 'mobileAccessory'])->name('product.accessory.mobile');
Route::get('/phu-kien/phu-kien-am-thanh', [UserProductController::class, 'audioAccessory'])->name('product.accessory.audio');


Route::get('/products/{slug}', [HomeController::class, 'show'])->name('product.detail');

Route::get('/phu-kien/{slug}', [HomeController::class, 'showAccessory'])->name('product.detailAccessory');

// blog cho user
Route::get('/blogs', [UserBlogController::class, 'index'])->name('blogs.index');
Route::get('/blog/{slug}', [UserBlogController::class, 'show'])->name('blogs.show');

Route::middleware('auth')->post('/comments', [UserCommentController::class, 'store'])->name('comments.store');


Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::put('/update/{variantId}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{variantId}', [CartController::class, 'remove'])->name('remove');
});

//  Admin - toàn quyền (chỉ admin, staff dung permission chặn 1 số quyền ở controller va view)
Route::middleware(['auth', 'role:admin|staff'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

    Route::resource('brands', BrandController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);

    Route::resource('banners', BannerController::class);

    Route::resource('blogs', BlogController::class);

    Route::post('blogs/upload', [BlogController::class, 'upload'])->name('blogs.upload');

    Route::get('/logs', [\App\Http\Controllers\Admin\AdminLogController::class, 'index'])
        ->middleware('can:view log')
        ->name('logs.index');


    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('/comments/{id}/approve', [CommentController::class, 'approve'])->name('comments.approve');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');

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

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');



    // Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('password.form');
    // Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change');

});

// Staff – chỉ vào sản phẩm
// Route::middleware(['auth', 'role:staff|admin'])->prefix('admin')->name('admin.')->group(function () {
//     Route::resource('products', ProductController::class)->only(['index', 'create', 'store', 'edit', 'update']);
// });

// Auth cho user 
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// 🟩 Quên mật khẩu


//auth cho admin 



Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::get('/register', [AdminAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AdminAuthController::class, 'register']);

    // Đổi mật khẩu
   

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
    
    Route::middleware(['auth'])->group(function () {
        Route::get('/change-password', [AdminAuthController::class, 'showChangePassword'])->name('password.form');
        Route::post('/change-password', [AdminAuthController::class, 'changePassword'])->name('password.change');
    });
});


// 🟩 Test gửi mail
Route::get('/test-mail', function () {
    Mail::raw('Test email from Laravel', function ($msg) {
        $msg->to('youremail@gmail.com')->subject('Test Mail');
    });
    return 'Gửi mail xong!';
});
