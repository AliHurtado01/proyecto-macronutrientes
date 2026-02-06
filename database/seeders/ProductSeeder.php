<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Helpers\NutrientExtractor;
use StaticKidz\BedcaAPI\BedcaClient;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $client = new BedcaClient();
        $categories = Category::all();

        foreach ($categories as $category) {
            $this->command->info("Procesando categoría: " . $category->name);

            // 1. Obtener lista de alimentos del grupo
            $list = $client->getFoodsInGroup($category->bedca_id);

            // Validación básica de respuesta API
            if (!isset($list->food)) continue;
            $foods = is_array($list->food) ? $list->food : [$list->food];

            // 2. Importar solo los primeros 3 para no saturar (puedes subir este número si quieres más)
            foreach (array_slice($foods, 0, 3) as $food) {

                // Evitar duplicados si ejecutas el seeder dos veces
                if (Product::where('bedca_id', $food->f_id)->exists()) {
                    continue;
                }

                $this->command->info("   Importando: " . $food->f_ori_name);

                // 3. Obtener detalles completos del alimento
                $details = $client->getFood($food->f_id);

                // 4. Usar el Helper para limpiar datos
                $data = NutrientExtractor::extract($details);

                // 5. Crear el producto con TODOS los requisitos del enunciado
                Product::create([
                    'user_id' => null, // null = Disponible para todos (Global)
                    'category_id' => $category->id,
                    'bedca_id' => $data['bedca_id'],
                    'name' => $data['name'],

                    // --- REQUISITOS OBLIGATORIOS ---
                    'calories' => $data['calories'],          // Calorías
                    'energy_kj' => $data['energy_kj'],        // Energía original
                    'protein' => $data['protein'],            // Proteínas
                    'total_fat' => $data['total_fat'],        // Grasa total
                    'saturated_fat' => $data['saturated_fat'], // Saturada
                    'monounsaturated_fat' => $data['monounsaturated_fat'], // Monoinsaturada
                    'polyunsaturated_fat' => $data['polyunsaturated_fat'], // Poliinsaturada
                    'trans_fat' => 0,                         // Trans (No provisto por API, default 0)
                    'cholesterol' => $data['cholesterol'],    // Colesterol
                    'carbohydrates' => $data['carbohydrates'],// Carbohidratos
                    'fiber' => $data['fiber'],                // Fibra

                    // Extra
                    'water' => $data['water'],
                ]);
            }
        }
    }
}
