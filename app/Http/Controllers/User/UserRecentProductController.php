<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserRecentProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRecentProductController extends Controller
{
    public function store(Request $request, $productId)
    {
        $userId = Auth::id();
        $sessionId = $request->session()->getId();

        // Xoá bản ghi cũ nếu tồn tại
        UserRecentProduct::where('product_id', $productId)
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->when(!$userId, fn($q) => $q->where('session_id', $sessionId))
            ->delete();

        // Lưu bản ghi mới
        UserRecentProduct::create([
            'user_id'    => $userId,
            'session_id' => $userId ? null : $sessionId,
            'product_id' => $productId,
            'viewed_at'  => now(),
        ]);

        return response()->json(['status' => 'ok']);
    }

    // Đồng bộ localStorage sau khi đăng nhập
    // public function sync(Request $request)
    // {
    //     $userId = Auth::id();
    //     $productIds = $request->input('products', []);

    //     foreach ($productIds as $pid) {
    //         UserRecentProduct::updateOrCreate(
    //             ['user_id' => $userId, 'product_id' => $pid],
    //             ['viewed_at' => now()]
    //         );
    //     }

    //     return response()->json(['status' => 'synced']);
    // }
}
