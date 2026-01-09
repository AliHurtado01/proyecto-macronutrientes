<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- RELACIONES ---

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function dishes(): HasMany
    {
        return $this->hasMany(Dish::class);
    }

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }

    public function nutritionalGoal(): HasOne
    {
        return $this->hasOne(NutritionalGoal::class);
    }

    // --- LÓGICA DE NEGOCIO (Aquí arreglamos el error del Dashboard) ---

    public function getDailyNutrients($date): array
    {
        // Traemos los menús del día con sus platos e ingredientes
        $menus = $this->menus()
            ->whereDate('date', $date)
            ->with('dishes.products')
            ->get();

        $totals = [
            'calories' => 0, 'protein' => 0, 'fat' => 0,
            'carbohydrates' => 0, 'fiber' => 0
        ];

        foreach ($menus as $menu) {
            $nutrients = $menu->calculateNutrients();

            $totals['calories'] += $nutrients['calories'];
            $totals['protein'] += $nutrients['protein'];
            $totals['fat'] += $nutrients['fat'];
            $totals['carbohydrates'] += $nutrients['carbohydrates'];
            $totals['fiber'] += $nutrients['fiber'];
        }

        return $totals;
    }
}
