<?php

namespace App\Repositories\Cart;

interface CartRepositoryInterface
{
    public function getUserCart($userId = null, $sessionId = null);
    public function addToCart($userId = null, $sessionId = null, $variantId, $quantity);
    public function removeFromCart($userId = null, $sessionId = null, $variantId);
    public function updateQuantity($userId = null, $sessionId = null, $variantId, $quantity);
}
