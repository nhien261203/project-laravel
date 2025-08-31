<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageRealtime extends Model
{
    use HasFactory;
    protected $table = 'message_realtime';
    protected $fillable = [
        'user_id',
        'conversation_id',
        'sender',
        'message',
        'client_id',
        'read_at',
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
