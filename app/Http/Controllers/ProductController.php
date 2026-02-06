<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Listado de productos (Buscador y Filtros)
     */
    public function index(Request $request)
    {
        // 1. Consulta Base: Usamos el Scope 'accessibleBy' del Modelo.
        // Como ya arreglamos el modelo, si soy Admin, esto traerá TODOS los productos.
        $query = Product::accessibleBy();

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
                          ->orderBy('is_favorite', 'desc') // Favoritos primero
                          ->orderBy('name', 'asc')         // Luego alfabéticamente
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
     * Guardar producto
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

        // --- VISIBILIDAD ---
        // Si es Admin -> user_id = null (Producto Global para todos)
        // Si es User -> user_id = mi id (Producto Privado)
        if (Auth::user()->hasRole('admin')) {
            $product->user_id = null;
        } else {
            $product->user_id = Auth::id();
        }

        // Asignar opcionales
        $product->fiber = $request->input('fiber', 0);
        $product->saturated_fat = $request->input('saturated_fat', 0);
        $product->monounsaturated_fat = $request->input('monounsaturated_fat', 0);
        $product->polyunsaturated_fat = $request->input('polyunsaturated_fat', 0);
        $product->cholesterol = $request->input('cholesterol', 0);
        $product->trans_fat = 0;
        $product->water = 0;

        $product->save();

        return redirect()->route('products.index')->with('success', 'Ingrediente creado correctamente.');
    }

    /**
     * Ver detalle
     */
    public function show(Product $product)
    {
        // Seguridad: Permitir ver si es global, mío O si soy ADMIN
        $isGlobal = is_null($product->user_id);
        $isMine = $product->user_id == Auth::id();
        $isAdmin = Auth::user()->hasRole('admin');

        if (!$isGlobal && !$isMine && !$isAdmin) {
            abort(403, 'No tienes permiso para ver este producto.');
        }

        return view('products.show', compact('product'));
    }

    /**
     * Editar producto
     */
    public function edit(Product $product)
    {
        // Solo edito lo mío. El Admin tampoco debería editar cosas de usuarios para no romper sus dietas,
        // pero sí puede editar sus propios productos globales.
        if ($product->user_id != Auth::id() && !$product->isGlobal()) {
             // Si quieres que el Admin edite CUALQUIER cosa, cambia la condición.
             // Aquí dejamos que solo edite lo suyo o globales si es admin.
             if (!Auth::user()->hasRole('admin') || ($product->user_id && $product->user_id != Auth::id())) {
                 abort(403, 'No puedes editar productos que no creaste.');
             }
        }

        $categories = Category::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Actualizar producto
     */
    public function update(Request $request, Product $product)
    {
        // Misma lógica de seguridad que en edit
        if (!Auth::user()->hasRole('admin') && $product->user_id != Auth::id()) {
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

        $product->fiber = $request->input('fiber', 0);
        $product->saturated_fat = $request->input('saturated_fat', 0);
        $product->monounsaturated_fat = $request->input('monounsaturated_fat', 0);
        $product->polyunsaturated_fat = $request->input('polyunsaturated_fat', 0);
        $product->cholesterol = $request->input('cholesterol', 0);

        $product->save();

        return redirect()->route('products.index')->with('success', 'Ingrediente actualizado.');
    }

    /**
     * Eliminar producto
     */
    public function destroy(Product $product)
    {
        // Permitir borrar si soy Admin O es mi producto
        if (Auth::user()->hasRole('admin') || $product->user_id == Auth::id()) {
            $product->delete();
            return redirect()->route('products.index')->with('success', 'Ingrediente eliminado.');
        }

        abort(403, 'No puedes borrar este producto.');
    }

    /**
     * Marcar/Desmarcar Favorito
     */
    public function toggleFavorite(Product $product)
    {
        // Solo permitimos marcar favoritos los productos PROPIOS
        if ($product->user_id == Auth::id()) {
            $product->is_favorite = !$product->is_favorite;
            $product->save();
            return back();
        }

        return back()->with('error', 'Solo puedes marcar favoritos tus productos personalizados.');
    }
}
