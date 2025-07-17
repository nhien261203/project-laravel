<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::pluck('name', 'name');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email',
            'role'  => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->only('name', 'email'));

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->hasRole('admin')) {
            return back()->with('error', 'Không thể xoá tài khoản quản trị viên chính.');
        }

        $user->delete();
        return back()->with('success', 'Xoá thành công');
    }

    public function show($id)
    {
        $user = User::with(['roles', 'orders', 'vouchers'])->findOrFail($id);

        $totalOrders = $user->orders->count();
        $totalAmount = $user->orders->sum('total_amount');

        // Lấy thông tin các đơn hàng liên quan đến voucher
        $orderMap = Order::whereIn('id', $user->vouchers->pluck('pivot.order_id')->filter()->unique())
            ->get()
            ->keyBy('id'); // để truy cập nhanh bằng ID

        return view('admin.users.show', compact('user', 'totalOrders', 'totalAmount', 'orderMap'));
    }
}
