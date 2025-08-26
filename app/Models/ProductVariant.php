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
        'sale_percent',
        'quantity',
        'sold',
        'status',
        'operating_system',
        'import_price',
    ];

    // xu li tru so luong khi don hang hoan thanh
    public function deductStock(int $quantity)
    {
        // Kiểm tra tồn kho còn lại
        if (($this->quantity - $this->sold) < $quantity) {
            throw new \Exception("Không đủ hàng trong kho");
        }

        $this->sold += $quantity;
        $this->save();
    }

    // loc con hang
    public function scopeInStock($query)
    {
        return $query->whereRaw('quantity - sold > 0');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'product_variant_id');
    }

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

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Ảnh chính
    public function primaryImage()
    {
        return $this->hasOne(ProductVariantImage::class)->where('is_primary', 1);
    }
}
