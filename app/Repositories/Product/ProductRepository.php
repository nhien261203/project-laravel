<?php

namespace App\Repositories\Product;

use App\Models\Category;
use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 8)
    {
        $query = Product::with(['category', 'brand']);

        if (isset($filters['keyword']) && $filters['keyword'] !== '') {
            $query->where('name', 'like', '%' . $filters['keyword'] . '%');
        }

        if (isset($filters['category_id']) && $filters['category_id'] !== '') {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['brand_id']) && $filters['brand_id'] !== '') {
            $query->where('brand_id', $filters['brand_id']);
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', (int) $filters['status']); // ép kiểu để an toàn
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }


    public function find($id)
    {
        return Product::with(['category', 'brand', 'variants.images'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $product = $this->find($id);
        return $product->update($data);
    }

    public function delete($id)
    {
        $product = $this->find($id);
        return $product->delete();
    }


    // lay ra san pham iphone cho section ben home
    public function getIphoneProducts(int $limit = 5)
    {
        return Product::with(['variants.images'])
            ->where('status', 1) // lấy sản phẩm đang hiển thị
            ->whereHas('category', function ($q) {
                $q->where('name', 'like', '%Điện thoại%');
            })
            ->whereHas('brand', function ($q) {
                $q->where('name', 'like', '%Apple%')
                    ->orWhere('name', 'like', '%iPhone%');
            })
            ->latest('id')
            ->limit($limit)
            ->get();
    }

    // lay all sp dien-thoai cho user
    public function getProductsByCategorySlug(string $slug)
    {
        $category = Category::where('slug', $slug)->where('status', 1)->firstOrFail();

        // Lấy ID của danh mục hiện tại và tất cả danh mục con
        $categoryIds = Category::where('id', $category->id)
            ->orWhere('parent_id', $category->id)
            ->pluck('id');

        return Product::with(['variants.images', 'category'])
            ->whereIn('category_id', $categoryIds)
            ->where('status', 1)
            ->when(request('brand_id'), fn($q) => $q->where('brand_id', request('brand_id')))
            ->latest('id')
            ->get();
        }


    
}
