<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $fillable = [
        
        'code',
        'type',
        'value',
        // 'max_usage',
        'max_usage_per_user',
        'min_order_amount',
        'max_discount',
        'only_for_new_user',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'only_for_new_user' => 'boolean',
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    // Quan hệ: nhiều user đã dùng voucher này
    public function users()
    {
        return $this->belongsToMany(User::class, 'voucher_user')
            ->withPivot('used_at', 'order_id')
            ->withTimestamps();
    }

    public function voucherUsers()
    {
        return $this->hasMany(VoucherUser::class);
    }

    public function totalUsed()
    {
        return $this->voucherUsers()->count();
    }

    public function isValidForUser($user)
    {
        // Hết hạn
        if (!$this->is_active || now()->lt($this->start_date) || now()->gt($this->end_date)) {
            return false;
        }

        // Kiểm tra số lượt dùng toàn hệ thống
        // if ($this->max_usage !== null && $this->voucherUsers()->count() >= $this->max_usage) {
        //     return false;
        // }

        // Kiểm tra số lượt dùng của user
        $userUsed = $this->voucherUsers()->where('user_id', $user->id)->count();
        if ($this->max_usage_per_user !== null && $userUsed >= $this->max_usage_per_user) {
            return false;
        }

        // Nếu chỉ dành cho user mới
        if ($this->only_for_new_user && $user->orders()->exists()) {
            return false;
        }

        return true;
    }
}
