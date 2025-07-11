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

        // Dùng Repository để tìm kiếm và lấy thêm all_storages, sale_percent
        $products = $this->productRepo->searchProducts($keyword);

        return view('user.search_result', compact('products', 'keyword'));
    }


}
