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

    // Calcula el total de este menú concreto (Desayuno, Comida, etc.)
    public function calculateNutrients(): array
    {
        $totals = ['calories' => 0, 'protein' => 0, 'fat' => 0, 'carbohydrates' => 0, 'fiber' => 0];

        foreach ($this->dishes as $dish) {
            $portions = $dish->pivot->portions;

            // Un plato puede tener N raciones. Aquí ajustamos por ración y por cantidad comida.
            // Si el plato entero son 1000kcal y tiene 4 raciones -> 1 ración = 250kcal.
            // Si me como 2 raciones ($portions = 2) -> 500kcal.

            $singleServingRatio = 1 / max($dish->servings, 1);
            $consumedRatio = $singleServingRatio * $portions;

            $totals['calories'] += $dish->total_calories * $consumedRatio;
            $totals['protein'] += $dish->total_protein * $consumedRatio;
            $totals['fat'] += $dish->total_fat * $consumedRatio;
            $totals['carbohydrates'] += $dish->total_carbohydrates * $consumedRatio;
            // Fibra no la guardamos en totales de plato, habría que sumarla de ingredientes,
            // pero para simplificar la dejamos en 0 o la agregamos al Dish si es necesario.
        }
        return $totals;
    }
}
