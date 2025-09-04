<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'status',
    ];

    // Danh mục cha
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Danh mục con
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Accessor để lấy URL dựa trên slug
    public function getRouteUrlAttribute(): string
    {
        // Lấy slug của danh mục cha nếu có
        $parentSlug = $this->parent ? $this->parent->slug : null;

        // Kiểm tra nếu danh mục cha là 'phu-kien'
        if ($parentSlug === 'phu-kien') {
            // Sử dụng route của phụ kiện con
            return route('product.category.accessory', ['slug' => $this->slug]);
        }

        // Nếu chính danh mục hiện tại là 'phu-kien'
        if ($this->slug === 'phu-kien') {
            return route('product.accessory');
        }

        // Mặc định cho tất cả các danh mục khác
        return route('product.category', ['slug' => $this->slug]);
    }
}
