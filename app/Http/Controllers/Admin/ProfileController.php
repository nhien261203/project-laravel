<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;


class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $roles = $user->getRoleNames(); // Trả về Collection tên các role

        return view('admin.profile.show', compact('user', 'roles'));
    }


    public function update(Request $request)
    {
        $user = Auth::user();

        // Nếu không phải admin và gửi lên role → từ chối
        if ($request->has('role') && !$user->hasRole('admin')) {
            abort(403, 'Bạn không có quyền thay đổi quyền người dùng.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'avatar' => 'nullable|image|max:2048',
            'role' => 'nullable|string|exists:roles,name' // chỉ validate nếu có gửi lên
        ]);

        // Xử lý avatar
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        // Nếu là admin và gửi lên role → cập nhật role
        if (isset($data['role']) && $user->hasRole('admin')) {
            $user->syncRoles([$data['role']]);
        }

        return redirect()->route('admin.profile.show')->with('success', 'Cập nhật hồ sơ thành công.');
    }

    public function edit()
    {
        $user = auth()->user();
        $roles = Role::all(); 
        return view('admin.profile.edit', compact('user', 'roles'));
    }

    public function Userprofile()
    {
        $user = auth()->user();
        $orders = $user->orders()->latest()->get(); 

        return view('user.profile', compact('orders'));
    }
}
