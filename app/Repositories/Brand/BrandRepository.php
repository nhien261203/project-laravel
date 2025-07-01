<?php

namespace App\Repositories\Brand;

use App\Models\Brand;
use Illuminate\Support\Facades\Storage;

class BrandRepository implements BrandRepositoryInterface
{
    public function all($request)
    {
        $query = Brand::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('country')) {
            $query->where('country', 'like', '%' . $request->country . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query->orderByDesc('id')->paginate(10)->withQueryString();
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
}
