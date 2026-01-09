<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NutritionalGoalController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

// Grupo de rutas protegidas (solo usuarios logueados)
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard (Resumen diario)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Productos (Ingredientes)
    Route::resource('products', ProductController::class);
    // Acción extra para favoritos
    Route::post('/products/{product}/favorite', [ProductController::class, 'toggleFavorite'])->name('products.favorite');

    // Platos (Recetas)
    Route::resource('dishes', DishController::class);
    Route::post('/dishes/{dish}/favorite', [DishController::class, 'toggleFavorite'])->name('dishes.favorite');

    // Menús (Calendario)
    Route::resource('menus', MenuController::class);
    Route::get('/menus/export/pdf', [MenuController::class, 'exportPdf'])->name('menus.export_pdf');

    // Objetivos Nutricionales (Navidad)
    Route::get('/goals', [NutritionalGoalController::class, 'edit'])->name('goals.edit');
    Route::put('/goals', [NutritionalGoalController::class, 'update'])->name('goals.update');

    // Perfil de usuario (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- RUTA DE DEBUG TEMPORAL ---
Route::get('/debug-views', function() {
    $path = resource_path('views/products');

    echo "<h1>Diagnóstico de Vistas</h1>";
    echo "<b>Laravel está buscando la carpeta aquí:</b> <br>" . $path . "<br><br>";

    if (is_dir($path)) {
        echo "✅ <b style='color:green'>La carpeta 'products' EXISTE.</b><br>";
        echo "Contenido de la carpeta:<br><ul>";
        $files = scandir($path);
        foreach($files as $file) {
            if($file != '.' && $file != '..') {
                echo "<li>" . $file . "</li>";
            }
        }
        echo "</ul>";
    } else {
        echo "❌ <b style='color:red'>La carpeta 'products' NO EXISTE en esa ruta.</b><br>";
        echo "Por favor, revisa si creaste la carpeta dentro de 'resources/views' o si la pusiste en otro lugar por error.";
    }

    echo "<br><hr><br>";
    echo "<b>Prueba de acceso directo al archivo:</b><br>";
    $fileIndex = $path . '/index.blade.php';
    if (file_exists($fileIndex)) {
        echo "✅ <b style='color:green'>El archivo 'index.blade.php' EXISTE.</b>";
    } else {
        echo "❌ <b style='color:red'>El archivo 'index.blade.php' NO EXISTE.</b>";
    }
});

require __DIR__.'/auth.php';
