<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'status',
        'thumbnail'
    ];

    /**
     * Tags liên kết nhiều nhiều
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_tag');
    }

    /**
     * Các comment liên kết 1-nhiều
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
