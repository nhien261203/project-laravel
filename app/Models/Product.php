<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'brand_id',
        'description',
        'status',
    ];

    // Danh mục sản phẩm
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Thương hiệu
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Biến thể sản phẩm
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }
}
