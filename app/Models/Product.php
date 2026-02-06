<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder; // Importante para los Scopes
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    // --- SCOPES (Arreglan los errores del Controlador) ---

    // Permite hacer: Product::accessibleBy(auth()->id())->get()
    public function scopeAccessibleBy(Builder $query, int $userId): Builder
    {
        return $query->where(function ($q) use ($userId) {
            $q->whereNull('user_id')            // Productos Globales (BEDCA)
              ->orWhere('user_id', $userId);    // Mis Productos
        });
    }

    public function scopeFavorites(Builder $query): Builder
    {
        return $query->where('is_favorite', true);
    }

    // --- UTILIDADES ---

    public function isGlobal(): bool
    {
        return is_null($this->user_id);
    }

    // Calcula nutrientes según gramos (útil para los platos)
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
