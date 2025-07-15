<?php

namespace App\Repositories\Voucher;

use App\Models\Voucher;

class VoucherRepository implements VoucherRepositoryInterface
{
    public function getAll()
    {
        return Voucher::latest()->get();
    }

    public function find($id)
    {
        return Voucher::findOrFail($id);
    }

    public function create(array $data)
    {
        return Voucher::create($data);
    }

    public function update($id, array $data)
    {
        $voucher = $this->find($id);
        $voucher->update($data);
        return $voucher;
    }

    public function delete($id)
    {
        return Voucher::destroy($id);
    }

    public function searchAndFilter(array $filters, int $perPage = 10)
    {
        $query = Voucher::query();

        if (!empty($filters['keyword'])) {
            $query->where('code', 'like', '%' . $filters['keyword'] . '%');
        }

        if (isset($filters['type']) && in_array($filters['type'], ['fixed', 'percent'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['only_for_new_user']) && $filters['only_for_new_user'] !== '') {
            $query->where('only_for_new_user', $filters['only_for_new_user']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }
}
