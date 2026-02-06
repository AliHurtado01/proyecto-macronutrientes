<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'name', 'bedca_id',
        'calories', 'energy_kj', 'protein', 'total_fat',
        'saturated_fat', 'monounsaturated_fat', 'polyunsaturated_fat', 'trans_fat',
        'cholesterol', 'carbohydrates', 'fiber', 'water',
        'is_favorite'
    ];

    protected $casts = [
        'is_favorite' => 'boolean',
        'calories' => 'decimal:2',
    ];

    // --- RELACIONES ---

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dishes(): BelongsToMany
    {
        return $this->belongsToMany(Dish::class, 'dish_product')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    // --- SCOPE DE VISIBILIDAD (LA CLAVE DEL PROBLEMA) ---

    public function scopeAccessibleBy($query)
    {
        $user = Auth::user();

        // 1. Si es ADMINISTRADOR, devolvemos TODO.
        // (Esto arregla que no veas los productos de los usuarios)
        if ($user && $user->hasRole('admin')) {
            return $query;
        }

        // 2. Si es USUARIO NORMAL, devolvemos Globales (Null) + Suyos
        return $query->where(function($q) use ($user) {
            $q->whereNull('user_id'); // Globales

            if ($user) {
                $q->orWhere('user_id', $user->id); // Mis productos
            }
        });
    }

    // --- UTILIDADES ---

    public function isGlobal(): bool
    {
        return is_null($this->user_id);
    }

    public function getNutrientsForQuantity(float $grams): array
    {
        $factor = $grams / 100;
        return [
            'calories' => $this->calories * $factor,
            'protein' => $this->protein * $factor,
            'fat' => $this->total_fat * $factor,
            'carbohydrates' => $this->carbohydrates * $factor,
            'fiber' => $this->fiber * $factor,
        ];
    }
}
