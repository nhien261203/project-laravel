<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Brand\BrandRepositoryInterface;
use Illuminate\Http\Request;

class UserProductController extends Controller
{
    protected $productRepo;
    protected $brandRepo;

    public function __construct(
        ProductRepositoryInterface $productRepo,
        BrandRepositoryInterface $brandRepo
    ) {
        $this->productRepo = $productRepo;
        $this->brandRepo = $brandRepo;
    }

    protected function renderCategoryPage(Request $request, string $slug, string $viewName)
    {
        $category = Category::where('slug', $slug)
            ->where('status', 1)
            ->with(['children' => function ($query) {
                $query->where('status', 1);
            }])
            ->firstOrFail();

        // Lấy tất cả ID của danh mục hiện tại và danh mục con
        $categoryIds = Category::where('id', $category->id)
            ->orWhere('parent_id', $category->id)
            ->pluck('id');

        $products = $this->productRepo->getProductsByCategorySlug($slug);
        $brands = $this->brandRepo->getBrandsByCategorySlug($slug);

        $rams     = $this->productRepo->getRamsByCategoryIds($categoryIds);
        $storages = $this->productRepo->getStoragesByCategoryIds($categoryIds);

        // Lấy danh mục cha (nếu có) để tạo breadcrumbs
        $parentCategory = $category->parent;

        if ($request->ajax()) {
            return view('components.product_list', compact('products'))->render();
        }


        return view($viewName, compact('products', 'brands', 'category', 'parentCategory', 'rams', 'storages'));
    }

    public function renderAccessoryPage(Request $request, string $slug)
    {
        $category = Category::where('slug', $slug)
            ->where('status', 1)
            ->with(['children' => fn($q) => $q->where('status', 1)])
            ->firstOrFail();

        $categoryIds = Category::where('id', $category->id)
            ->orWhere('parent_id', $category->id)
            ->pluck('id');

        // Lấy sản phẩm theo category, phân trang 8 / page
        $accessories = $this->productRepo->getProductsByCategorySlug($slug); // ->paginate() ở repo

        $brands = $this->brandRepo->getBrandsByCategorySlug($slug);

        $rams     = $this->productRepo->getRamsByCategoryIds($categoryIds);
        $storages = $this->productRepo->getStoragesByCategoryIds($categoryIds);

        $parentCategory = $category->parent;

        if ($request->ajax()) {
            return view('components.accessory_list', compact('accessories'))->render();
        }

        return view('user.product.accessory', compact(
            'accessories',
            'brands',
            'category',
            'parentCategory',
            'rams',
            'storages'
        ));
    }

    /**
     * Route cho danh mục phụ kiện cố định
     */
    public function accessoryCategory(Request $request)
    {
        return $this->renderAccessoryPage($request, 'phu-kien');
    }

    public function showCategory(Request $request, string $slug)
    {

        return $this->renderCategoryPage($request, $slug, 'user.product.phone');
    }

    public function showAccessoryCategory(Request $request, string $slug)
    {
        return $this->renderAccessoryPage($request, $slug);
    }



    // public function phoneCategory(Request $request)
    // {
    //     return $this->renderCategoryPage($request, 'dien-thoai', 'user.product.phone');
    // }

    // public function laptopCategory()
    // {
    //     return $this->renderCategoryPage('laptop', 'user.product.laptop');
    // }

    // public function accessoryCategory(Request $request)
    // {
    //     return $this->renderCategoryPage($request, 'phu-kien', 'user.product.accessory');
    // }

    // public function mobileAccessory()
    // {
    //     return $this->renderCategoryPage('phu-kien-di-dong', 'user.product.accessory_mobile');
    // }

    // public function audioAccessory()
    // {
    //     return $this->renderCategoryPage('thiet-bi-am-thanh', 'user.product.accessory_audio');
    // }

    // tim kiem tren header
    public function search(Request $request)
    {
        $keyword = $request->input('q');

        // Có thể trim keyword để tránh khoảng trắng dư thừa
        $keyword = trim($keyword);

        // Nếu muốn, có thể kiểm tra keyword rỗng và xử lý trước khi search
        if (empty($keyword)) {
            return redirect()->back()->with('error', 'Vui lòng nhập từ khóa tìm kiếm.');
        }

        // Gọi repo search với phân trang (mặc định 12 sản phẩm / trang)
        $products = $this->productRepo->searchProducts($keyword);

        // Trả về view với $products là LengthAwarePaginator có phân trang
        return view('user.search_result', compact('products', 'keyword'));
    }
    public function searchSuggest(Request $request)
    {
        $keyword = $request->q;

        if (!$keyword) return response()->json([]);

        $products = $this->productRepo->searchProducts($keyword, 5); // giới hạn 5 kết quả

        $data = $products->map(fn($p) => [
            'name' => $p->name,
            'slug' => $p->slug,
            'price' => $p->variants->first()?->price ?? 0,
            'original_price' => $p->variants->first()?->original_price ?? 0,
            'sale_percent' => $p->sale_percent ?? 0,
            'image' => $p->variants->first()?->images->first()?->image_path ?? null,
            'category_slug' => $p->category->slug ?? null, // thêm category slug
        ]);


        return response()->json($data);
    }
}
