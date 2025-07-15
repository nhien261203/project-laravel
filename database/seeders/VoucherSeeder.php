<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Voucher::create([
            'code' => 'WELCOME50',
            'type' => 'fixed', // 'fixed' hoặc 'percent'
            'value' => 50000,
            'min_order_amount' => 0,
            'max_usage' => 1000,
            'max_usage_per_user' => 1,
            'only_for_new_user' => true,
            'start_date' => now(),
            'end_date' => now()->addMonths(6),
            'is_active' => true,
        ]);
    }
}
