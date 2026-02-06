<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class Dish extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'description', 'servings',
        'total_calories', 'total_protein', 'total_fat', 'total_carbohydrates',
        'is_favorite'
    ];

    protected $casts = [
        'is_favorite' => 'boolean',
    ];

    // --- RELACIONES ---
    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    public function products(): BelongsToMany {
        return $this->belongsToMany(Product::class, 'dish_product')
                    ->withPivot('quantity')->withTimestamps();
    }

    public function menus(): BelongsToMany {
        return $this->belongsToMany(Menu::class, 'menu_dish')
                    ->withPivot('portions')->withTimestamps();
    }

    // --- SCOPE DE VISIBILIDAD (NUEVO PARA PLATOS) ---
    public function scopeAccessibleBy($query)
    {
        $user = Auth::user();

        // 1. Si es Admin -> Ve TODOS los platos
        if ($user && $user->hasRole('admin')) {
            return $query;
        }

        // 2. Si es Usuario -> Ve Globales (Admin) + Suyos
        return $query->where(function($q) use ($user) {
            $q->whereNull('user_id'); // Platos Globales (Recetas del sistema)

            if ($user) {
                $q->orWhere('user_id', $user->id); // Mis platos
            }
        });
    }

    // --- CÁLCULOS ---
    public function calculateNutrients(): array
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
            'total_carbohydrates' => $totals['carbohydrates'],
        ]);

        return $totals;
    }
}
