<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Plato') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="dishForm()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('dishes.store') }}">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- Columna Izquierda: Datos Básicos --}}
                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <h3 class="font-bold text-gray-700 mb-4">Información General</h3>

                            <div class="mb-4">
                                <x-input-label for="name" value="Nombre del Plato" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                    required placeholder="Ej: Tortilla de Patatas" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="servings" value="Raciones que salen" />
                                <x-text-input id="servings" class="block mt-1 w-full" type="number" name="servings"
                                    value="1" min="1" required />
                                <p class="text-xs text-gray-500 mt-1">¿Para cuántas personas/comidas da esta receta?</p>
                            </div>

                            <div>
                                <x-input-label for="description" value="Notas / Receta" />
                                <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm h-32"></textarea>
                            </div>
                        </div>

                        {{-- Botón Guardar --}}
                        <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                            <x-primary-button class="w-full justify-center">
                                {{ __('Guardar Plato') }}
                            </x-primary-button>
                        </div>
                    </div>

                    {{-- Columna Derecha: Ingredientes --}}
                    <div class="lg:col-span-2">
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-bold text-gray-700">Ingredientes</h3>
                                <button type="button" @click="addIngredient()"
                                    class="text-sm bg-green-100 text-green-700 py-1 px-3 rounded hover:bg-green-200 transition">
                                    + Añadir Ingrediente
                                </button>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(row, index) in rows" :key="index">
                                    <div class="flex gap-2 items-start p-3 bg-gray-50 rounded border border-gray-100">
                                        {{-- Selector de Producto --}}
                                        <div class="flex-1">
                                            <select :name="'products[' + index + '][id]'"
                                                class="w-full border-gray-300 rounded-md text-sm" required>
                                                <option value="">Selecciona ingrediente...</option>
                                                @foreach ($products as $p)
                                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Cantidad --}}
                                        <div class="w-24">
                                            <input type="number" :name="'products[' + index + '][quantity]'"
                                                placeholder="Gramos" class="w-full border-gray-300 rounded-md text-sm"
                                                required min="1">
                                        </div>
                                        <span class="pt-2 text-sm text-gray-500">g</span>

                                        {{-- Botón Borrar Fila --}}
                                        <button type="button" @click="removeIngredient(index)"
                                            class="text-red-500 hover:text-red-700 pt-1 px-2">
                                            &times;
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <p class="text-xs text-gray-400 mt-4 text-center" x-show="rows.length === 0">
                                Añade al menos un ingrediente para calcular los nutrientes.
                            </p>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- Script Alpine --}}
    <script>
        function dishForm() {
            return {
                rows: [{
                    id: '',
                    quantity: ''
                }], // Empezamos con 1 fila
                addIngredient() {
                    this.rows.push({
                        id: '',
                        quantity: ''
                    });
                },
                removeIngredient(index) {
                    if (this.rows.length > 1) {
                        this.rows.splice(index, 1);
                    } else {
                        alert("El plato debe tener al menos un ingrediente.");
                    }
                }
            }
        }
    </script>
</x-app-layout>
