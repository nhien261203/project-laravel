<?php

namespace App\Repositories\Category;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function all($request)
    {
        $query = Category::query();

        if (isset($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if (isset($request->status) && in_array($request->status, ['0', '1'], true)) {
            $query->where('status', $request->status);
        }

        return $query->orderByDesc('id')->paginate(10)->withQueryString();
    }


    public function find($id)
    {
        return Category::findOrFail($id);
    }

    public function create(array $data)
    {
        $data['name'] = trim($data['name']);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['parent_id'] = $data['parent_id'] ?? null;
        if (isset($data['logo']) && $data['logo']->isValid()) {
            $path = $data['logo']->store('categories', 'public');
            $data['logo'] = $path;
        }

        return Category::create($data);
    }

    public function update($id, array $data)
    {
        $category = $this->find($id);

        $data['name'] = trim($data['name']);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        // Không được chọn chính mình làm danh mục cha
        if (isset($data['parent_id']) && $data['parent_id'] == $id) {
            unset($data['parent_id']);
        } else {
            $data['parent_id'] = $data['parent_id'] ?? null;
        }

        if (isset($data['logo']) && $data['logo']->isValid()) {
            // Xoá logo cũ nếu có
            if ($category->logo && Storage::disk('public')->exists($category->logo)) {
                Storage::disk('public')->delete($category->logo);
            }

            $path = $data['logo']->store('categories', 'public');
            $data['logo'] = $path;
        } else {
            unset($data['logo']); // không update nếu không gửi logo
        }

        $category->update($data);
        return $category;
    }

    public function delete($id)
    {
        $category = $this->find($id);

        
        if ($category->logo && Storage::disk('public')->exists($category->logo)) {
            Storage::disk('public')->delete($category->logo);
        }
        return Category::destroy($id);
    }

    public function getParentOptions($excludeId = null)
    {
        $query = Category::query();
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->get();
    }

    // lấy category ra cho headers bên User/ HomeController
    // public function getWithChildren()
    // {
    //     return Category::with(['children' => function ($q) {
    //         $q->where('status', 1);
    //     }])
    //         ->whereNull('parent_id')
    //         ->where('status', 1)
    //         ->get();
    // }


}
