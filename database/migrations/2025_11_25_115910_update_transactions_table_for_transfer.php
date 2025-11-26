<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Thêm cột ví đích (nullable vì giao dịch thu/chi thường không cần)
            $table->foreignId('destination_wallet_id')
                  ->nullable()
                  ->after('wallet_id')
                  ->constrained('wallets')
                  ->onDelete('cascade');
            
            // Lưu ý: Nếu cột 'type' của bạn đang là ENUM trong DB cũ, bạn cần thay đổi nó.
            // Nếu dùng MySQL/Postgres, cần cài doctrine/dbal. 
            // Với SQLite (mặc định) hoặc setup mới, ta có thể bỏ qua bước đổi type nếu lúc tạo bảng đã để string.
            // Ở đây mình giả định bạn dùng string hoặc sửa lại migration gốc nếu chưa deploy production.
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['destination_wallet_id']);
            $table->dropColumn('destination_wallet_id');
        });
    }
};