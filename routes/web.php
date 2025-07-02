<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
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
    return view('layout.admin');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('brands', BrandController::class);
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class);
});


Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class);
});

Route::prefix('admin/products/{product}/variants')->name('admin.products.variants.')->group(function () {
    Route::get('/', [ProductVariantController::class, 'index'])->name('index'); // danh sach 
    Route::post('/', [ProductVariantController::class, 'store'])->name('store'); // luu moi

    Route::get('create', [ProductVariantController::class, 'create'])->name('create'); // ➕ Form tạo
    Route::get('{variant}/edit', [ProductVariantController::class, 'edit'])->name('edit');
    Route::put('{variant}', [ProductVariantController::class, 'update'])->name('update');
    Route::delete('{variant}', [ProductVariantController::class, 'destroy'])->name('destroy');
    Route::get('{variant}', [ProductVariantController::class, 'show'])->name('show'); // hiển thị chi tiết biến thể

    Route::delete('images/{image}', [ProductVariantController::class, 'deleteImage'])->name('images.delete');

   
});
