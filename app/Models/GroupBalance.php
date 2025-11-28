<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $group_id
 * @property int $user_id
 * @property float $balance
 * @property \Illuminate\Support\Carbon $last_calculated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class GroupBalance extends Model
{
    protected $fillable = [
        'group_id',
        'user_id',
        'balance',
        'last_calculated_at',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'last_calculated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isOwed(): bool
    {
        return $this->balance > 0;
    }

    public function owes(): bool
    {
        return $this->balance < 0;
    }

    public function isSettled(): bool
    {
        return abs($this->balance) < 0.01;
    }
}
