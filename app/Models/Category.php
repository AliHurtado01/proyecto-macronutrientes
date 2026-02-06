<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    // Permitir asignación masiva para el Seeder
    protected $fillable = ['bedca_id', 'name'];

    // Relación: Una categoría tiene muchos productos
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
