<?php
namespace App\Repositories\Product;

interface ProductRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 10);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);

    // lay 5 sp iphone cho section iphone ( user )
    public function getIphoneProducts(int $limit = 5);

    // lay san pham dien-thoai cho trang dien thoai (user )
    public function getPaginatedProductsByCategorySlug(string $slug, int $perPage = 12);

    // public function queryProductsByCategorySlug(string $slug);
    // public function getOperatingSystems();




}
