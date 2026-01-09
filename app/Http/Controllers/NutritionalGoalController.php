<?php

namespace App\Http\Controllers;

use App\Models\NutritionalGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NutritionalGoalController extends Controller
{
    /**
     * Mostrar formulario de objetivos
     */
    public function edit()
    {
        // Si el usuario no tiene objetivo, creamos uno vacío al vuelo
        $goal = Auth::user()->nutritionalGoal ?? NutritionalGoal::create(['user_id' => Auth::id()]);

        return view('goals.edit', compact('goal'));
    }

    /**
     * Guardar cambios
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'daily_calories' => 'nullable|numeric|min:0',
            'daily_protein' => 'nullable|numeric|min:0',
            'daily_fat' => 'nullable|numeric|min:0',
            'daily_carbohydrates' => 'nullable|numeric|min:0',
        ]);

        $goal = Auth::user()->nutritionalGoal;

        // Actualizamos (si vienen vacíos los ponemos null o 0 según prefieras, update ignora nulls si no se pasan)
        $goal->update($validated);

        return redirect()->route('dashboard')->with('success', 'Objetivos actualizados correctamente.');
    }
}
