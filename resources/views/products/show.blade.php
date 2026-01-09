<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- Encabezado con Categoría y Botones --}}
                <div class="flex justify-between items-start mb-6 border-b pb-4">
                    <div>
                        <span class="inline-block bg-gray-100 text-gray-800 text-sm font-medium px-2.5 py-0.5 rounded">
                            {{ $product->category->name }}
                        </span>
                        @if (!$product->user_id)
                            <span
                                class="inline-block ml-2 bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded">Base
                                de Datos BEDCA</span>
                        @else
                            <span
                                class="inline-block ml-2 bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded">Personalizado</span>
                        @endif
                    </div>

                    <div class="flex space-x-2">
                        @if ($product->user_id === auth()->id())
                            {{-- Botones solo si es MÍO --}}
                            <a href="{{ route('products.edit', $product) }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition">
                                Editar
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST"
                                onsubmit="return confirm('¿Seguro que quieres borrar este ingrediente? Se eliminará de tus platos.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition">
                                    Borrar
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('products.index') }}"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition">
                            Volver
                        </a>
                    </div>
                </div>

                {{-- Tarjetas de Macronutrientes Principales --}}
                <h3 class="text-lg font-semibold mb-4 text-gray-700">Información Nutricional (por 100g)</h3>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="p-4 bg-gray-50 rounded-lg text-center border border-gray-200">
                        <div class="text-3xl font-bold text-gray-800">{{ (float) $product->calories }}</div>
                        <div class="text-sm text-gray-500 font-medium">Kcal</div>
                    </div>
                    <div class="p-4 bg-blue-50 rounded-lg text-center border border-blue-100">
                        <div class="text-3xl font-bold text-blue-600">{{ (float) $product->protein }}g</div>
                        <div class="text-sm text-blue-800 font-medium">Proteínas</div>
                    </div>
                    <div class="p-4 bg-yellow-50 rounded-lg text-center border border-yellow-100">
                        <div class="text-3xl font-bold text-yellow-600">{{ (float) $product->total_fat }}g</div>
                        <div class="text-sm text-yellow-800 font-medium">Grasas</div>
                    </div>
                    <div class="p-4 bg-orange-50 rounded-lg text-center border border-orange-100">
                        <div class="text-3xl font-bold text-orange-600">{{ (float) $product->carbohydrates }}g</div>
                        <div class="text-sm text-orange-800 font-medium">Carbohidratos</div>
                    </div>
                </div>

                {{-- Detalles Específicos --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h4 class="font-bold text-gray-700 mb-3 border-b pb-1">Desglose de Grasas</h4>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex justify-between">
                                <span>Saturadas:</span>
                                <span class="font-semibold">{{ (float) $product->saturated_fat }} g</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Monoinsaturadas:</span>
                                <span class="font-semibold">{{ (float) $product->monounsaturated_fat }} g</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Poliinsaturadas:</span>
                                <span class="font-semibold">{{ (float) $product->polyunsaturated_fat }} g</span>
                            </li>
                            <li class="flex justify-between text-yellow-700">
                                <span>Colesterol:</span>
                                <span class="font-semibold">{{ (float) $product->cholesterol }} mg</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-700 mb-3 border-b pb-1">Otros Componentes</h4>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex justify-between">
                                <span>Fibra:</span>
                                <span class="font-semibold">{{ (float) $product->fiber }} g</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Agua (Humedad):</span>
                                <span class="font-semibold">{{ (float) $product->water }} g</span>
                            </li>
                            <li class="flex justify-between text-gray-400 text-sm italic mt-4">
                                <span>ID Base de Datos:</span>
                                <span>{{ $product->bedca_id ?? 'N/A' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
