<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariantImage;
use App\Models\UserRecentProduct;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $categoryRepo;
    protected $productRepo;

    public function __construct(
        CategoryRepositoryInterface $categoryRepo,
        ProductRepositoryInterface $productRepo
    ) {
        $this->categoryRepo = $categoryRepo;
        $this->productRepo = $productRepo;
    }



    public function index()
    {
        //Lấy danh mục cho header (bao gồm cả children)
        // $categoriesWithChildren = $this->categoryRepo->getWithChildren();

        // Lọc các danh mục cha
        //$parentCategories = $categoriesWithChildren->where('parent_id', null);

        // Gắn ảnh đại diện cho từng danh mục cha
        // foreach ($parentCategories as $category) {
        //     $category->display_image = $this->getCategoryThumbnail($category);
        // }

        $iphoneProducts = $this->productRepo->getIphoneProducts(5);


        foreach ($iphoneProducts as $product) {

            $firstVariant = $product->variants->first();

            $storages = $product->variants
                ->pluck('storage')
                ->unique()
                ->filter()
                ->values()
                ->map(fn($s) => strtoupper($s))
                ->implode(' / ');


            $product->all_storages = $storages;

            $product->sale_percent = $firstVariant?->sale_percent ?? 0;
        }


        $laptopProducts = $this->productRepo->getLaptopProducts(5);

        foreach ($laptopProducts as $product) {
            $firstVariant = $product->variants->first();
            $storages = $product->variants
                ->pluck('storage')
                ->unique()
                ->filter()
                ->values()
                ->map(fn($s) => strtoupper($s))
                ->implode(' / ');

            $product->all_storages = $storages;
            $product->sale_percent = $firstVariant?->sale_percent ?? 0;
        }


        $accessoryProducts = $this->productRepo->getAccessoryProducts(5);

        foreach ($accessoryProducts as $product) {
            $firstVariant = $product->variants->first();
            $storages = $product->variants
                ->pluck('storage')
                ->unique()
                ->filter()
                ->values()
                ->map(fn($s) => strtoupper($s))
                ->implode(' / ');

            $product->all_storages = $storages;
            $product->sale_percent = $firstVariant?->sale_percent ?? 0;
        }

        // Lấy banner cho trang chủ
        $banners = Banner::where('status', 1)
            ->latest('id')
            ->limit(4)
            ->get();

        $latestBlogs = Blog::where('status', 1)
            ->latest('id')
            ->limit(4)
            ->get();

        return view('user.home', compact(
            //'categoriesWithChildren',
            'banners',
            'iphoneProducts',
            'laptopProducts',
            'latestBlogs',
            'accessoryProducts',
            // 'parentCategories'
        ));
    }

    public function allIphone(Request $request)
    {
        // Lấy tất cả iPhone products (không phân trang)
        $iphoneProducts = $this->productRepo->getAllIphoneProducts();

        // Thêm các field extras: all_storages và sale_percent
        foreach ($iphoneProducts as $product) {
            $firstVariant = $product->variants->first();
            $storages = $product->variants
                ->pluck('storage')
                ->unique()
                ->filter()
                ->values()
                ->map(fn($s) => strtoupper($s))
                ->implode(' / ');

            $product->all_storages = $storages;
            $product->sale_percent = $firstVariant?->sale_percent ?? 0;
        }

        // Lấy tất cả RAM và Storage cho filter
        $rams     = $this->productRepo->getAllIphoneRams();
        $storages = $this->productRepo->getAllIphoneStorages();

        // Nếu là AJAX request, trả về HTML component
        if ($request->ajax()) {
            return view('components.Iphone_list', ['products' => $iphoneProducts])->render();
        }

        // Nếu không phải AJAX, trả về view đầy đủ
        return view('user.product.all-iphone', compact('iphoneProducts', 'rams', 'storages'));
    }


    /**
     * Trả về đường dẫn ảnh đại diện đầu tiên từ danh mục
     * Ưu tiên danh mục cha → nếu không có thì duyệt qua danh mục con
     */
    // private function getCategoryThumbnail(Category $category): ?string
    // {
    //     // Ưu tiên ảnh từ danh mục cha
    //     $imagePath = $this->getFirstVariantImageFromCategory($category);

    //     // Nếu không có ảnh, duyệt qua các danh mục con
    //     if (!$imagePath) {
    //         foreach ($category->children as $child) {
    //             $imagePath = $this->getFirstVariantImageFromCategory($child);
    //             if ($imagePath) {
    //                 break;
    //             }
    //         }
    //     }

    //     return $imagePath ? asset('storage/' . $imagePath) : null;
    // }

    // /**
    //  * Trả về ảnh đầu tiên từ biến thể của sản phẩm đầu tiên thuộc danh mục
    //  */
    // private function getFirstVariantImageFromCategory(Category $category): ?string
    // {
    //     $product = $category->products()
    //         ->with(['variants.images'])
    //         ->first();

    //     if (!$product) return null;

    //     $variant = $product->variants->first();
    //     if (!$variant) return null;

    //     $image = $variant->images->first();
    //     return $image?->image_path;
    // }



    // chi tiet sp trang home
    public function show($slug)
    {
        // inStock goi tu phuong thuc scope ben Model ProductVariant
        $product = Product::with([
            'variants' => fn($q) => $q->inStock()->where('status', 1)->with('images'),
            // 'approvedReviews.user',
            'brand',
            'category.parent' // thêm parent
        ])->where('slug', $slug)->firstOrFail();

        $approvedReviews = $product->approvedReviews()
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(5);

        // Các biến khác vẫn giữ nguyên
        $totalSold = $product->variants->sum('sold');
        $totalReviews = $approvedReviews->total(); // lấy tổng từ phân trang
        $averageRating = $totalReviews > 0 ? round($product->approvedReviews()->avg('rating'), 1) : 0;

        $colors = $product->variants->pluck('color')->unique()->filter();
        $storages = $product->variants->pluck('storage')->unique()->filter();

        $fallbackVariantWithImage = $product->variants->firstWhere(fn($v) => $v->images->isNotEmpty());

        foreach ($product->variants as $variant) {
            if ($variant->images->isEmpty() && $fallbackVariantWithImage) {
                $variant->fallback_image = $fallbackVariantWithImage->images->first()->image_path;
            }
        }

        $recentQuery = UserRecentProduct::query()
            ->where('product_id', '!=', $product->id)
            ->orderByDesc('viewed_at');

        if (auth()->check()) {
            $recentQuery->where('user_id', auth()->id());
        } else {
            $recentQuery->where('session_id', session()->getId());
        }

        $recentIds = $recentQuery->limit(10)->pluck('product_id');

        $recentlyViewed = Product::with(['variants.images', 'category.parent']) // thêm parent
            ->whereIn('id', $recentIds)
            ->get()
            ->map(function ($p) use ($recentIds) {
                $p->is_accessory = $p->category?->slug === 'phu-kien' || $p->category?->parent?->slug === 'phu-kien';
                return $p;
            })
            ->sortBy(fn($p) => array_search($p->id, $recentIds->toArray()));

        $canReview = false;

        if (auth()->check()) {
            $hasReviewed = $product->reviews->where('user_id', auth()->id())->count() > 0;

            if (!$hasReviewed) {
                $hasPurchased = Order::where('user_id', auth()->id())
                    ->whereIn('status', ['completed'])
                    ->whereHas('items.variant', function ($q) use ($product) {
                        $q->where('product_id', $product->id);
                    })
                    ->exists();

                $canReview = $hasPurchased;
            }
        }

        // Lấy category & parent
        $category = $product->category;
        $parent = $category?->parent;

        return view('user.product.detail', compact('product', 'approvedReviews', 'colors', 'storages', 'recentlyViewed', 'canReview', 'totalSold', 'totalReviews', 'averageRating', 'parent', 'category'));
    }

    public function showAccessory($slug)
    {
        $product = Product::with([
            'variants' => fn($q) => $q->inStock()->where('status', 1)->with('images'),
            // 'approvedReviews.user',
            'brand',
            'category.parent'
        ])->where('slug', $slug)->firstOrFail();

        $approvedReviews = $product->approvedReviews()
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(5);

        $totalSold = $product->variants->sum('sold');
        $totalReviews = $approvedReviews->total(); // lấy tổng từ phân trang
        $averageRating = $totalReviews > 0 ? round($product->approvedReviews()->avg('rating'), 1) : 0;

        $colors = $product->variants->pluck('color')->unique()->filter();
        $storages = $product->variants->pluck('storage')->unique()->filter();

        $fallbackVariantWithImage = $product->variants->firstWhere(fn($v) => $v->images->isNotEmpty());

        foreach ($product->variants as $variant) {
            if ($variant->images->isEmpty() && $fallbackVariantWithImage) {
                $variant->fallback_image = $fallbackVariantWithImage->images->first()->image_path;
            }
        }

        $recentQuery = UserRecentProduct::query()
            ->where('product_id', '!=', $product->id)
            ->orderByDesc('viewed_at');

        if (auth()->check()) {
            $recentQuery->where('user_id', auth()->id());
        } else {
            $recentQuery->where('session_id', session()->getId());
        }

        $recentIds = $recentQuery->limit(10)->pluck('product_id');

        $recentlyViewed = Product::with(['variants.images', 'category.parent']) // thêm parent
            ->whereIn('id', $recentIds)
            ->get()
            ->map(function ($p) use ($recentIds) {
                $p->is_accessory = $p->category?->slug === 'phu-kien' || $p->category?->parent?->slug === 'phu-kien';
                return $p;
            })
            ->sortBy(fn($p) => array_search($p->id, $recentIds->toArray()));

        $canReview = false;

        if (auth()->check()) {
            $hasReviewed = $product->reviews->where('user_id', auth()->id())->count() > 0;

            if (!$hasReviewed) {
                $hasPurchased = Order::where('user_id', auth()->id())
                    ->whereIn('status', ['completed'])
                    ->whereHas('items.variant', function ($q) use ($product) {
                        $q->where('product_id', $product->id);
                    })
                    ->exists();


                $canReview = $hasPurchased;
            }
        }
        $category = $product->category;
        $parent = $category?->parent;

        return view('user.product.detail-accessory', compact('product', 'approvedReviews', 'colors', 'storages', 'recentlyViewed', 'canReview', 'totalSold', 'totalReviews', 'averageRating', 'category', 'parent'));
    }
}
