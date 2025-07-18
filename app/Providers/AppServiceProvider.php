<?php

namespace App\Providers;

use App\Repositories\Brand\BrandRepository;
use App\Repositories\Brand\BrandRepositoryInterface;
use App\Repositories\Cart\CartRepository;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Dashboard\DashboardRepository;
use App\Repositories\Dashboard\DashboardRepositoryInterface;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\ProductVariant\ProductVariantRepository;
use App\Repositories\ProductVariant\ProductVariantRepositoryInterface;
use App\Repositories\UserRecentProduct\UserRecentProductRepository;
use App\Repositories\UserRecentProduct\UserRecentProductRepositoryInterface;
use App\Repositories\Voucher\VoucherRepository;
use App\Repositories\Voucher\VoucherRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BrandRepositoryInterface::class, BrandRepository::class);

        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);

        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);

        $this->app->bind(ProductVariantRepositoryInterface::class, ProductVariantRepository::class);

        $this->app->bind(
            CartRepositoryInterface::class,
            CartRepository::class
        );

        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);

        $this->app->bind(
            UserRecentProductRepositoryInterface::class,
            UserRecentProductRepository::class
        );

        $this->app->bind(VoucherRepositoryInterface::class, VoucherRepository::class);

        $this->app->bind(DashboardRepositoryInterface::class, DashboardRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $cartRepo = app(CartRepositoryInterface::class);
            $userId = Auth::id();
            $sessionId = session()->getId();

            $cart = $cartRepo->getUserCart($userId, $sessionId);
            $totalQty = $cart ? $cart->items->sum('quantity') : 0;

            $view->with('cartQty', $totalQty);
        });
    }
}
