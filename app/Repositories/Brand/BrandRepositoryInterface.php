<?php
namespace App\Repositories\Brand;

interface BrandRepositoryInterface
{
    public function all($request);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);

    public function getBrandsByCategorySlug(string $slug);
}
