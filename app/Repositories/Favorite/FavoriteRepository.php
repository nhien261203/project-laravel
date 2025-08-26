<?php

namespace App\Repositories\Favorite;

use App\Models\Favorite;

class FavoriteRepository implements FavoriteRepositoryInterface
{
    public function getUserFavorites($userId = null, $sessionId = null, $perPage = 8)
    {
        $query = Favorite::with(['product.variants', 'variant']);

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        // Sắp xếp mới nhất trước, rồi phân trang
        return $query->latest()->paginate($perPage);
    }


    public function addToFavorite($userId = null, $sessionId = null, $productId, $variantId = null)
    {
        $data = [
            'user_id' => $userId,
            'session_id' => $userId ? null : $sessionId,
            'product_id' => $productId,
            'product_variant_id' => $variantId
        ];
        return Favorite::firstOrCreate($data);
    }

    public function removeFromFavorite($userId = null, $sessionId = null, $favoriteId)
    {
        $query = Favorite::where('id', $favoriteId);
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }
        $favorite = $query->firstOrFail();
        $favorite->delete();
        return true;
    }

    public function mergeFavorite($userId, $sessionId)
    {
        $guestFavorites = Favorite::where('session_id', $sessionId)->get();

        if ($guestFavorites->isEmpty()) {
            return;
        }

        foreach ($guestFavorites as $fav) {
            $exists = Favorite::where('user_id', $userId)
                ->where('product_id', $fav->product_id)
                ->where('product_variant_id', $fav->product_variant_id)
                ->first();

            if (!$exists) {
                $fav->user_id = $userId;
                $fav->session_id = null;
                $fav->save();
            } else {
                $fav->delete();
            }
        }
    }
}
