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

        if (!empty($data['images'])) {
            $this->uploadImages($variant, $data['images']);
        }

        return $variant;
    }

    public function update($id, array $data)
    {
        $variant = $this->find($id);
        $variant->update($data);

        // Cập nhật ảnh chính
        if (!empty($data['primary_image_id'])) {
            foreach ($variant->images as $image) {
                $image->update(['is_primary' => $image->id == $data['primary_image_id'] ? 1 : 0]);
            }
        }

        // Thêm ảnh mới
        if (!empty($data['images'])) {
            $this->uploadImages($variant, $data['images']);
        }

        return $variant;
    }

    public function delete($id)
    {
        $variant = $this->find($id);

        // Xoá ảnh khỏi storage
        foreach ($variant->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Xoá bản ghi
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
        foreach ($images as $index => $file) {
            $path = $file->store('variant_images', 'public');

            $variant->images()->create([
                'image_path' => $path,
                'is_primary' => 0 // Đặt mặc định không là ảnh chính
            ]);
        }
    }
}
