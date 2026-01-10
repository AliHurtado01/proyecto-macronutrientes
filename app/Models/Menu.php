<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Menu extends Model
{
    protected $fillable = ['user_id', 'date', 'meal_type'];

    protected $casts = [
        'date' => 'date'
    ];

    // --- RELACIONES ---

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dishes(): BelongsToMany
    {
        return $this->belongsToMany(Dish::class, 'menu_dish')
                    ->withPivot('portions')
                    ->withTimestamps();
    }

    // --- LÓGICA DE CÁLCULO ---

    public function calculateNutrients(): array
    {
        $totals = ['calories' => 0, 'protein' => 0, 'fat' => 0, 'carbohydrates' => 0, 'fiber' => 0];

        foreach ($this->dishes as $dish) {
            $portions = $dish->pivot->portions;

            // Ajuste por raciones
            $singleServingRatio = 1 / max($dish->servings, 1);
            $consumedRatio = $singleServingRatio * $portions;

            $totals['calories'] += $dish->total_calories * $consumedRatio;
            $totals['protein'] += $dish->total_protein * $consumedRatio;
            $totals['fat'] += $dish->total_fat * $consumedRatio;
            $totals['carbohydrates'] += $dish->total_carbohydrates * $consumedRatio;
        }
        return $totals;
    }

    // --- FUNCIONES VISUALES (Las que faltaban) ---

    public function getMealTypeLabel(): string
    {
        return match($this->meal_type) {
            'breakfast' => 'Desayuno',
            'morning_snack' => 'Media Mañana',
            'lunch' => 'Comida',
            'afternoon_snack' => 'Merienda',
            'dinner' => 'Cena',
            'night_snack' => 'Recena',
            default => ucfirst($this->meal_type),
        };
    }

    public function getMealTypeIcon(): string
    {
        return match($this->meal_type) {
            'breakfast' => '🍳',
            'morning_snack' => '🍎',
            'lunch' => '🍽️',
            'afternoon_snack' => '☕',
            'dinner' => '🌙',
            'night_snack' => '🥛',
            default => '🍴',
        };
    }
}
