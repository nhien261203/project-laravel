<?php

namespace App\Repositories\Brand;

use App\Models\Brand;
use Illuminate\Support\Facades\Storage;

class BrandRepository implements BrandRepositoryInterface
{
    public function all($request)
    {
        $query = Brand::query();

        if (isset($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if (isset($request->country)) {
            $query->where('country', 'like', '%' . $request->country . '%');
        }

        if (isset($request->status) && in_array($request->status, ['0', '1'])) {
            $query->where('status', $request->status);
        }

        return $query->orderByDesc('id')->paginate(6)->withQueryString();
    }


    public function find($id)
    {
        return Brand::findOrFail($id);
    }

    public function create(array $data)
    {
        if (isset($data['logo'])) {
            $data['logo'] = $data['logo']->store('brand', 'public');
        }
        return Brand::create($data);
    }

    public function update($id, array $data)
    {
        $brand = $this->find($id);

        if (isset($data['logo'])) {
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $data['logo'] = $data['logo']->store('brand', 'public');
        } else {
            unset($data['logo']);
        }

        $brand->update($data);
        return $brand;
    }

    public function delete($id)
    {
        $brand = $this->find($id);
        if ($brand->logo) {
            Storage::disk('public')->delete($brand->logo);
        }
        return $brand->delete();
    }


    // LAY BRAND LOC O TRANG PRODUCT USER
    public function getBrandsByCategorySlug(string $slug)
    {
        $category = \App\Models\Category::where('slug', $slug)->where('status', 1)->firstOrFail();

        // Lấy tất cả ID của danh mục hiện tại và danh mục con
        $categoryIds = \App\Models\Category::where('id', $category->id)
            ->orWhere('parent_id', $category->id)
            ->pluck('id');

        // Lọc các brand có sản phẩm thuộc các danh mục này
        return Brand::whereHas('products', function ($q) use ($categoryIds) {
                $q->whereIn('category_id', $categoryIds)->where('status', 1);
            })
            ->withCount(['products' => function ($q) use ($categoryIds) {
                $q->whereIn('category_id', $categoryIds)->where('status', 1);
            }])
            ->distinct()
            ->get();
    }


}
