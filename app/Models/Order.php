<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'total_amount',
        'total_quantity',
        'payment_status',  // unpaid | paid
        'status',          // pending | processing | completed | cancelled

        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_address',
        'note',
    ];

    // Quan hệ: Order có nhiều OrderItem
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Quan hệ: Order thuộc về 1 User (có thể null)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Tổng tiền lại từ các item (nếu cần)
    public function calculateTotalAmount()
    {
        return $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    // Trạng thái hiển thị đẹp (ví dụ dùng trong blade)
    public function statusLabel()
    {
        return match ($this->status) {
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            default => 'Không xác định',
        };
    }
}
