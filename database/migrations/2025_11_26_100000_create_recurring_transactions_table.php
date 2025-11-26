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
        Schema::create('recurring_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            $table->foreignId('destination_wallet_id')->nullable()->constrained('wallets')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('amount', 15, 2);
            $table->enum('type', ['income', 'expense', 'transfer']);
            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'yearly']);
            $table->date('start_date');
            $table->date('next_run_date');
            $table->string('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_transactions');
    }
};
