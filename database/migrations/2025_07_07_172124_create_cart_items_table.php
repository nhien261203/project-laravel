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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_id');
            //$table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_variant_id');
            $table->integer('quantity')->default(1);

            // Snapshot sản phẩm
            $table->string('snapshot_product_name');
            $table->string('snapshot_product_slug')->nullable();
            $table->string('snapshot_color')->nullable();
            $table->string('snapshot_storage')->nullable();
            $table->string('snapshot_ram')->nullable();
            $table->string('snapshot_chip')->nullable();
            $table->string('snapshot_screen')->nullable();
            $table->string('snapshot_battery')->nullable();
            $table->string('snapshot_os')->nullable();
            $table->string('snapshot_weight')->nullable();

            $table->unsignedBigInteger('snapshot_price')->default(0);
            $table->unsignedBigInteger('snapshot_original_price')->nullable();
            $table->unsignedInteger('snapshot_sale_percent')->nullable();

            $table->string('snapshot_image')->nullable(); // lưu ảnh đầu tiên của biến thể

            $table->timestamps();

            // Ràng buộc
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            //$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
