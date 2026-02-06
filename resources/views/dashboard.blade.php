<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Resumen Nutricional') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 1. SECCIÓN DE OBJETIVOS Y PROGRESO --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">📊 Progreso Diario - {{ now()->format('d/m/Y') }}</h3>
                    <a href="{{ route('goals.edit') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-semibold">
                        ⚙️ Configurar Objetivos
                    </a>
                </div>

                @if($goal)
                    {{-- Si hay objetivos configurados, mostramos las barras --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                        {{-- Calorías --}}
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-500">Calorías</span>
                                <span class="text-xs font-bold text-gray-400">Objetivo: {{ (int)$goal->daily_calories }}</span>
                            </div>
                            <div class="text-2xl font-bold text-gray-800 mb-2">
                                {{ (int)$dailyNutrients['calories'] }} <span class="text-sm font-normal text-gray-500">kcal</span>
                            </div>
                            @php $calPercent = ($goal->daily_calories > 0) ? ($dailyNutrients['calories'] / $goal->daily_calories) * 100 : 0; @endphp
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="h-2.5 rounded-full {{ nutrient_color($calPercent) }}" style="width: {{ min($calPercent, 100) }}%"></div>
                            </div>
                            <p class="text-right text-xs mt-1 {{ nutrient_color($calPercent) }} font-bold">{{ round($calPercent) }}%</p>
                        </div>

                        {{-- Proteínas --}}
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-blue-600">Proteínas</span>
                                <span class="text-xs font-bold text-blue-400">/ {{ (int)$goal->daily_protein }}g</span>
                            </div>
                            <div class="text-2xl font-bold text-blue-800 mb-2">
                                {{ (float)$dailyNutrients['protein'] }} <span class="text-sm font-normal text-blue-600">g</span>
                            </div>
                            @php $proPercent = ($goal->daily_protein > 0) ? ($dailyNutrients['protein'] / $goal->daily_protein) * 100 : 0; @endphp
                            <div class="w-full bg-blue-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min($proPercent, 100) }}%"></div>
                            </div>
                        </div>

                        {{-- Grasas --}}
                        <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-100">
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-yellow-600">Grasas</span>
                                <span class="text-xs font-bold text-yellow-400">/ {{ (int)$goal->daily_fat }}g</span>
                            </div>
                            <div class="text-2xl font-bold text-yellow-800 mb-2">
                                {{ (float)$dailyNutrients['fat'] }} <span class="text-sm font-normal text-yellow-600">g</span>
                            </div>
                            @php $fatPercent = ($goal->daily_fat > 0) ? ($dailyNutrients['fat'] / $goal->daily_fat) * 100 : 0; @endphp
                            <div class="w-full bg-yellow-200 rounded-full h-2.5">
                                <div class="bg-yellow-500 h-2.5 rounded-full" style="width: {{ min($fatPercent, 100) }}%"></div>
                            </div>
                        </div>

                        {{-- Carbohidratos --}}
                        <div class="bg-orange-50 p-4 rounded-xl border border-orange-100">
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-orange-600">Carbohidratos</span>
                                <span class="text-xs font-bold text-orange-400">/ {{ (int)$goal->daily_carbohydrates }}g</span>
                            </div>
                            <div class="text-2xl font-bold text-orange-800 mb-2">
                                {{ (float)$dailyNutrients['carbohydrates'] }} <span class="text-sm font-normal text-orange-600">g</span>
                            </div>
                            @php $carbPercent = ($goal->daily_carbohydrates > 0) ? ($dailyNutrients['carbohydrates'] / $goal->daily_carbohydrates) * 100 : 0; @endphp
                            <div class="w-full bg-orange-200 rounded-full h-2.5">
                                <div class="bg-orange-500 h-2.5 rounded-full" style="width: {{ min($carbPercent, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Si NO hay objetivos --}}
                    <div class="text-center py-8 bg-yellow-50 rounded-lg border border-yellow-200">
                        <p class="text-yellow-800 mb-2">⚠️ No has definido tus objetivos diarios.</p>
                        <a href="{{ route('goals.edit') }}" class="text-indigo-600 hover:underline font-bold">
                            Configurar mis metas ahora &rarr;
                        </a>
                    </div>
                @endif
            </div>

            {{-- 2. SECCIÓN DE MENÚS DE HOY --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Columna Izquierda: Menús --}}
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">🍽️ Menú de Hoy</h3>
                        <a href="{{ route('menus.index') }}" class="text-sm text-blue-600 hover:underline">Ver Calendario Completo</a>
                    </div>

                    @if($todayMenus->count() > 0)
                        <div class="space-y-4">
                            @foreach($todayMenus as $menu)
                                <div class="border border-gray-100 rounded-lg p-4 hover:shadow-md transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">
                                                {{ $menu->meal_type }}
                                            </span>
                                            <div class="mt-1">
                                                @foreach($menu->dishes as $dish)
                                                    <span class="inline-block bg-gray-100 text-gray-700 text-sm px-2 py-1 rounded mr-2 mb-1">
                                                        {{ $dish->name }} (x{{ (float)$dish->pivot->portions }})
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                        {{-- Calcular total rápido del menú --}}
                                        @php $mTotals = $menu->calculateNutrients(); @endphp
                                        <div class="text-right">
                                            <span class="block font-bold text-gray-800">{{ (int)$mTotals['calories'] }} kcal</span>
                                            <span class="text-xs text-blue-500">{{ (int)$mTotals['protein'] }}g prot</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400">
                            <p>No tienes comidas registradas para hoy.</p>
                            <a href="{{ route('menus.create') }}" class="mt-2 inline-block bg-blue-600 text-white text-sm py-2 px-4 rounded hover:bg-blue-700">
                                + Registrar Comida
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Columna Derecha: Estadísticas Rápidas --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">🏆 Mis Estadísticas</h3>
                    <ul class="space-y-4">
                        <li class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                            <span class="text-purple-800">Platos Creados</span>
                            <span class="font-bold text-2xl text-purple-600">{{ $stats['total_dishes'] }}</span>
                        </li>
                        <li class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                            <span class="text-green-800">Mis Productos</span>
                            <span class="font-bold text-2xl text-green-600">{{ $stats['total_products'] }}</span>
                        </li>
                        <li class="flex justify-between items-center p-3 bg-pink-50 rounded-lg">
                            <span class="text-pink-800">Platos Favoritos</span>
                            <span class="font-bold text-2xl text-pink-600">{{ $stats['favorite_dishes'] }}</span>
                        </li>
                    </ul>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h4 class="text-sm font-bold text-gray-500 mb-3 uppercase">Accesos Rápidos</h4>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('products.create') }}" class="text-center p-2 border rounded hover:bg-gray-50 text-sm">Nuevo Ingrediente</a>
                            <a href="{{ route('dishes.create') }}" class="text-center p-2 border rounded hover:bg-gray-50 text-sm">Crear Plato</a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
