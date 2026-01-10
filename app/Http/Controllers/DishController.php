<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DishController extends Controller
{
    /**
     * Listado de mis platos
     */
    public function index(Request $request)
    {
        $query = Dish::where('user_id', Auth::id());

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->boolean('favorites')) {
            $query->where('is_favorite', true);
        }

        $dishes = $query->orderBy('is_favorite', 'desc')
                        ->orderBy('name', 'asc')
                        ->paginate(12);

        return view('dishes.index', compact('dishes'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        // Necesitamos la lista de productos para el selector
        $products = Product::accessibleBy(Auth::id())
                           ->orderBy('name')
                           ->get(['id', 'name', 'calories']); // Solo traemos lo necesario para optimizar

        return view('dishes.create', compact('products'));
    }

    /**
     * Guardar plato y sus ingredientes
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'servings' => 'required|integer|min:1',
            'products' => 'required|array|min:1', // Al menos 1 ingrediente
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1', // Gramos
        ]);

        // 1. Crear el plato
        $dish = Dish::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $request->description,
            'servings' => $validated['servings'],
        ]);

        // 2. Asociar ingredientes (Tabla Pivote)
        // Preparamos el array para sync: [id => ['quantity' => 100], id2 => ...]
        $syncData = [];
        foreach ($validated['products'] as $item) {
            // Si el ingrediente se repite, sumamos cantidad (opcional, aquí simplificamos sobrescribiendo o asumiendo único)
            $syncData[$item['id']] = ['quantity' => $item['quantity']];
        }

        $dish->products()->sync($syncData);

        // 3. Calcular totales y guardar en caché
        $dish->calculateNutrients();

        return redirect()->route('dishes.index')->with('success', 'Plato creado correctamente.');
    }

    /**
     * Ver detalle
     */
    public function show(Dish $dish)
    {
        if ($dish->user_id != Auth::id()) abort(403);

        $dish->load('products.category'); // Carga ansiosa para rendimiento
        return view('dishes.show', compact('dish'));
    }

    /**
     * Editar plato
     */
    public function edit(Dish $dish)
    {
        if ($dish->user_id != Auth::id()) abort(403);

        $products = Product::accessibleBy(Auth::id())->orderBy('name')->get(['id', 'name']);

        // Cargamos los productos actuales del plato para rellenar el formulario
        $dish->load('products');

        return view('dishes.edit', compact('dish', 'products'));
    }

    /**
     * Actualizar plato
     */
    public function update(Request $request, Dish $dish)
    {
        if ($dish->user_id != Auth::id()) abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'servings' => 'required|integer|min:1',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
        ]);

        // 1. Actualizar datos básicos
        $dish->update([
            'name' => $validated['name'],
            'description' => $request->description,
            'servings' => $validated['servings'],
        ]);

        // 2. Sincronizar ingredientes (Borra los viejos y pone los nuevos)
        $syncData = [];
        foreach ($validated['products'] as $item) {
            $syncData[$item['id']] = ['quantity' => $item['quantity']];
        }
        $dish->products()->sync($syncData);

        // 3. Recalcular
        $dish->calculateNutrients();

        return redirect()->route('dishes.index')->with('success', 'Plato actualizado.');
    }

    /**
     * Eliminar
     */
    public function destroy(Dish $dish)
    {
        if ($dish->user_id != Auth::id()) abort(403);
        $dish->delete(); // La cascada en BD borrará las relaciones en dish_product
        return redirect()->route('dishes.index')->with('success', 'Plato eliminado.');
    }

    /**
     * Favorito
     */
    public function toggleFavorite(Dish $dish)
    {
        if ($dish->user_id != Auth::id()) abort(403);

        $dish->is_favorite = !$dish->is_favorite;
        $dish->save();

        return back();
    }
}
