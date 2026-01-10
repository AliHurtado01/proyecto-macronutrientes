<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dish;
use App\Models\Product;
use App\Models\User;

class DishSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Obtener el usuario de prueba para asignarle los platos
        $user = User::where('email', 'test@example.com')->first();

        if (!$user) {
            $this->command->error('❌ No se encontró el usuario test@example.com. Crea primero el usuario.');
            return;
        }

        // 2. Lista de "Recetas Maestras" para intentar crear
        // El script buscará productos que contengan esas palabras clave
        $recipes = [
            [
                'name' => 'Tortilla de Patatas',
                'description' => 'Receta clásica española con huevo y patata.',
                'servings' => 4,
                'ingredients' => [
                    ['search' => 'huevo', 'qty' => 400],  // ~6 huevos
                    ['search' => 'patata', 'qty' => 600], // 3 patatas grandes
                    ['search' => 'aceite', 'qty' => 50],  // Un chorro generoso
                ]
            ],
            [
                'name' => 'Ensalada Mixta',
                'description' => 'Ensalada fresca ligera para cenar.',
                'servings' => 2,
                'ingredients' => [
                    ['search' => 'lechuga', 'qty' => 200],
                    ['search' => 'tomate', 'qty' => 150],
                    ['search' => 'cebolla', 'qty' => 50],
                    ['search' => 'atún', 'qty' => 100],
                    ['search' => 'aceite', 'qty' => 15],
                ]
            ],
            [
                'name' => 'Arroz a la Cubana',
                'description' => 'Arroz blanco con tomate frito y huevo.',
                'servings' => 1,
                'ingredients' => [
                    ['search' => 'arroz', 'qty' => 100],
                    ['search' => 'tomate', 'qty' => 100],
                    ['search' => 'huevo', 'qty' => 60],
                ]
            ],
            [
                'name' => 'Pollo Asado con Patatas',
                'description' => 'Muslos de pollo al horno.',
                'servings' => 2,
                'ingredients' => [
                    ['search' => 'pollo', 'qty' => 400],
                    ['search' => 'patata', 'qty' => 300],
                    ['search' => 'aceite', 'qty' => 20],
                ]
            ],
            [
                'name' => 'Macarrones con Tomate',
                'description' => 'Pasta con salsa de tomate y carne.',
                'servings' => 4,
                'ingredients' => [
                    ['search' => 'pasta', 'qty' => 400], // O 'macarrón'
                    ['search' => 'tomate', 'qty' => 300],
                    ['search' => 'carne', 'qty' => 200],
                    ['search' => 'queso', 'qty' => 50],
                ]
            ],
            [
                'name' => 'Desayuno Saludable',
                'description' => 'Avena con leche y fruta.',
                'servings' => 1,
                'ingredients' => [
                    ['search' => 'avena', 'qty' => 50],
                    ['search' => 'leche', 'qty' => 250],
                    ['search' => 'plátano', 'qty' => 100], // O 'manzana'
                ]
            ],
            [
                'name' => 'Sandwich Mixto',
                'description' => 'Cena rápida.',
                'servings' => 1,
                'ingredients' => [
                    ['search' => 'pan', 'qty' => 60],
                    ['search' => 'jamón', 'qty' => 40],
                    ['search' => 'queso', 'qty' => 30],
                ]
            ],
            [
                'name' => 'Lentejas Estofadas',
                'description' => 'Plato de cuchara tradicional.',
                'servings' => 4,
                'ingredients' => [
                    ['search' => 'lenteja', 'qty' => 300],
                    ['search' => 'zanahoria', 'qty' => 100],
                    ['search' => 'patata', 'qty' => 200],
                    ['search' => 'chorizo', 'qty' => 100], // O carne de cerdo
                ]
            ],
            [
                'name' => 'Merluza a la plancha',
                'description' => 'Pescado blanco con guarnición.',
                'servings' => 1,
                'ingredients' => [
                    ['search' => 'merluza', 'qty' => 200], // O 'pescado'
                    ['search' => 'lechuga', 'qty' => 100],
                    ['search' => 'aceite', 'qty' => 10],
                ]
            ],
            [
                'name' => 'Batido de Proteínas',
                'description' => 'Post-entreno.',
                'servings' => 1,
                'ingredients' => [
                    ['search' => 'leche', 'qty' => 300],
                    ['search' => 'proteína', 'qty' => 30], // Puede que no encuentre esto en BEDCA, buscará 'leche' en polvo o similar
                    ['search' => 'cacao', 'qty' => 10],
                ]
            ],
        ];

        $createdCount = 0;

        // 3. Crear los platos "reales"
        foreach ($recipes as $recipe) {
            $this->command->info("🍳 Cocinando: {$recipe['name']}...");

            $dish = Dish::create([
                'user_id' => $user->id,
                'name' => $recipe['name'],
                'description' => $recipe['description'],
                'servings' => $recipe['servings'],
            ]);

            $hasIngredients = false;

            foreach ($recipe['ingredients'] as $ing) {
                // Buscamos un producto que coincida (ej: "Huevo" -> "Huevo de gallina")
                $product = Product::where('name', 'like', '%' . $ing['search'] . '%')->first();

                // Si no encuentra "pasta", intenta buscar algo genérico de la DB para no dejarlo vacío
                if (!$product) {
                    $product = Product::inRandomOrder()->first();
                }

                if ($product) {
                    $dish->products()->attach($product->id, ['quantity' => $ing['qty']]);
                    $hasIngredients = true;
                }
            }

            if ($hasIngredients) {
                $dish->calculateNutrients();
                $createdCount++;
            } else {
                $dish->delete(); // Borrar si no se pudo añadir nada (raro)
            }
        }

        // 4. Relleno: Si faltan platos para llegar a 10, crea aleatorios
        while ($createdCount < 10) {
            $createdCount++;
            $name = "Plato Sorpresa #$createdCount";
            $this->command->info("🎲 Generando aleatorio: $name");

            $dish = Dish::create([
                'user_id' => $user->id,
                'name' => $name,
                'description' => 'Plato generado aleatoriamente para pruebas.',
                'servings' => rand(1, 4),
            ]);

            // Añadir 3 a 6 ingredientes al azar
            $randomProducts = Product::inRandomOrder()->limit(rand(3, 6))->get();

            foreach ($randomProducts as $p) {
                $dish->products()->attach($p->id, ['quantity' => rand(50, 300)]);
            }

            $dish->calculateNutrients();
        }

        $this->command->info("✅ ¡Listo! Se han servido $createdCount platos.");
    }
}
