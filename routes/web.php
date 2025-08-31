<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminContactController;
use App\Http\Controllers\Admin\AdminGoogleController;
use App\Http\Controllers\Admin\AdminLogController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\DashBoardController;
use App\Http\Controllers\Admin\ForgotPasswordController;
use App\Http\Controllers\Admin\GithubController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductExportController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VoucherController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\PusherController;
use App\Http\Controllers\User\CompareController;
use App\Http\Controllers\User\FavoriteController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\UserBlogController;
use App\Http\Controllers\User\UserCommentController;
use App\Http\Controllers\User\UserContactController;
use App\Http\Controllers\User\UserForgotPasswordController;
use App\Http\Controllers\User\UserGoogleController;
use App\Http\Controllers\User\UserOrderController;
use App\Http\Controllers\User\UserProductController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\User\UserRecentProductController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Trang chủ – cho tất cả (user cũng vào được)
// Route::middleware('auto.merge.cart')->group(function () {

// });


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dien-thoai', [UserProductController::class, 'phoneCategory'])->name('product.phone');
Route::get('/laptop', [UserProductController::class, 'laptopCategory'])->name('product.laptop');

Route::get('/phu-kien', [UserProductController::class, 'accessoryCategory'])->name('product.accessory');

Route::get('/phu-kien/phu-kien-di-dong', [UserProductController::class, 'mobileAccessory'])->name('product.accessory.mobile');
Route::get('/phu-kien/thiet-bi-am-thanh', [UserProductController::class, 'audioAccessory'])->name('product.accessory.audio');


Route::get('/products/{slug}', [HomeController::class, 'show'])->name('product.detail');

Route::get('/phu-kien/{slug}', [HomeController::class, 'showAccessory'])->name('product.detailAccessory');

// xem tất cả sản phẩm iPhone
Route::get('/iphone', [HomeController::class, 'allIphone'])->name('user.iphone.all');


// nut tim kiem tren header 
Route::get('/search', [UserProductController::class, 'search'])->name('product.search');
Route::get('/search-suggest', [UserProductController::class, 'searchSuggest'])->name('product.searchSuggest');


// blog cho user
Route::get('/blogs', [UserBlogController::class, 'index'])->name('blogs.index');
Route::get('/blog/{slug}', [UserBlogController::class, 'show'])->name('blogs.show');

Route::get('/about', function () {
    return view('user.about');
})->name('about');

Route::get('/contact', [UserContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [UserContactController::class, 'submit'])->name('contact.submit');

Route::middleware('auth')->post('/comments', [UserCommentController::class, 'store'])->name('comments.store');

Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
Route::post('/favorites', [FavoriteController::class, 'store']);
// Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy']);
Route::delete('/favorites/by-product/{productId}', [FavoriteController::class, 'destroyByProduct']);
Route::get('/favorites/count', [FavoriteController::class, 'count'])->name('favorites.count');
// Route::post('/favorites/toggle/{productId}', [FavoriteController::class, 'toggle']);

Route::post('/viewed-products/{product}', [UserRecentProductController::class, 'store'])->name('recently.viewed.store');
// web.php
Route::get('so-sanh/{slug}', [CompareController::class, 'compare'])->name('product.compare');


// Route::post('/viewed-products/sync', [UserRecentProductController::class, 'sync'])->middleware('auth');

Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::post('reviews/store', [ReviewController::class, 'store'])->name('reviews.store');
});

Route::middleware(['auth'])->name('user.')->group(function () {
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
    Route::get('/edit', [UserProfileController::class, 'edit'])->name('edit');
    Route::post('/update', [UserProfileController::class, 'update'])->name('update');
});

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::put('/update/{variantId}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{variantId}', [CartController::class, 'remove'])->name('remove');

    // su dung check auth trong controller ( nen k co middleware auth o day)
    Route::post('/apply-voucher', [CartController::class, 'applyVoucher'])->name('apply-voucher');
    Route::post('/remove-voucher', [CartController::class, 'removeVoucher'])->name('remove-voucher');

    Route::get('/count', [CartController::class, 'count'])->name('count');


});

Route::middleware(['auth'])->prefix('orders')->name('user.orders.')->group(function () {
    Route::get('/', [UserOrderController::class, 'index'])->name('index');
    Route::get('/{id}', [UserOrderController::class, 'show'])->name('show');
    Route::post('/place', [UserOrderController::class, 'store'])->name('store'); // Đặt hàng từ giỏ hàng
    Route::post('/{id}/cancel', [UserOrderController::class, 'cancel'])->name('cancel');

    Route::get('/{id}/payment/vnpay', [UserOrderController::class, 'vnpayPayment'])->name('vnpay');
    Route::get('/vnpay/return', [UserOrderController::class, 'vnpayReturn'])->name('vnpay.return');


});
// Route::post('/vnpay-payment', [PaymentController::class, 'vnpayPayment'])->name('vnpay_payment')->middleware('auth');

//  Admin - toàn quyền (chỉ admin, staff dung permission chặn 1 số quyền ở controller va view)
Route::middleware(['auth', 'role:admin|staff'])->prefix('admin')->name('admin.')->group(function () {
    // Hiển thị giao diện dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/statistics', [DashboardController::class, 'getStatistics'])->name('dashboard.statistics');
    Route::get('/dashboard/category-pie', [DashBoardController::class, 'getCategoryPieChart']);

    Route::get('/dashboard/monthly-summary', [DashBoardController::class, 'getMonthlySummary'])->name('dashboard.monthly-summary');

    Route::get('/dashboard/top-products', [DashBoardController::class, 'getTopSellingProducts']);

    Route::get('/dashboard/monthly-orders', [DashBoardController::class, 'getMonthlyOrderSummary']);

    Route::get('/dashboard/monthly-top-products', [DashboardController::class, 'monthlyTopProducts']);

    
    // Route::get('/dashboard/stock-alert', [DashBoardController::class, 'stockAlert'])
    //     ->name('dashboard.stock-alert');

    Route::get('/stock-all', [DashBoardController::class, 'stockAll'])
        ->name('stock-all');

    Route::resource('brands', BrandController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);

    Route::resource('banners', BannerController::class);

    Route::resource('tags', TagController::class);

    Route::resource('blogs', BlogController::class);

    Route::resource('users', UserController::class);
    Route::patch('users/{id}/toggle-active', [UserController::class, 'toggleActiveStatus'])->name('users.toggle-active');


    Route::post('blogs/upload', [BlogController::class, 'upload'])->name('blogs.upload');

    Route::resource('vouchers', VoucherController::class);


    Route::get('/logs', [AdminLogController::class, 'index'])
        ->middleware('role:admin')
        ->name('logs.index');


    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('/comments/{id}/approve', [CommentController::class, 'approve'])->name('comments.approve');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{id}/unapprove', [CommentController::class, 'unapprove'])->name('comments.unapprove');
    Route::post('comments/{id}/reject', [CommentController::class, 'reject'])->name('comments.reject'); 

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

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::post('/{id}/update-status', [OrderController::class, 'updateStatus'])->name('updateStatus');
        Route::delete('/{id}', [OrderController::class, 'destroy'])->name('destroy');
    });

    Route::resource('reviews', AdminReviewController::class)->only(['index', 'destroy']);
    Route::post('reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('reviews/{review}/unapprove', [AdminReviewController::class, 'unapprove'])->name('reviews.unapprove');
    Route::post('reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
    // Route::post('reviews/{review}/update-status', [AdminReviewController::class, 'updateStatus'])->name('reviews.updateStatus');

    Route::get('/contacts', [AdminContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{contact}', [AdminContactController::class, 'show'])->name('contacts.show');
    Route::patch('/contacts/{contact}/mark-replied', [AdminContactController::class, 'markReplied'])->name('contacts.mark-replied');

    Route::patch('contacts/{contact}/mark-unreplied', [AdminContactController::class, 'markUnreplied'])
        ->name('contacts.mark-unreplied');

    Route::delete('/contacts/{contact}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');

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

Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('password.form');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change');
});

// Quên mật khẩu
Route::prefix('password')->name('password.')->group(function () {
    Route::get('forgot', [UserForgotPasswordController::class, 'showForm'])->name('request');
    Route::post('forgot', [UserForgotPasswordController::class, 'sendResetLink'])->name('email');
    Route::get('reset', [UserForgotPasswordController::class, 'showResetForm'])->name('reset');
    Route::post('reset', [UserForgotPasswordController::class, 'resetPassword'])->name('update');
});



//auth gg
Route::get('auth/google', [UserGoogleController::class, 'redirectToGoogle'])->name('redirect.google');
Route::get('auth/google/callback',[UserGoogleController::class, 'handleGoogleCallback']);

// Route::get('admin/auth/google', [AdminGoogleController::class, 'redirectToGoogle'])->name('admin.redirect.google');
// Route::get('admin/auth/google/callback', [AdminGoogleController::class, 'handleGoogleCallbackForAdmin']);

//auth github admin
Route::get('admin/auth/github', [GithubController::class, 'redirectToGithub'])->name('admin.redirect.github');
Route::get('admin/auth/github/callback', [GithubController::class, 'handleGithubCallback']);

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


//  Test gửi mail
Route::get('/test-mail', function () {
    Mail::raw('Test email from Laravel', function ($msg) {
        $msg->to('youremail@gmail.com')->subject('Test Mail');
    });
    return 'Gửi mail xong!';
});


// xoa cart dinh ky
// Route::get('/clear-cart-data', function () {
//     DB::statement('SET FOREIGN_KEY_CHECKS=0;');

//     DB::table('cart_items')->truncate();
//     DB::table('carts')->truncate();

//     DB::statement('SET FOREIGN_KEY_CHECKS=1;');

//     return 'Đã xoá toàn bộ dữ liệu carts và cart_items.';
// });



Route::get('/admin/export/products/txt', [ProductExportController::class, 'exportToTxt'])->name('admin.products.export.txt');
Route::get('/admin/export/products/pdf', [ProductExportController::class, 'exportToPdf'])->name('admin.products.export.pdf');

Route::get('/admin/export/orders/pdf', [OrderController::class, 'exportPdf'])->name('admin.orders.export.pdf');

Route::post('/chatbot/ask', [ChatBotController::class, 'ask']);
Route::get('/chatbot/history', [ChatBotController::class, 'history']);
Route::get('/chat', function () {
    return view('chat');
});

Route::get('/chat/pusher', [PusherController::class, 'index'])
    ->middleware('auth');

// User gửi tin nhắn
Route::post('/send-message', [PusherController::class, 'sendMessage'])->name('send.message')
    ->middleware('auth');

// Lấy lịch sử tin nhắn conversation hiện tại
Route::get('/messages/last', [PusherController::class, 'getLastMessage'])->name('chat.last')
    ->middleware('auth');

// Lấy tất cả tin nhắn của conversation
Route::get('/messages/{conversation_id}', [PusherController::class, 'getMessages'])
    ->middleware('auth');

// Admin lấy danh sách conversation
Route::get('/admin/conversations', [PusherController::class, 'getConversations'])
    ->middleware('auth');

// Admin/staff gửi tin nhắn trả lời
Route::post('/admin/send-reply', [PusherController::class, 'sendReply'])
    ->middleware('auth');

Route::middleware(['auth', 'role:admin|staff'])->group(function () {
    Route::get('/admin/chat', [PusherController::class, 'adminIndex'])->name('chat.admin.index');
});


