<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Brand\BrandRepositoryInterface;

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

    public function phoneCategory()
    {
        // Danh sách sản phẩm trong danh mục "điện thoại", có thể lọc theo brand
        $products = $this->productRepo->getPaginatedProductsByCategorySlug('dien-thoai', 12);

        foreach ($products as $product) {
            $firstVariant = $product->variants->first();
            $storages = $product->variants->pluck('storage')->unique()->filter()->implode(' / ');
            $product->all_storages = $storages;
            $product->sale_percent = $firstVariant?->sale_percent ?? 0;
        }

        // Danh sách các brand có sản phẩm thuộc danh mục "điện thoại"
        $brands = $this->brandRepo->getBrandsByCategorySlug('dien-thoai');

        return view('user.product.phone', compact('products', 'brands'));
    }
}
