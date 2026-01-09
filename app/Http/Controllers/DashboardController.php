<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // 1. Calcular nutrientes consumidos hoy
        // (Requiere que tengas la función getDailyNutrients en tu Modelo User)
        $dailyNutrients = $user->getDailyNutrients($today);

        // 2. Obtener objetivo nutricional
        $goal = $user->nutritionalGoal;

        // 3. Estadísticas simples
        $stats = [
            'total_dishes' => $user->dishes()->count(),
            'total_products' => $user->products()->count(),
            // Cuenta solo tus platos favoritos
            'favorite_dishes' => $user->dishes()->where('is_favorite', true)->count(),
        ];

        // 4. Menús de hoy para mostrarlos
        $todayMenus = $user->menus()
            ->whereDate('date', $today)
            ->with('dishes')
            ->get();

        return view('dashboard', compact('dailyNutrients', 'goal', 'stats', 'todayMenus'));
    }
}
