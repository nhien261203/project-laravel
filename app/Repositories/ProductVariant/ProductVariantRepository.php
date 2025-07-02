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
        $images = $data['images'] ?? [];
        unset($data['images']);

        $variant = ProductVariant::create($data);

        if (!empty($images)) {
            $this->uploadImages($variant, $images);
        }

        return $variant;
    }

    public function update($id, array $data)
    {
        $variant = $this->find($id);

        // Xử lý ảnh nếu có yêu cầu thay thế
        if (!empty($data['replace_images'])) {
            foreach ($variant->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }
            $variant->images()->delete();
        }

        $images = $data['images'] ?? [];
        unset($data['images'], $data['replace_images']);

        $variant->update($data);

        if (!empty($images)) {
            $this->uploadImages($variant, $images);
        }

        return $variant;
    }

    public function delete($id)
    {
        $variant = $this->find($id);

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
