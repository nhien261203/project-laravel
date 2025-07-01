<?php

namespace App\Repositories\ProductVariant;

interface ProductVariantRepositoryInterface
{
    public function getByProduct($productId);
    public function find($id);
    public function create($productId, array $data);
    public function update($id, array $data);
    public function delete($id);
    public function deleteImage($imageId);
}
