<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ingredientes y Productos') }}
            </h2>
            <a href="{{ route('products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition">
                + Nuevo Ingrediente
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filtros y Buscador --}}
            <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
                <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    {{-- Buscador --}}
                    <div class="col-span-2">
                        <x-text-input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar huevo, manzana..." class="w-full" />
                    </div>

                    {{-- Categoría --}}
                    <div>
                        <select name="category_id" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" onchange="this.form.submit()">
                            <option value="">Todas las categorías</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filtro Mis Productos Favoritos --}}
                    <div class="flex items-center">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="favorites" value="1" onchange="this.form.submit()" {{ request('favorites') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Mis Favoritos</span>
                        </label>
                    </div>
                </form>
            </div>

            {{-- Grid de Productos --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($products as $product)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 hover:shadow-md transition relative">

                        {{-- Etiqueta: Mío vs BEDCA --}}
                        <div class="absolute top-2 right-2">
                            @if($product->user_id)
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-green-200">Mío</span>
                            @else
                                <span class="bg-blue-50 text-blue-600 text-xs font-semibold px-2.5 py-0.5 rounded border border-blue-100">BEDCA</span>
                            @endif
                        </div>

                        <div class="p-6">
                            <div class="mb-4 pr-10"> <h3 class="font-bold text-gray-800 text-lg leading-tight mb-1 truncate" title="{{ $product->name }}">
                                    {{-- ENLACE AL DETALLE --}}
                                    <a href="{{ route('products.show', $product) }}" class="hover:text-blue-600 hover:underline transition">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                <p class="text-xs text-gray-500">{{ $product->category->name }}</p>
                            </div>

                            {{-- Resumen Nutricional --}}
                            <div class="grid grid-cols-4 gap-2 text-center text-xs bg-gray-50 p-3 rounded-lg mb-4">
                                <div>
                                    <span class="block font-bold text-gray-800 text-sm">{{ (int)$product->calories }}</span>
                                    <span class="text-gray-400">Kcal</span>
                                </div>
                                <div>
                                    <span class="block font-bold text-blue-600 text-sm">{{ (float)$product->protein }}</span>
                                    <span class="text-gray-400">Prot</span>
                                </div>
                                <div>
                                    <span class="block font-bold text-yellow-600 text-sm">{{ (float)$product->total_fat }}</span>
                                    <span class="text-gray-400">Grasa</span>
                                </div>
                                <div>
                                    <span class="block font-bold text-orange-600 text-sm">{{ (float)$product->carbohydrates }}</span>
                                    <span class="text-gray-400">Carb</span>
                                </div>
                            </div>

                            {{-- Acciones --}}
                            <div class="flex justify-between items-center pt-2 border-t border-gray-100">

                                {{-- Si es MÍO: Puedo editar, borrar y marcar favorito --}}
                                @if($product->user_id == auth()->id())
                                    <form action="{{ route('products.favorite', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-sm flex items-center {{ $product->is_favorite ? 'text-red-500 font-bold' : 'text-gray-400 hover:text-red-500' }}">
                                            <span class="mr-1">♥</span> {{ $product->is_favorite ? 'Favorito' : 'Favorito' }}
                                        </button>
                                    </form>

                                    <div class="flex space-x-3">
                                        <a href="{{ route('products.edit', $product) }}" class="text-sm text-indigo-600 hover:text-indigo-900">Editar</a>

                                        <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres borrar este ingrediente?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-900">Borrar</button>
                                        </form>
                                    </div>

                                {{-- Si es BEDCA: Solo lectura --}}
                                @else
                                    <span class="text-xs text-gray-400 italic" title="No puedes marcar favoritos productos globales en esta versión">
                                        ♥ Global (No editable)
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-gray-50 rounded border border-dashed border-gray-300">
                        <p class="text-gray-500 text-lg">No se encontraron productos.</p>
                        @if(request('search') || request('category_id'))
                            <a href="{{ route('products.index') }}" class="text-blue-600 hover:underline mt-2 inline-block">Limpiar filtros</a>
                        @endif
                    </div>
                @endforelse
            </div>

            {{-- Paginación --}}
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
