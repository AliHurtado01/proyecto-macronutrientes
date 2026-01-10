<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Listado de productos
     */
    public function index(Request $request)
    {
        // 1. Consulta base: Productos de BEDCA (user_id null) O Míos
        $query = Product::where(function ($q) {
            $q->whereNull('user_id')
                ->orWhere('user_id', Auth::id());
        });

        // 2. Filtros
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->boolean('favorites')) {
            $query->where('is_favorite', true);
        }

        // 3. Ordenar y Paginar
        $products = $query->with('category')
            ->orderBy('is_favorite', 'desc')
            ->orderBy('name', 'asc')
            ->paginate(12);

        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    /**
     * Guardar nuevo producto
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'calories' => 'required|numeric|min:0',
            'protein' => 'required|numeric|min:0',
            'total_fat' => 'required|numeric|min:0',
            'carbohydrates' => 'required|numeric|min:0',
        ]);

        $product = new Product($validated);
        $product->user_id = Auth::id(); // Es mío

        // Rellenar opcionales con 0
        $product->fiber = $request->input('fiber', 0);
        $product->saturated_fat = $request->input('saturated_fat', 0);
        $product->monounsaturated_fat = $request->input('monounsaturated_fat', 0);
        $product->polyunsaturated_fat = $request->input('polyunsaturated_fat', 0);
        $product->cholesterol = $request->input('cholesterol', 0);
        $product->trans_fat = 0;
        $product->water = 0;

        $product->save();

        return redirect()->route('products.index')->with('success', 'Ingrediente creado.');
    }

    /**
     * Ver detalle
     */
    public function show(Product $product)
    {
        // Seguridad: Si es privado y no es mío, error 403
        if ($product->user_id && $product->user_id != Auth::id()) {
            abort(403);
        }
        return view('products.show', compact('product'));
    }

    /**
     * Editar
     */
    public function edit(Product $product)
    {
        // Solo edito lo mío
        if ($product->isGlobal() || $product->user_id != Auth::id()) {
            abort(403, 'No puedes editar productos globales.');
        }
        $categories = Category::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Actualizar
     */
    public function update(Request $request, Product $product)
    {
        if ($product->isGlobal() || $product->user_id != Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'calories' => 'required|numeric|min:0',
            'protein' => 'required|numeric|min:0',
            'total_fat' => 'required|numeric|min:0',
            'carbohydrates' => 'required|numeric|min:0',
        ]);

        $product->fill($validated);
        // Actualizar opcionales
        $product->fiber = $request->input('fiber', 0);
        $product->saturated_fat = $request->input('saturated_fat', 0);
        $product->monounsaturated_fat = $request->input('monounsaturated_fat', 0);
        $product->polyunsaturated_fat = $request->input('polyunsaturated_fat', 0);
        $product->cholesterol = $request->input('cholesterol', 0);

        $product->save();

        return redirect()->route('products.index')->with('success', 'Actualizado correctamente.');
    }

    /**
     * Eliminar
     */
    public function destroy(Product $product)
    {
        if ($product->isGlobal() || $product->user_id != Auth::id()) {
            abort(403);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Eliminado correctamente.');
    }

    /**
     * Alternar Favorito (Simplificado)
     */
    public function toggleFavorite(Product $product)
    {
        // Solo permitimos marcar favoritos los productos PROPIOS
        // porque usamos una columna simple en la base de datos.
        if ($product->user_id == Auth::id()) {
            $product->is_favorite = !$product->is_favorite;
            $product->save();
            return back();
        }

        return back()->with('error', 'Solo puedes marcar favoritos tus productos personalizados.');
    }
}
