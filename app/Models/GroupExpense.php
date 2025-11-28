<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $group_id
 * @property int $paid_by_user_id
 * @property int|null $category_id
 * @property float $amount
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $expense_date
 * @property string $split_method
 * @property bool $is_settled
 * @property bool $is_settlement
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class GroupExpense extends Model
{
    protected $fillable = [
        'group_id',
        'paid_by_user_id',
        'category_id',
        'amount',
        'description',
        'expense_date',
        'split_method',
        'is_settled',
        'is_settlement',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
        'is_settled' => 'boolean',
        'is_settlement' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by_user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function splits(): HasMany
    {
        return $this->hasMany(GroupExpenseSplit::class, 'expense_id');
    }

    public function unpaidSplits(): HasMany
    {
        return $this->hasMany(GroupExpenseSplit::class, 'expense_id')->where('is_paid', false);
    }
}
