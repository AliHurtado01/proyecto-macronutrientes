<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Dish;
use App\Models\Menu;
use App\Models\User;

class AdminController extends Controller
{
    public function stats()
    {
        // Estadísticas simples
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_dishes' => Dish::count(),
            'total_menus' => Menu::count(),
        ];

        return view('admin.stats', compact('stats'));
    }
}
