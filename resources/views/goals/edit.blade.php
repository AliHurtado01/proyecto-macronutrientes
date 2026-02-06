<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Objetivos Nutricionales') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <p class="text-gray-600 mb-6">
                    Define tus metas diarias. Estos valores se usarán en el Dashboard para calcular tu progreso y mostrar alertas.
                </p>

                <form method="POST" action="{{ route('goals.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="daily_calories" :value="__('Objetivo Calorías (Kcal)')" />
                            <x-text-input id="daily_calories" class="block mt-1 w-full" type="number" step="1" name="daily_calories" :value="old('daily_calories', $goal->daily_calories)" />
                        </div>

                        <div>
                            <x-input-label for="daily_protein" :value="__('Objetivo Proteínas (g)')" />
                            <x-text-input id="daily_protein" class="block mt-1 w-full" type="number" step="1" name="daily_protein" :value="old('daily_protein', $goal->daily_protein)" />
                        </div>

                        <div>
                            <x-input-label for="daily_fat" :value="__('Objetivo Grasas (g)')" />
                            <x-text-input id="daily_fat" class="block mt-1 w-full" type="number" step="1" name="daily_fat" :value="old('daily_fat', $goal->daily_fat)" />
                        </div>

                        <div>
                            <x-input-label for="daily_carbohydrates" :value="__('Objetivo Carbohidratos (g)')" />
                            <x-text-input id="daily_carbohydrates" class="block mt-1 w-full" type="number" step="1" name="daily_carbohydrates" :value="old('daily_carbohydrates', $goal->daily_carbohydrates)" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4 pt-4 border-t">
                        <x-primary-button>
                            {{ __('Guardar Objetivos') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
