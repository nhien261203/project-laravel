<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'cart_id',
        // 'product_id',
        'product_variant_id',
        'quantity',

        // snapshot
        'snapshot_product_name',
        'snapshot_product_slug',
        'snapshot_color',
        'snapshot_storage',
        'snapshot_ram',
        'snapshot_chip',
        'snapshot_screen',
        'snapshot_battery',
        'snapshot_os',
        'snapshot_weight',
        'snapshot_price',
        'snapshot_original_price',
        'snapshot_cost_price',
        'snapshot_sale_percent',
        'snapshot_image',
    ];

    // Quan hệ: thuộc giỏ hàng
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // Quan hệ: sản phẩm gốc
    // public function product()
    // {
    //     return $this->belongsTo(Product::class);
    // }

    // Quan hệ: biến thể sản phẩm
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
