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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('province_code')->nullable();
            $table->string('province_name')->nullable();
            $table->string('district_code')->nullable();
            $table->string('district_name')->nullable();
            $table->string('ward_code')->nullable();
            $table->string('ward_name')->nullable();
            $table->string('address_detail')->nullable(); // số nhà, tên đường
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'province_code',
                'province_name',
                'district_code',
                'district_name',
                'ward_code',
                'ward_name',
                'address_detail',
            ]);
        });
    }
};
