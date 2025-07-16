<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Voucher\VoucherRepositoryInterface;

class VoucherController extends Controller
{
    protected $voucherRepo;

    public function __construct(VoucherRepositoryInterface $voucherRepo)
    {
        $this->voucherRepo = $voucherRepo;
    }

    public function index(Request $request)
    {
        $filters = $request->only([
            'keyword',
            'type',
            'is_active',
            'only_for_new_user'
        ]);

        $vouchers = $this->voucherRepo->searchAndFilter($filters);

        return view('admin.vouchers.index', compact('vouchers', 'filters'));
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:vouchers,code',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|integer|min:1',

            // 'max_usage' => 'nullable|integer|min:1',
            'max_usage_per_user' => 'nullable|integer|min:1',
            'min_order_amount' => 'nullable|integer|min:0',
            'max_discount' => 'nullable|integer|min:0',

            'only_for_new_user' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',

            'is_active' => 'boolean',
        ]);

        $this->voucherRepo->create($data);
        return redirect()->route('admin.vouchers.index')->with('success', 'Tạo mã giảm giá thành công');
    }

    public function edit($id)
    {
        $voucher = $this->voucherRepo->find($id);
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:vouchers,code,' . $id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|integer|min:1',

            // 'max_usage' => 'nullable|integer|min:1',
            'max_usage_per_user' => 'nullable|integer|min:1',
            'min_order_amount' => 'nullable|integer|min:0',
            'max_discount' => 'nullable|integer|min:0',

            'only_for_new_user' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',

            'is_active' => 'boolean',
        ]);

        $this->voucherRepo->update($id, $data);
        return redirect()->route('admin.vouchers.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        $this->voucherRepo->delete($id);
        return redirect()->route('admin.vouchers.index')->with('success', 'Xóa thành công');
    }
}
