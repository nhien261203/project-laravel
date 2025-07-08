<?php

namespace App\Repositories\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;

class CartRepository implements CartRepositoryInterface
{
    public function getUserCart($userId = null, $sessionId = null)
    {
        if ($userId) {
            return Cart::with('items')->firstOrCreate(['user_id' => $userId]);
        }

        return Cart::with('items')->firstOrCreate(['session_id' => $sessionId]);
    }


    public function addToCart($userId = null, $sessionId = null, $variantId, $quantity)
    {
        $cart = $this->getUserCart($userId, $sessionId);

        $item = $cart->items()->where('product_variant_id', $variantId)->first();

        if ($item) {
            $item->increment('quantity', $quantity);
            return $item;
        }

        // Lấy thông tin snapshot
        $variant = ProductVariant::with(['product', 'images'])->findOrFail($variantId);

        //dd($variant->toArray());


        return $cart->items()->create([
            'product_variant_id' => $variantId,
            'quantity' => $quantity,
            'snapshot_product_name' => $variant->product->name,
            'snapshot_product_slug' => $variant->product->slug,
            'snapshot_color' => $variant->color,
            'snapshot_storage' => $variant->storage,
            'snapshot_ram' => $variant->ram,
            'snapshot_chip' => $variant->chip,
            'snapshot_screen' => $variant->screen,
            'snapshot_battery' => $variant->battery,
            'snapshot_os' => $variant->operating_system,
            'snapshot_weight' => $variant->weight,
            'snapshot_cost_price' => $variant->import_price,
            'snapshot_price' => $variant->price,
            'snapshot_original_price' => $variant->original_price,
            'snapshot_sale_percent' => $variant->sale_percent,


            'snapshot_image' => $variant->images->first()->image_path ?? null,
        ]);
    }

    public function removeFromCart($userId = null, $sessionId = null, $variantId)
    {
        $cart = $this->getUserCart($userId, $sessionId);
        return $cart->items()->where('product_variant_id', $variantId)->delete();
    }

    public function updateQuantity($userId = null, $sessionId = null, $variantId, $quantity)
    {
        $cart = $this->getUserCart($userId, $sessionId);
        return $cart->items()->where('product_variant_id', $variantId)->update([
            'quantity' => $quantity
        ]);
    }

    // ham nay gop cart tu chua login thanh login
    public function mergeCart($userId, $sessionId)
    {
        $guestCart = $this->getUserCart(null, $sessionId);
        $userCart = $this->getUserCart($userId, null);

        // Nếu giỏ hàng guest không có sản phẩm thì không merge
        if ($guestCart->items->isEmpty() || $guestCart->id === $userCart->id) {
            return;
        }

        foreach ($guestCart->items as $item) {
            // Nếu đã có variant đó trong giỏ hàng của user
            $existing = $userCart->items()->where('product_variant_id', $item->product_variant_id)->first();

            if ($existing) {
                $existing->increment('quantity', $item->quantity);
            } else {
                $newItem = $item->replicate();
                $newItem->cart_id = $userCart->id;
                $newItem->save();
            }
        }

        $guestCart->items()->delete(); // Xóa giỏ hàng session sau khi merge xong
    }
}
