<?php

namespace App\Repositories\Order;

interface OrderRepositoryInterface
{
    public function getAll();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);

    public function createOrderFromCart(int $userId, array $formData);
}
