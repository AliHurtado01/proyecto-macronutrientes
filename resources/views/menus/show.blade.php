<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle de Comida') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- Cabecera --}}
                <div class="flex justify-between items-start mb-6 border-b pb-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">
                            {{ $menu->getMealTypeIcon() }} {{ $menu->getMealTypeLabel() }}
                        </h3>
                        <p class="text-gray-500">{{ $menu->date->format('d \d\e F, Y') }}</p>
                    </div>

                    <form action="{{ route('menus.destroy', $menu) }}" method="POST"
                        onsubmit="return confirm('¿Eliminar esta comida del calendario?');">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="bg-red-100 text-red-700 px-3 py-1 rounded hover:bg-red-200 text-sm font-bold">
                            Eliminar
                        </button>
                    </form>
                </div>

                {{-- Totales de la Comida --}}
                <div class="bg-indigo-50 rounded-lg p-4 mb-6">
                    <h4 class="text-xs uppercase font-bold text-indigo-400 mb-2">Aporte Nutricional Total</h4>
                    <div class="grid grid-cols-4 gap-4 text-center">
                        <div>
                            <span class="block text-xl font-bold text-gray-800">{{ (int) $totals['calories'] }}</span>
                            <span class="text-xs text-gray-500">Kcal</span>
                        </div>
                        <div>
                            <span class="block text-xl font-bold text-blue-600">{{ (int) $totals['protein'] }}g</span>
                            <span class="text-xs text-blue-400">Prot</span>
                        </div>
                        <div>
                            <span class="block text-xl font-bold text-yellow-600">{{ (int) $totals['fat'] }}g</span>
                            <span class="text-xs text-yellow-400">Grasas</span>
                        </div>
                        <div>
                            <span
                                class="block text-xl font-bold text-orange-600">{{ (int) $totals['carbohydrates'] }}g</span>
                            <span class="text-xs text-orange-400">Carbs</span>
                        </div>
                    </div>
                </div>

                {{-- Lista de Platos --}}
                <h4 class="font-bold text-gray-700 mb-3">Platos Consumidos</h4>
                <div class="space-y-3">
                    @foreach ($menu->dishes as $dish)
                        <div class="flex justify-between items-center p-3 border rounded-lg hover:bg-gray-50">
                            <div>
                                <a href="{{ route('dishes.show', $dish) }}"
                                    class="font-bold text-indigo-600 hover:underline">
                                    {{ $dish->name }}
                                </a>
                                <span class="text-gray-500 text-sm ml-2">x {{ (float) $dish->pivot->portions }}
                                    ración</span>
                            </div>
                            <div class="text-right text-sm text-gray-500">
                                {{ round(($dish->total_calories / $dish->servings) * $dish->pivot->portions) }} kcal
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 pt-4 border-t">
                    <a href="{{ route('menus.index', ['date' => $menu->date->format('Y-m-d')]) }}"
                        class="text-gray-600 hover:text-gray-900">
                        &larr; Volver al Calendario
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
