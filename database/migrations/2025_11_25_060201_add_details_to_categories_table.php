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
        Schema::table('categories', function (Blueprint $table) {
            // Thêm cột type (thu/chi), mặc định là chi tiêu
            $table->enum('type', ['income', 'expense'])->default('expense')->after('name');

            // Thêm cột icon (lưu tên class icon, ví dụ: 'ri-home-line')
            $table->string('icon')->nullable()->default('ri-price-tag-3-line')->after('type');

            // Thêm cột color (lưu mã hex, ví dụ: #FF0000)
            $table->string('color')->nullable()->default('#10B981')->after('icon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['type', 'icon', 'color']);
        });
    }
};