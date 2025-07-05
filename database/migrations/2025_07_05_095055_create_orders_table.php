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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Bắt buộc có user
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Thông tin người nhận hàng
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->string('shipping_address');

            // Tổng tiền đơn hàng (có số lẻ)
            $table->decimal('total_amount', 15, 2);

            // Trạng thái đơn hàng
            $table->enum('status', ['pending', 'shipping', 'completed', 'cancelled'])->default('pending');

            // Trạng thái thanh toán
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');

            // Phương thức thanh toán: cod, bank_transfer, vnpay...
            $table->string('payment_method')->nullable();

            // Mã đơn hàng (hiển thị cho khách)
            $table->string('order_code')->nullable()->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
