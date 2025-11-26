<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $category_id
 * @property int $wallet_id
 * @property int|null $destination_wallet_id
 * @property float $amount
 * @property string $type
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $transaction_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Wallet $wallet
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\User $user
 */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'wallet_id', 'amount', 'type', 'destination_wallet_id', // Thêm wallet_id
        'description', 'transaction_date'
    ];

    // Cast để transaction_date tự động là kiểu Date (Carbon)
    protected $casts = [
        'transaction_date' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function wallet() {
    return $this->belongsTo(Wallet::class);
    }

    public function destinationWallet() {
    return $this->belongsTo(Wallet::class, 'destination_wallet_id');
    }
}