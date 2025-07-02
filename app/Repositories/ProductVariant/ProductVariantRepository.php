<?php

namespace App\Repositories\ProductVariant;

use App\Models\ProductVariant;
use App\Models\ProductVariantImage;
use Illuminate\Support\Facades\Storage;

class ProductVariantRepository implements ProductVariantRepositoryInterface
{
    public function getByProduct($productId)
    {
        return ProductVariant::with('images')->where('product_id', $productId)->get();
    }

    public function find($id)
    {
        return ProductVariant::with('images')->findOrFail($id);
    }

    public function create($productId, array $data)
    {
        $data['product_id'] = $productId;
        $variant = ProductVariant::create($data);

        // Upload ảnh nếu có
        if (!empty($data['images'])) {
            $this->uploadImages($variant, $data['images']);
        }

        return $variant;
    }

    public function update($id, array $data)
    {
        $variant = $this->find($id);
        $variant->update($data);

        // Upload ảnh mới nếu có
        if (!empty($data['images'])) {
            $this->uploadImages($variant, $data['images']);
        }

        return $variant;
    }

    public function delete($id)
    {
        $variant = $this->find($id);

        // Xoá ảnh vật lý và DB
        foreach ($variant->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $variant->images()->delete();
        return $variant->delete();
    }

    public function deleteImage($imageId)
    {
        $image = ProductVariantImage::findOrFail($imageId);
        Storage::disk('public')->delete($image->image_path);
        return $image->delete();
    }

    /**
     * Upload danh sách ảnh (không gán ảnh chính)
     */
    protected function uploadImages(ProductVariant $variant, array $images)
    {
        foreach ($images as $file) {
            $path = $file->store('variant_images', 'public');

            $variant->images()->create([
                'image_path' => $path,
            ]);
        }
    }
}
