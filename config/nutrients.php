<?php

return [
    'mapping' => [
        // ID BEDCA => [ columna_db, Nombre legible ]
        '409' => ['col' => 'energy_kj', 'name' => 'Energía (kJ)'],
        '416' => ['col' => 'protein', 'name' => 'Proteínas'],
        '410' => ['col' => 'total_fat', 'name' => 'Grasa Total'],
        '53'  => ['col' => 'carbohydrates', 'name' => 'Carbohidratos'],
        '307' => ['col' => 'fiber', 'name' => 'Fibra'],

        // Grasas específicas (Requisitos del enunciado)
        '299' => ['col' => 'saturated_fat', 'name' => 'Grasa Saturada'],
        '282' => ['col' => 'monounsaturated_fat', 'name' => 'Grasa Monoinsaturada'],
        '287' => ['col' => 'polyunsaturated_fat', 'name' => 'Grasa Poliinsaturada'],
        '433' => ['col' => 'cholesterol', 'name' => 'Colesterol'],

        // Extra útil
        '417' => ['col' => 'water', 'name' => 'Agua'],
    ],

    // Factor de conversión (1 kJ = 0.239 Kcal)
    'kj_to_kcal' => 0.239006,
];
