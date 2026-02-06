<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('format_nutrient')) {
    /**
     * Formatea un número decimal a un string bonito (ej: 20.5 g)
     */
    function format_nutrient($value, $unit = 'g', $decimals = 1)
    {
        if ($value === null) return '0 ' . $unit;
        return number_format((float)$value, $decimals, ',', '.') . ' ' . $unit;
    }
}

if (!function_exists('nutrient_color')) {
    /**
     * Devuelve una clase de color de Tailwind según el porcentaje completado
     */
    function nutrient_color($percentage)
    {
        if ($percentage < 50) return 'text-red-600';    // Muy poco
        if ($percentage < 80) return 'text-yellow-600'; // Casi
        if ($percentage <= 110) return 'text-green-600'; // Perfecto
        return 'text-orange-600';                        // Pasado
    }
}

if (!function_exists('meal_type_options')) {
    /**
     * Devuelve la lista de tipos de comida traducida
     */
    function meal_type_options()
    {
        return [
            'breakfast' => 'Desayuno',
            'morning_snack' => 'Media Mañana',
            'lunch' => 'Comida',
            'afternoon_snack' => 'Merienda',
            'dinner' => 'Cena',
            'night_snack' => 'Recena',
        ];
    }
}
