<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveBlogCategoryIdFromBlogsTable extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            // Nếu cột đang có ràng buộc foreign key thì phải drop trước
            $table->dropForeign(['blog_category_id']);
            $table->dropColumn('blog_category_id');
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->foreignId('blog_category_id')->nullable()->constrained('blog_categories')->nullOnDelete();
        });
    }
}
