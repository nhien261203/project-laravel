<?php
namespace App\Repositories\Product;

interface ProductRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 10);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);

    public function getRamsByCategoryIds($categoryIds);
    public function getStoragesByCategoryIds($categoryIds);

    public function getAllIphoneStorages();
    public function getAllIphoneRams();

    // lay 5 sp iphone cho section iphone ( user )
    public function getIphoneProducts(int $limit = 5);

    public function getAllIphoneProducts();

    public function getLaptopProducts(int $limit = 5);

    public function getAccessoryProducts(int $limit = 5);

    // lay san pham dien-thoai cho trang dien thoai (user )
    public function getProductsByCategorySlug(string $slug);

    public function searchProducts(string $keyword, int $perPage = 8);

    // public function queryProductsByCategorySlug(string $slug);
    // public function getOperatingSystems();

}
