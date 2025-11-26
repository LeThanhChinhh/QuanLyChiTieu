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
    Schema::table('users', function (Blueprint $table) {
        $table->string('google_id')->nullable()->after('email'); // Thêm ID Google
        $table->string('password')->nullable()->change();        // Cho phép pass để trống
        $table->string('avatar')->nullable()->change();          // Cho phép avatar để trống (nếu cần)
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('google_id');
        $table->string('password')->nullable(false)->change();
    });
}
};
