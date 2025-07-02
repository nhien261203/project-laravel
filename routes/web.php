<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('layout.user');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

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
// auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');


//test gui mail
Route::get('/test-mail', function () {
    Mail::raw('Test email from Laravel', function ($msg) {
        $msg->to('youremail@gmail.com')->subject('Test Mail');
    });

    return 'Gửi mail xong!';
});