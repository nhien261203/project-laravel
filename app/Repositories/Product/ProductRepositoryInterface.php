<?php
namespace App\Repositories\Product;

interface ProductRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 10);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
