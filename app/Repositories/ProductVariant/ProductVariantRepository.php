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

        // Xác định ảnh chính nếu được chọn
        $newPrimaryImageIndex = null;
        if (!empty($data['primary_image_id']) && str_starts_with($data['primary_image_id'], 'new_')) {
            $newPrimaryImageIndex = (int) str_replace('new_', '', $data['primary_image_id']);
        }

        // Upload ảnh
        if (!empty($data['images'])) {
            $this->uploadImages($variant, $data['images'], $newPrimaryImageIndex);
        }

        return $variant;
    }

    public function update($id, array $data)
    {
        $variant = $this->find($id);
        $variant->update($data);

        // Reset ảnh cũ về không phải ảnh chính
        foreach ($variant->images as $image) {
            $image->update(['is_primary' => 0]);
        }

        // Gắn cờ nếu ảnh chính là ảnh mới
        $newPrimaryImageIndex = null;
        if (!empty($data['primary_image_id']) && str_starts_with($data['primary_image_id'], 'new_')) {
            $newPrimaryImageIndex = (int) str_replace('new_', '', $data['primary_image_id']);
        }

        // Upload ảnh mới
        if (!empty($data['images'])) {
            $this->uploadImages($variant, $data['images'], $newPrimaryImageIndex);
        }

        // Nếu ảnh chính là ảnh cũ
        if (!empty($data['primary_image_id']) && !str_starts_with($data['primary_image_id'], 'new_')) {
            foreach ($variant->images as $image) {
                $image->update(['is_primary' => $image->id == $data['primary_image_id'] ? 1 : 0]);
            }
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

    /**
     * Upload danh sách ảnh và đánh dấu ảnh chính nếu có
     */
    protected function uploadImages(ProductVariant $variant, array $images, $primaryIndex = null)
    {
        foreach ($images as $index => $file) {
            $path = $file->store('variant_images', 'public');
            $isPrimary = $index === $primaryIndex ? 1 : 0;

            $variant->images()->create([
                'image_path' => $path,
                'is_primary' => $isPrimary
            ]);
        }
    }
}
