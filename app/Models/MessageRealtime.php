<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageRealtime extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'sender',
        'message'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
