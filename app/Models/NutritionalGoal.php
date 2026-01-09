<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NutritionalGoal extends Model
{
    protected $fillable = [
        'user_id', 'daily_calories', 'daily_protein', 'daily_fat', 'daily_carbohydrates'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
