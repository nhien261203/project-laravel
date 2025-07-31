<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query->paginate(10)->appends($request->query());

        $roles = \Spatie\Permission\Models\Role::all(); // lấy danh sách quyền

        return view('admin.users.index', compact('users', 'roles'));
    }


    public function edit($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Bạn không có quyền truy cập.');
        }
        $user = User::findOrFail($id);
        $roles = Role::pluck('name', 'name');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email',
            'role' => 'required|array',
            'role.*' => 'exists:roles,name',

        ]);

        $user = User::findOrFail($id);
        $user->update($request->only('name', 'email'));

        $user->syncRoles($request->role);

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }
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
