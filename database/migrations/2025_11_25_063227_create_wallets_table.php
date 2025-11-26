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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Tên ví: Tiền mặt, VCB
            $table->decimal('balance', 15, 2)->default(0); // Số dư
            $table->string('icon')->nullable()->default('ri-wallet-3-line');
            $table->string('color')->nullable()->default('#10B981');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
