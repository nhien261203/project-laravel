<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'sku',
        'ram',
        'storage',
        'color',
        'screen_size',
        'weight',
        'battery',
        'chip',
        'price',
        'original_price',
        'quantity',
        'sold',
        'status',
        'operating_system',
        'import_price',
    ];

    // Sản phẩm cha
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Danh sách ảnh của biến thể
    public function images()
    {
        return $this->hasMany(ProductVariantImage::class);
    }

    // Ảnh chính
    public function primaryImage()
    {
        return $this->hasOne(ProductVariantImage::class)->where('is_primary', 1);
    }
}
