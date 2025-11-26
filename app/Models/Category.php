<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $type
 * @property string|null $icon
 * @property string|null $color
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id', 
    'name', 
    'type',  
    'icon',  
    'color'  
];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
    
    public function budgets() {
        return $this->hasMany(Budget::class);
    }
}