<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property float $balance
 * @property string|null $icon
 * @property string|null $color
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaction[] $transactions
 * @property-read \App\Models\User $user
 */
class Wallet extends Model
{
        // app/Models/Wallet.php
    protected $fillable = ['user_id', 'name', 'balance', 'icon', 'color'];

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
