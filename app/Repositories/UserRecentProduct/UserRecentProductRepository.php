<?php

namespace App\Repositories\UserRecentProduct;

use App\Models\UserRecentProduct;
use Carbon\Carbon;

class UserRecentProductRepository implements UserRecentProductRepositoryInterface
{
    public function mergeRecentViewed($userId, $sessionId)
    {

        // dd([
        //     'userId' => $userId,
        //     'sessionId' => $sessionId,
        //     'sessionProducts' => UserRecentProduct::where('session_id', $sessionId)->get()->toArray(),
        // ]);
        if (!$userId || !$sessionId) return;

        $sessionProducts = UserRecentProduct::where('session_id', $sessionId)->get();


        foreach ($sessionProducts as $item) {
            $existing = UserRecentProduct::where('user_id', $userId)
                ->where('product_id', $item->product_id)
                ->first();

            if ($existing) {
                // Nếu bản ghi từ session mới hơn thì cập nhật
                if (Carbon::parse($item->viewed_at)->gt(Carbon::parse($existing->viewed_at))) {
                    $existing->update(['viewed_at' => $item->viewed_at]);
                }
            } else {
                // Tạo mới từ bản ghi session
                UserRecentProduct::create([
                    'user_id'    => $userId,
                    'session_id' => null, // tránh bị trùng 
                    'product_id' => $item->product_id,
                    'viewed_at'  => $item->viewed_at,
                ]);
            }
        }

        // Xoá các bản ghi của session
        UserRecentProduct::where('session_id', $sessionId)->delete();

        //dd(UserRecentProduct::where('user_id', $userId)->get()->toArray()); // Xem kết quả cuối

        // Giữ lại 10 sản phẩm gần nhất
        $recent = UserRecentProduct::where('user_id', $userId)
            ->orderByDesc('viewed_at')
            ->orderByDesc('id') // đảm bảo ổn định
            ->get();

        if ($recent->count() > 10) {
            $idsToDelete = $recent->slice(10)->pluck('id');
            UserRecentProduct::whereIn('id', $idsToDelete)->delete();
        }
    }
}
