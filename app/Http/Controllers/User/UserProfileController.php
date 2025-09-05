<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        return view('user.profile', compact('user'));
    }
    public function edit()
    {
        $user = Auth::user();
        return view('user.profile_edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if($request->phone) {
        $exists = User::where('phone', $request->phone)
                    ->where('id', '!=', $user->id)
                    ->exists();
        if($exists) {
            return back()->with('error', 'Số điện thoại này đã được sử dụng.');
        }
    }
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user->update($data);

        return redirect()->route('user.profile')->with('success', 'Cập nhật thông tin thành công.');
    }
}
