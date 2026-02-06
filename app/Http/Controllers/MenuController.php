<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Dish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class MenuController extends Controller
{
    /**
     * Muestra el calendario (vista semanal o lista)
     */
    public function index(Request $request)
    {
        // Por defecto mostramos la semana actual
        $date = $request->has('date')
            ? Carbon::parse($request->date)
            : Carbon::today();

        $startOfWeek = $date->copy()->startOfWeek();
        $endOfWeek = $date->copy()->endOfWeek();

        // Obtener menús de esta semana
        $menus = Menu::where('user_id', Auth::id())
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->with('dishes') // Carga impaciente para optimizar
            ->get()
            ->groupBy(function ($item) {
                return $item->date->format('Y-m-d'); // Agrupar por día
            });

        return view('menus.index', compact('menus', 'startOfWeek', 'endOfWeek', 'date'));
    }

    /**
     * Formulario para crear menú
     */
    public function create(Request $request)
    {
        // Fecha seleccionada o hoy
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        // Mis platos disponibles
        $dishes = Dish::where('user_id', Auth::id())
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('menus.create', compact('dishes', 'date'));
    }

    /**
     * Guardar menú
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'meal_type' => 'required|string', // breakfast, lunch...
            'dishes' => 'required|array|min:1',
            'dishes.*.id' => 'required|exists:dishes,id',
            'dishes.*.portions' => 'required|numeric|min:0.1',
        ]);

        // Verificar duplicados (ej: ya existe Comida para hoy)
        $exists = Menu::where('user_id', Auth::id())
            ->where('date', $validated['date'])
            ->where('meal_type', $validated['meal_type'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['meal_type' => 'Ya existe un menú para este momento del día. Edítalo en su lugar.']);
        }

        // 1. Crear el menú
        $menu = Menu::create([
            'user_id' => Auth::id(),
            'date' => $validated['date'],
            'meal_type' => $validated['meal_type'],
        ]);

        // 2. Asociar platos con porciones
        foreach ($validated['dishes'] as $dish) {
            $menu->dishes()->attach($dish['id'], ['portions' => $dish['portions']]);
        }

        return redirect()->route('menus.index', ['date' => $validated['date']])
            ->with('success', 'Menú planificado correctamente.');
    }

    /**
     * Ver detalles del menú
     */
    public function show(Menu $menu)
    {
        if ($menu->user_id != Auth::id()) abort(403);

        $menu->load('dishes.products');
        // Calculamos nutrientes totales al vuelo usando la función del modelo
        $totals = $menu->calculateNutrients();

        return view('menus.show', compact('menu', 'totals'));
    }

    /**
     * Eliminar menú
     */
public function destroy(Menu $menu)
    {
        if ($menu->user_id != Auth::id()) abort(403);

        $date = Carbon::parse($menu->date)->format('Y-m-d');

        $menu->delete();

        return redirect()->route('menus.index', ['date' => $date])
            ->with('success', 'Comida eliminada del calendario.');
    }

    public function exportPdf(Request $request)
    {
        $date = $request->has('date') ? Carbon::parse($request->date) : Carbon::today();
        $startOfWeek = $date->copy()->startOfWeek();
        $endOfWeek = $date->copy()->endOfWeek();

        // Obtener datos ordenados
        $menus = Menu::where('user_id', Auth::id())
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->with('dishes')
            ->orderBy('date')
            ->get()
            ->groupBy(function($item) {
                return $item->date->format('Y-m-d');
            });

        // Generar PDF usando una vista especial
        $pdf = Pdf::loadView('menus.pdf', compact('menus', 'startOfWeek', 'endOfWeek'));

        return $pdf->download('menu-semanal-' . $startOfWeek->format('d-m-Y') . '.pdf');
    }
}
