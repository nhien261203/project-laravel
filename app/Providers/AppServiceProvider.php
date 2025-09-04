<?php

namespace App\Providers;

use App\Models\Category;
use App\Repositories\Brand\BrandRepository;
use App\Repositories\Brand\BrandRepositoryInterface;
use App\Repositories\Cart\CartRepository;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Dashboard\DashboardRepository;
use App\Repositories\Dashboard\DashboardRepositoryInterface;
use App\Repositories\Favorite\FavoriteRepository;
use App\Repositories\Favorite\FavoriteRepositoryInterface;
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
        $this->app->bind(FavoriteRepositoryInterface::class, FavoriteRepository::class);
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


        // lấy ra các danh mục để hiển thị ở header
        View::composer('components.header', function ($view) {
            // Lấy danh mục phụ kiện để loại trừ
            $accessory = Category::where('slug', 'phu-kien')->first();

            // Lấy các danh mục chính (cấp cha) và chỉ tải các danh mục con có status = 1
            $categories = Category::where('status', 1)
                ->whereNull('parent_id') // Chỉ lấy danh mục cha
                ->when($accessory, function ($q) use ($accessory) {
                    // Loại bỏ danh mục "phụ kiện" ra khỏi danh sách chính
                    $q->where('id', '!=', $accessory->id);
                })
                // Thêm một closure vào with() để chỉ tải các danh mục con có status = 1
                ->with(['children' => function ($query) {
                    $query->where('status', 1);
                }])
                ->get();

            // Lấy danh mục phụ kiện và các con của nó riêng biệt, chỉ lấy các con có status = 1
            $accessoryWithChildren = null;
            if ($accessory) {
                $accessoryWithChildren = Category::where('status', 1)
                    ->with(['children' => function ($query) {
                        $query->where('status', 1);
                    }])
                    ->find($accessory->id);
            }

            $view->with([
                'categories' => $categories,
                'accessory' => $accessoryWithChildren,
            ]);
        });
    }
}
