<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\ProductVariantImage;
use App\Repositories\Category\CategoryRepositoryInterface;

class HomeController extends Controller
{
    protected $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
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

        // Lấy banner cho trang chủ
        $banners = Banner::where('status', 1)
            ->latest('id')
            ->limit(4)
            ->get();

        return view('layout.user', compact(
            //'categoriesWithChildren',
            'banners',
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
}
