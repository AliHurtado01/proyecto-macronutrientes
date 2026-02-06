<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $dish->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- Cabecera --}}
                <div class="flex justify-between items-start mb-6 border-b pb-4">
                    <div>
                        <span class="bg-indigo-100 text-indigo-800 text-sm font-medium px-2.5 py-0.5 rounded">
                            {{ $dish->servings }} Raciones
                        </span>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('dishes.edit', $dish) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">Editar</a>
                        <form action="{{ route('dishes.destroy', $dish) }}" method="POST" onsubmit="return confirm('¿Borrar plato?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">Borrar</button>
                        </form>
                        <a href="{{ route('dishes.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Volver</a>
                    </div>
                </div>

                {{-- Tabla Nutricional --}}
                <h3 class="font-bold text-gray-700 mb-4">Valores Nutricionales (Por Plato Completo)</h3>
                <div class="grid grid-cols-4 gap-4 text-center mb-8">
                    <div class="p-4 bg-gray-50 rounded">
                        <div class="text-2xl font-bold">{{ (int)$dish->total_calories }}</div>
                        <div class="text-xs uppercase text-gray-500">Calorías</div>
                    </div>
                    <div class="p-4 bg-blue-50 rounded">
                        <div class="text-2xl font-bold text-blue-600">{{ (int)$dish->total_protein }}g</div>
                        <div class="text-xs uppercase text-blue-400">Proteínas</div>
                    </div>
                    <div class="p-4 bg-yellow-50 rounded">
                        <div class="text-2xl font-bold text-yellow-600">{{ (int)$dish->total_fat }}g</div>
                        <div class="text-xs uppercase text-yellow-400">Grasas</div>
                    </div>
                    <div class="p-4 bg-orange-50 rounded">
                        <div class="text-2xl font-bold text-orange-600">{{ (int)$dish->total_carbohydrates }}g</div>
                        <div class="text-xs uppercase text-orange-400">Carbos</div>
                    </div>
                </div>

                @if($dish->servings > 1)
                    <div class="bg-indigo-50 p-4 rounded mb-8 text-center text-indigo-700">
                        <strong>Por ración:</strong> {{ round($dish->total_calories / $dish->servings) }} kcal
                    </div>
                @endif

                {{-- Lista de Ingredientes --}}
                <h3 class="font-bold text-gray-700 mb-4">Ingredientes</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Ingrediente</th>
                                <th class="py-2 px-4 text-right text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                                <th class="py-2 px-4 text-right text-xs font-medium text-gray-500 uppercase">Kcal Aprox.</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($dish->products as $product)
                                <tr>
                                    <td class="py-2 px-4">{{ $product->name }}</td>
                                    <td class="py-2 px-4 text-right">{{ (float)$product->pivot->quantity }} g</td>
                                    <td class="py-2 px-4 text-right text-gray-500">
                                        {{ round(($product->calories * $product->pivot->quantity) / 100) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
