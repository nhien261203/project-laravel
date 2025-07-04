<?php

namespace App\Repositories\Product;

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
}
