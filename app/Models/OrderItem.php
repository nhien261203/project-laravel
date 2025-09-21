<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_variant_id',
        'quantity',
        'price',
        'original_price',
        'cost_price',

        // Snapshot (không có prefix snapshot_ vì DB đang dùng tên ngắn)
        'product_name',
        'product_slug',
        'color',
        'storage',
        'ram',
        'chip',
        'screen',
        'battery',
        'os',
        'weight',
        'description',
        'image',
        'sale_percent',
    ];

    // Quan hệ: Thuộc về 1 đơn hàng
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Quan hệ: trỏ về biến thể gốc
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
