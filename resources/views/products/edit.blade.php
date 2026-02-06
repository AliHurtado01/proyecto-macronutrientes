<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Ingrediente: ') . $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('products.update', $product) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <x-input-label for="name" value="Nombre" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="$product->name" required />
                        </div>
                        <div>
                            <x-input-label for="category_id" value="Categoría" />
                            <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="border-t pt-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información Nutricional</h3>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="calories" value="Calorías" />
                                <x-text-input id="calories" class="block mt-1 w-full" type="number" step="0.01" name="calories" :value="$product->calories" required />
                            </div>
                            <div>
                                <x-input-label for="protein" value="Proteínas" />
                                <x-text-input id="protein" class="block mt-1 w-full" type="number" step="0.01" name="protein" :value="$product->protein" required />
                            </div>
                            <div>
                                <x-input-label for="total_fat" value="Grasa Total" />
                                <x-text-input id="total_fat" class="block mt-1 w-full" type="number" step="0.01" name="total_fat" :value="$product->total_fat" required />
                            </div>
                            <div>
                                <x-input-label for="carbohydrates" value="Carbohidratos" />
                                <x-text-input id="carbohydrates" class="block mt-1 w-full" type="number" step="0.01" name="carbohydrates" :value="$product->carbohydrates" required />
                            </div>
                            {{-- Agrega el resto de campos si lo necesitas editar, por brevedad dejo los principales --}}
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('products.index') }}" class="text-sm text-gray-600 underline mr-4">Cancelar</a>
                        <x-primary-button>{{ __('Actualizar') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
