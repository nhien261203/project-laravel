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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('sku')->unique();

            $table->string('ram')->nullable();
            $table->string('storage')->nullable();
            $table->string('color')->nullable();
            $table->string('screen_size')->nullable();
            $table->string('weight')->nullable();
            $table->string('battery')->nullable();
            $table->string('chip')->nullable();

            $table->decimal('price', 10, 2);
            $table->decimal('original_price', 10, 2)->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('sold')->default(0);
            $table->boolean('status')->default(1); // 1: hiện, 0: ẩn

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
