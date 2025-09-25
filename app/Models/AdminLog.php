<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'admin_id', 'action', 'description', 'ip_address', 'user_agent','causer_id',
        'causer_type',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    public function causer()
    {
        return $this->morphTo();
    }
}
