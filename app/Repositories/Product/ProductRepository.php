<?php

namespace App\Repositories\Product;

use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 10)
    {
        $query = Product::with(['category', 'brand']);

        if (!empty($filters['keyword'])) {
            $query->where('name', 'like', '%' . $filters['keyword'] . '%');
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        return $query->latest()->paginate($perPage);
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
}
