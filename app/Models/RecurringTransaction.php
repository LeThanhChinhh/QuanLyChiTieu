<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wallet_id',
        'destination_wallet_id',
        'category_id',
        'amount',
        'type',
        'frequency',
        'start_date',
        'next_run_date',
        'description',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'next_run_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function destinationWallet()
    {
        return $this->belongsTo(Wallet::class, 'destination_wallet_id');
    }
}
