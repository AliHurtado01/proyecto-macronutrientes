<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nuevo Ingrediente Personalizado') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('products.store') }}" class="space-y-6">
                    @csrf

                    {{-- Datos Básicos --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <x-input-label for="name" value="Nombre del Alimento" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <x-input-label for="category_id" value="Categoría" />
                            <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="border-t pt-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información Nutricional (por 100g)</h3>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            {{-- Macros Principales --}}
                            <div>
                                <x-input-label for="calories" value="Calorías (Kcal)" />
                                <x-text-input id="calories" class="block mt-1 w-full bg-gray-50" type="number" step="0.01" name="calories" required />
                            </div>
                            <div>
                                <x-input-label for="protein" value="Proteínas (g)" />
                                <x-text-input id="protein" class="block mt-1 w-full bg-blue-50" type="number" step="0.01" name="protein" required />
                            </div>
                            <div>
                                <x-input-label for="carbohydrates" value="Carbohidratos (g)" />
                                <x-text-input id="carbohydrates" class="block mt-1 w-full bg-orange-50" type="number" step="0.01" name="carbohydrates" required />
                            </div>

                            {{-- Desglose de Grasas --}}
                            <div class="col-span-full mt-2 mb-2 border-b pb-2 text-sm font-semibold text-gray-500">Grasas y Colesterol</div>

                            <div>
                                <x-input-label for="total_fat" value="Grasa Total (g)" />
                                <x-text-input id="total_fat" class="block mt-1 w-full bg-yellow-50" type="number" step="0.01" name="total_fat" required />
                            </div>
                            <div>
                                <x-input-label for="saturated_fat" value="Saturada (g)" />
                                <x-text-input id="saturated_fat" class="block mt-1 w-full" type="number" step="0.01" name="saturated_fat" value="0" />
                            </div>
                            <div>
                                <x-input-label for="monounsaturated_fat" value="Monoinsaturada (g)" />
                                <x-text-input id="monounsaturated_fat" class="block mt-1 w-full" type="number" step="0.01" name="monounsaturated_fat" value="0" />
                            </div>
                            <div>
                                <x-input-label for="polyunsaturated_fat" value="Poliinsaturada (g)" />
                                <x-text-input id="polyunsaturated_fat" class="block mt-1 w-full" type="number" step="0.01" name="polyunsaturated_fat" value="0" />
                            </div>
                            <div>
                                <x-input-label for="cholesterol" value="Colesterol (mg)" />
                                <x-text-input id="cholesterol" class="block mt-1 w-full" type="number" step="0.01" name="cholesterol" value="0" />
                            </div>

                            {{-- Otros --}}
                            <div class="col-span-full mt-2 mb-2 border-b pb-2 text-sm font-semibold text-gray-500">Otros</div>

                            <div>
                                <x-input-label for="fiber" value="Fibra (g)" />
                                <x-text-input id="fiber" class="block mt-1 w-full" type="number" step="0.01" name="fiber" value="0" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('products.index') }}" class="text-sm text-gray-600 underline mr-4">Cancelar</a>
                        <x-primary-button>
                            {{ __('Guardar Ingrediente') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
