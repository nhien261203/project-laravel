<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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

    protected function renderCategoryPage(string $slug, string $viewName)
    {
        $products = $this->productRepo->getProductsByCategorySlug($slug);
        $brands = $this->brandRepo->getBrandsByCategorySlug($slug);

        return view($viewName, compact('products', 'brands'));
    }

    public function phoneCategory()
    {
        return $this->renderCategoryPage('dien-thoai', 'user.product.phone');
    }

    public function laptopCategory()
    {
        return $this->renderCategoryPage('laptop', 'user.product.laptop');
    }

    public function accessoryCategory()
    {
        return $this->renderCategoryPage('phu-kien', 'user.product.accessory');
    }

    public function mobileAccessory()
    {
        return $this->renderCategoryPage('phu-kien-di-dong', 'user.product.accessory_mobile');
    }

    public function audioAccessory()
    {
        return $this->renderCategoryPage('thiet-bi-am-thanh', 'user.product.accessory_audio');
    }

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
