<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DishController extends Controller
{
    /**
     * Listado de platos (Corregido)
     */
    public function index(Request $request)
    {
        // USAMOS EL NUEVO SCOPE DEL MODELO DISH
        // Esto automáticamente muestra:
        // - Al Admin: TODO.
        // - Al Usuario: Sus platos + Platos Globales (creados por admin con user_id null).
        $query = Dish::accessibleBy()->with('user');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->boolean('favorites')) {
            $query->where('is_favorite', true);
        }

        // Ordenamos: Primero favoritos, luego por nombre
        $dishes = $query->orderBy('is_favorite', 'desc')
                        ->orderBy('name', 'asc')
                        ->paginate(12);

        return view('dishes.index', compact('dishes'));
    }

    public function create()
    {
        // Para crear plato, necesito ingredientes.
        // Uso el scope de Product para traer globales + míos.
        $products = Product::accessibleBy()->orderBy('name')->get(['id', 'name', 'calories']);
        return view('dishes.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'servings' => 'required|integer|min:1',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
        ]);

        // --- CAMBIO IMPORTANTE: VISIBILIDAD ---
        // Si soy Admin -> user_id = null (Plato Global / Receta pública)
        // Si soy User -> user_id = Auth::id() (Mi plato privado)
        $userId = Auth::user()->hasRole('admin') ? null : Auth::id();

        $dish = Dish::create([
            'user_id' => $userId,
            'name' => $validated['name'],
            'description' => $request->description,
            'servings' => $validated['servings'],
        ]);

        // Guardar ingredientes
        $syncData = [];
        foreach ($validated['products'] as $item) {
            $syncData[$item['id']] = ['quantity' => $item['quantity']];
        }
        $dish->products()->sync($syncData);

        // Calcular totales
        $dish->calculateNutrients();

        return redirect()->route('dishes.index')->with('success', 'Plato creado correctamente.');
    }

    public function show(Dish $dish)
    {
        // Permiso: Soy dueño O es global (user_id null) O soy admin
        $isGlobal = is_null($dish->user_id);
        $isOwner = $dish->user_id == Auth::id();
        $isAdmin = Auth::user()->hasRole('admin');

        if (!$isGlobal && !$isOwner && !$isAdmin) {
            abort(403, 'No tienes permiso para ver este plato.');
        }

        $dish->load('products.category');
        return view('dishes.show', compact('dish'));
    }

    public function edit(Dish $dish)
    {
        // Solo Admin o Dueño pueden editar
        if ($dish->user_id != Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'No puedes editar este plato.');
        }

        $products = Product::accessibleBy()->orderBy('name')->get(['id', 'name']);
        $dish->load('products');

        return view('dishes.edit', compact('dish', 'products'));
    }

    public function update(Request $request, Dish $dish)
    {
        if ($dish->user_id != Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'servings' => 'required|integer|min:1',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
        ]);

        $dish->update([
            'name' => $validated['name'],
            'description' => $request->description,
            'servings' => $validated['servings'],
        ]);

        $syncData = [];
        foreach ($validated['products'] as $item) {
            $syncData[$item['id']] = ['quantity' => $item['quantity']];
        }
        $dish->products()->sync($syncData);
        $dish->calculateNutrients();

        return redirect()->route('dishes.index')->with('success', 'Plato actualizado.');
    }

    public function destroy(Dish $dish)
    {
        // Solo Admin o Dueño pueden borrar
        if ($dish->user_id != Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $dish->delete();
        return redirect()->route('dishes.index')->with('success', 'Plato eliminado.');
    }

    public function toggleFavorite(Dish $dish)
    {
        // Solo puedo marcar favoritos si el plato es MÍO
        // Si el plato es global (del admin), no puedo marcarlo favorito en esta versión simple
        if ($dish->user_id == Auth::id()) {
            $dish->is_favorite = !$dish->is_favorite;
            $dish->save();
            return back();
        }

        return back()->with('error', 'Solo puedes añadir a favoritos tus propios platos.');
    }
}
