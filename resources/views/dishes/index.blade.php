<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mis Recetas y Platos') }}
            </h2>
            <a href="{{ route('dishes.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow transition">
                + Crear Nuevo Plato
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Buscador --}}
            <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
                <form method="GET" action="{{ route('dishes.index') }}" class="flex gap-4">
                    <x-text-input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar plato..." class="w-full" />
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Buscar</button>
                </form>
            </div>

            @if($dishes->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($dishes as $dish)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 hover:shadow-md transition relative">

                            {{-- ETIQUETAS DE PROPIEDAD --}}
                            <div class="absolute top-2 right-2">
                                @if(!$dish->user_id)
                                    {{-- CASO 1: PLATO GLOBAL (Creado por Admin) --}}
                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-blue-200">
                                        Global (Receta Pública)
                                    </span>
                                @elseif($dish->user_id == auth()->id())
                                    {{-- CASO 2: MI PLATO --}}
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-green-200">
                                        Mío
                                    </span>
                                @else
                                    {{-- CASO 3: DE OTRO USUARIO (Visto por Admin) --}}
                                    <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-purple-200">
                                        Usuario #{{ $dish->user_id }}
                                    </span>
                                @endif
                            </div>

                            <div class="p-6">
                                <h3 class="font-bold text-gray-800 text-xl mb-2 pr-10 truncate">
                                    <a href="{{ route('dishes.show', $dish) }}" class="hover:text-indigo-600 hover:underline">
                                        {{ $dish->name }}
                                    </a>
                                </h3>

                                <div class="grid grid-cols-3 gap-2 text-center text-xs bg-gray-50 p-3 rounded-lg mb-4">
                                    <div>
                                        <span class="block font-bold text-gray-800 text-sm">{{ (int)$dish->total_calories }}</span>
                                        <span class="text-gray-400">Kcal</span>
                                    </div>
                                    <div>
                                        <span class="block font-bold text-blue-600 text-sm">{{ (int)$dish->total_protein }}g</span>
                                        <span class="text-gray-400">Prot</span>
                                    </div>
                                    <div>
                                        <span class="block font-bold text-orange-600 text-sm">{{ (int)$dish->total_carbohydrates }}g</span>
                                        <span class="text-gray-400">Carb</span>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center border-t pt-4">
                                    <span class="text-xs text-gray-500">{{ $dish->products_count ?? $dish->products()->count() }} Ingr.</span>

                                    <div class="flex space-x-2 items-center">
                                        {{-- Botones de acción --}}
                                        @if($dish->user_id == auth()->id())
                                            <form action="{{ route('dishes.favorite', $dish) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-gray-400 hover:text-red-500 {{ $dish->is_favorite ? 'text-red-500 font-bold' : '' }}">♥</button>
                                            </form>
                                            <a href="{{ route('dishes.edit', $dish) }}" class="text-indigo-600 text-sm font-semibold">Editar</a>
                                            <form action="{{ route('dishes.destroy', $dish) }}" method="POST" onsubmit="return confirm('¿Borrar?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 text-sm font-semibold">Borrar</button>
                                            </form>
                                        @elseif(auth()->user()->hasRole('admin'))
                                            {{-- Admin borrando cosas ajenas --}}
                                            <form action="{{ route('dishes.destroy', $dish) }}" method="POST" onsubmit="return confirm('¿Borrar receta pública/ajena?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 text-xs border border-red-200 bg-red-50 px-2 py-1 rounded">Forzar Borrado</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">{{ $dishes->links() }}</div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                    <p class="text-gray-500 mb-4">No hay platos disponibles.</p>
                    <a href="{{ route('dishes.create') }}" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">¡Crea el primero!</a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
