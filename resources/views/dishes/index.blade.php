<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mis Platos y Recetas') }}
            </h2>
            <a href="{{ route('dishes.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow transition">
                + Crear Plato
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filtros --}}
            <div class="bg-white p-4 rounded-lg shadow-sm mb-6 flex justify-between items-center">
                <form method="GET" action="{{ route('dishes.index') }}" class="flex-1 flex gap-4">
                    <x-text-input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Buscar plato..." class="w-full md:w-1/3" />

                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="favorites" value="1" onchange="this.form.submit()"
                            {{ request('favorites') ? 'checked' : '' }}
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span class="ml-2 text-gray-600">Solo Favoritos</span>
                    </label>
                </form>
            </div>

            {{-- Grid de Platos --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($dishes as $dish)
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 hover:shadow-md transition relative group">

                        <div class="p-6">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-gray-800 text-xl leading-tight truncate"
                                    title="{{ $dish->name }}">
                                    <a href="{{ route('dishes.show', $dish) }}"
                                        class="hover:text-indigo-600 transition">
                                        {{ $dish->name }}
                                    </a>
                                </h3>
                                <form action="{{ route('dishes.favorite', $dish) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="text-xl {{ $dish->is_favorite ? 'text-red-500' : 'text-gray-300 hover:text-red-300' }}">
                                        ♥
                                    </button>
                                </form>
                            </div>

                            <p class="text-sm text-gray-500 mb-4 line-clamp-2">
                                {{ $dish->description ?? 'Sin descripción' }}</p>

                            {{-- Resumen Nutricional (Total del plato) --}}
                            <div class="bg-indigo-50 rounded-lg p-3 mb-4">
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Calorías Totales:</span>
                                    <span class="font-bold text-indigo-700">{{ (int) $dish->total_calories }}
                                        kcal</span>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>Prot: {{ (int) $dish->total_protein }}g</span>
                                    <span>Grasa: {{ (int) $dish->total_fat }}g</span>
                                    <span>Carb: {{ (int) $dish->total_carbohydrates }}g</span>
                                </div>
                            </div>

                            <div class="flex justify-between items-center text-xs text-gray-400 border-t pt-3">
                                <span>{{ $dish->servings }} raciones</span>
                                <div class="flex space-x-2">
                                    <a href="{{ route('dishes.edit', $dish) }}"
                                        class="text-indigo-600 hover:underline">Editar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">No has creado platos todavía.</p>
                        <a href="{{ route('dishes.create') }}"
                            class="text-indigo-600 hover:underline mt-2 inline-block">¡Crea el primero!</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $dishes->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
