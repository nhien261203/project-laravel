<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariantImage;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;

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
            $storages = $product->variants
                ->pluck('storage')
                ->unique()
                ->filter()
                ->values()
                ->map(fn($s) => strtoupper($s))
                ->implode(' / ');

            $product->all_storages = $storages;
        }
        // Lấy banner cho trang chủ
        $banners = Banner::where('status', 1)
            ->latest('id')
            ->limit(4)
            ->get();

        return view('layout.user', compact(
            //'categoriesWithChildren',
            'banners',
            'iphoneProducts'
            // 'parentCategories'
        ));
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


    public function show($slug)
    {
        $product = Product::with(['variants.images'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Nhóm các thuộc tính (màu, bộ nhớ)
        $colors = $product->variants->pluck('color')->unique()->filter();
        $storages = $product->variants->pluck('storage')->unique()->filter();

        // Lấy ảnh đầu tiên nếu variant không có ảnh
        foreach ($product->variants as $variant) {
            if ($variant->images->isEmpty()) {
                $fallback = $product->variants->firstWhere(fn($v) => !$v->images->isEmpty());
                if ($fallback) {
                    $variant->fallback_image = $fallback->images->first()->image_path;
                }
            }
        }

        return view('user.product.detail', compact('product', 'colors', 'storages'));
    }
}
