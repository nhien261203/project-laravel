<?php

namespace App\Repositories\Voucher;

interface VoucherRepositoryInterface
{
    public function getAll();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function searchAndFilter(array $filters, int $perPage = 10);
}
