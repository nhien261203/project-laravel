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
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();

            $table->integer('quantity');
            $table->decimal('price', 15, 0); // giá tại thời điểm đặt
            $table->decimal('original_price', 15, 0)->nullable(); // giá gốc
            $table->decimal('cost_price', 15, 0)->nullable(); // giá vốn

            // Snapshot thông tin biến thể
            $table->string('product_name');
            $table->string('product_slug');
            $table->string('color')->nullable();
            $table->string('storage')->nullable();
            $table->string('ram')->nullable();
            $table->string('chip')->nullable();
            $table->string('screen')->nullable();
            $table->string('battery')->nullable();
            $table->string('os')->nullable();
            $table->string('weight')->nullable();
            $table->text('description')->nullable();

            $table->string('image')->nullable();
            $table->integer('sale_percent')->default(0);

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
