<?php

namespace App\Repositories\Favorite;

interface FavoriteRepositoryInterface
{
    public function getUserFavorites($userId = null, $sessionId = null);
    public function addToFavorite($userId = null, $sessionId = null, $productId, $variantId = null);
    public function removeFromFavorite($userId = null, $sessionId = null, $favoriteId);
    public function mergeFavorite($userId, $sessionId);
}
