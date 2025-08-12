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
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // đơn có thể không đăng nhập
            $table->string('code')->unique(); // mã đơn hàng

            $table->decimal('total_amount', 15, 0); // tổng tiền (đã áp dụng giảm giá)
            $table->integer('total_quantity')->default(0); // tổng số lượng
            $table->string('payment_status')->default('unpaid'); // unpaid | paid
            $table->string('status')->default('pending'); // pending | shipping | completed | cancelled

            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            // $table->text('customer_address');

            $table->text('note')->nullable();

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
