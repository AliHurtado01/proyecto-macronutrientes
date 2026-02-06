<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Plato') }}
        </h2>
    </x-slot>

    {{-- Inicializamos Alpine con los datos existentes --}}
    <div class="py-12" x-data="dishForm({{ $dish->products->map(fn($p) => ['id' => $p->id, 'quantity' => $p->pivot->quantity]) }})">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('dishes.update', $dish) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <h3 class="font-bold text-gray-700 mb-4">Información General</h3>
                            <div class="mb-4">
                                <x-input-label for="name" value="Nombre" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                    :value="$dish->name" required />
                            </div>
                            <div class="mb-4">
                                <x-input-label for="servings" value="Raciones" />
                                <x-text-input id="servings" class="block mt-1 w-full" type="number" name="servings"
                                    :value="$dish->servings" min="1" required />
                            </div>
                            <div>
                                <x-input-label for="description" value="Notas" />
                                <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm h-32">{{ $dish->description }}</textarea>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                            <x-primary-button
                                class="w-full justify-center">{{ __('Actualizar Plato') }}</x-primary-button>
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-bold text-gray-700">Ingredientes</h3>
                                <button type="button" @click="addIngredient()"
                                    class="text-sm bg-green-100 text-green-700 py-1 px-3 rounded hover:bg-green-200">+
                                    Añadir</button>
                            </div>
                            <div class="space-y-3">
                                <template x-for="(row, index) in rows" :key="index">
                                    <div class="flex gap-2 items-start p-3 bg-gray-50 rounded border border-gray-100">
                                        <div class="flex-1">
                                            <select :name="'products[' + index + '][id]'" x-model="row.id"
                                                class="w-full border-gray-300 rounded-md text-sm" required>
                                                <option value="">Selecciona ingrediente...</option>
                                                @foreach ($products as $p)
                                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="w-24">
                                            <input type="number" :name="'products[' + index + '][quantity]'"
                                                x-model="row.quantity" placeholder="Gramos"
                                                class="w-full border-gray-300 rounded-md text-sm" required
                                                min="1">
                                        </div>
                                        <span class="pt-2 text-sm text-gray-500">g</span>
                                        <button type="button" @click="removeIngredient(index)"
                                            class="text-red-500 hover:text-red-700 pt-1 px-2">&times;</button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function dishForm(initialData) {
            return {
                rows: initialData.length ? initialData : [{
                    id: '',
                    quantity: ''
                }],
                addIngredient() {
                    this.rows.push({
                        id: '',
                        quantity: ''
                    });
                },
                removeIngredient(index) {
                    if (this.rows.length > 1) this.rows.splice(index, 1);
                    else alert("El plato debe tener al menos un ingrediente.");
                }
            }
        }
    </script>
</x-app-layout>
