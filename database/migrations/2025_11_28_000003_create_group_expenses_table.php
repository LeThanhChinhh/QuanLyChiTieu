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
        Schema::create('group_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('paid_by_user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('amount', 15, 2);
            $table->string('description')->nullable();
            $table->date('expense_date');
            $table->enum('split_method', ['equal', 'percentage', 'custom', 'shares'])->default('equal');
            $table->boolean('is_settled')->default(false);
            $table->timestamps();
            
            $table->index(['group_id', 'expense_date']);
            $table->index('is_settled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_expenses');
    }
};
