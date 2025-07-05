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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            // Liên kết để tra cứu (không dùng để hiển thị)
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_variant_id')->nullable();

            // Snapshot dữ liệu sản phẩm tại thời điểm mua
            $table->string('product_name');
            $table->string('variant_info')->nullable(); // RAM / ROM / Color...

            $table->decimal('price', 15, 2);   // Giá đơn vị
            $table->integer('quantity')->default(1);
            $table->decimal('total', 15, 2);   // Tổng = price * quantity

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
