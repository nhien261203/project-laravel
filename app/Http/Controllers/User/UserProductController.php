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
        $products = $this->productRepo->getProductsByCategorySlug('dien-thoai');

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

    public function laptopCategory()
    {
        // Danh sách sản phẩm trong danh mục "laptop", có thể lọc theo brand
        $products = $this->productRepo->getProductsByCategorySlug('laptop');

        foreach ($products as $product) {
            $firstVariant = $product->variants->first();
            $storages = $product->variants->pluck('storage')->unique()->filter()->implode(' / ');
            $product->all_storages = $storages;
            $product->sale_percent = $firstVariant?->sale_percent ?? 0;
        }

        // Danh sách các brand có sản phẩm thuộc danh mục "laptop"
        $brands = $this->brandRepo->getBrandsByCategorySlug('laptop');

        return view('user.product.laptop', compact('products', 'brands'));
    }

    public function accessoryCategory()
    {
        // Lấy sản phẩm trong danh mục "phu-kien" và các danh mục con
        $products = $this->productRepo->getProductsByCategorySlug('phu-kien');

        foreach ($products as $product) {
            $firstVariant = $product->variants->first();
            $storages = $product->variants->pluck('storage')->filter()->unique()->implode(' / ');
            $product->all_storages = $storages;
            $product->sale_percent = $firstVariant?->sale_percent ?? 0;
        }

        $brands = $this->brandRepo->getBrandsByCategorySlug('phu-kien');

        return view('user.product.accessory', compact('products', 'brands'));
    }


    public function mobileAccessory()
    {
        $products = $this->productRepo->getProductsByCategorySlug('phu-kien-di-dong');

        foreach ($products as $product) {
            $firstVariant = $product->variants->first();
            $product->sale_percent = $firstVariant?->sale_percent ?? 0;
        }

        $brands = $this->brandRepo->getBrandsByCategorySlug('phu-kien-di-dong');

        return view('user.product.accessory_mobile', compact('products', 'brands'));
    }

    public function audioAccessory()
    {
        $products = $this->productRepo->getProductsByCategorySlug('thiet-bi-am-thanh');

        foreach ($products as $product) {
            $firstVariant = $product->variants->first();
            $product->sale_percent = $firstVariant?->sale_percent ?? 0;
        }

        $brands = $this->brandRepo->getBrandsByCategorySlug('thiet-bi-am-thanh');

        return view('user.product.accessory_audio', compact('products', 'brands'));
    }



}
