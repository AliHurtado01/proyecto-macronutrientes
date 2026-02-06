<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class NutrientExtractor
{
    public static function extract($foodData): array
    {
        $mapping = config('nutrients.mapping');
        $factor = config('nutrients.kj_to_kcal');

        // Valores por defecto
        $data = [
            'bedca_id' => $foodData->food->f_id,
            'name' => $foodData->food->f_ori_name,
            'energy_kj' => 0,
            'calories' => 0,
        ];

        // Inicializar nutrientes en 0
        foreach ($mapping as $map) {
            $data[$map['col']] = 0;
        }

        // Recorrer valores de la API
        if (isset($foodData->food->foodvalue)) {
            $values = is_array($foodData->food->foodvalue) ? $foodData->food->foodvalue : [$foodData->food->foodvalue];

            foreach ($values as $val) {
                // Si el ID existe en nuestro mapa
                if (array_key_exists($val->c_id, $mapping)) {
                    $column = $mapping[$val->c_id]['col'];
                    // Limpieza de datos (si es objeto vacío, poner 0)
                    $cleanValue = (is_object($val->best_location) || is_array($val->best_location))
                        ? 0
                        : floatval($val->best_location);

                    $data[$column] = $cleanValue;
                }
            }
        }

        // Calcular Kcal
        if ($data['energy_kj'] > 0) {
            $data['calories'] = $data['energy_kj'] * $factor;
        }

        return $data;
    }
}
