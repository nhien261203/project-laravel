<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique(); // Mã giảm giá, ví dụ: GIAM50K
            $table->enum('type', ['fixed', 'percent']); // Loại giảm giá: cố định (50k), phần trăm (10%)
            $table->unsignedInteger('value'); // Giá trị giảm: 50000 hoặc 10 (%)

            $table->unsignedInteger('max_usage')->nullable(); // Giới hạn toàn hệ thống (NULL = không giới hạn)
            $table->unsignedInteger('max_usage_per_user')->nullable(); // Giới hạn mỗi user

            $table->unsignedInteger('min_order_amount')->nullable(); // Đơn hàng tối thiểu để được dùng
            $table->unsignedInteger('max_discount')->nullable(); // Với phần trăm: giới hạn mức giảm tối đa

            $table->boolean('only_for_new_user')->default(false); // Mã chỉ dùng cho user mới

            $table->dateTime('start_date')->nullable(); // Ngày bắt đầu
            $table->dateTime('end_date')->nullable(); // Ngày hết hạn

            $table->boolean('is_active')->default(true); // Đã kích hoạt chưa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
