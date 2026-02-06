<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Dish extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'servings',
        'total_calories',
        'total_protein',
        'total_fat',
        'total_carbohydrates',
        'is_favorite'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'dish_product')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    // Recalcula y guarda los totales en la BD
    public function calculateNutrients()
    {
        $totals = ['calories' => 0, 'protein' => 0, 'fat' => 0, 'carbohydrates' => 0];

        foreach ($this->products as $product) {
            $grams = $product->pivot->quantity;
            $nutrients = $product->getNutrientsForQuantity($grams);

            $totals['calories'] += $nutrients['calories'];
            $totals['protein'] += $nutrients['protein'];
            $totals['fat'] += $nutrients['fat'];
            $totals['carbohydrates'] += $nutrients['carbohydrates'];
        }

        $this->update([
            'total_calories' => $totals['calories'],
            'total_protein' => $totals['protein'],
            'total_fat' => $totals['fat'],
            'total_carbohydrates' => $totals['carbohydrates']
        ]);
    }
}
