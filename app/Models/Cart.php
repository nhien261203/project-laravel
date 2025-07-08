<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'session_id',
    ];

    // Quan hệ: Giỏ hàng thuộc về 1 người dùng (có thể null)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ: Cart có nhiều CartItem
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // Tổng số lượng item trong giỏ
    public function totalQuantity()
    {
        return $this->items()->sum('quantity');
    }

    // Tổng tiền
    public function totalPrice()
    {
        return $this->items->sum(function ($item) {
            return $item->snapshot_price * $item->quantity;
        });
    }
}
