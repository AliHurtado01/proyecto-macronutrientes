<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Planificar Comida') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="menuForm()">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('menus.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <x-input-label for="date" value="Fecha" />
                            <x-text-input id="date" class="block mt-1 w-full" type="date" name="date"
                                value="{{ $date }}" required />
                        </div>

                        <div>
                            <x-input-label for="meal_type" value="Momento del Día" />
                            <select id="meal_type" name="meal_type"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                @foreach (meal_type_options() as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('meal_type')" class="mt-2" />
                        </div>
                    </div>

                    <div class="border-t pt-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-gray-700">Platos a consumir</h3>
                            <button type="button" @click="addDish()"
                                class="text-sm bg-indigo-100 text-indigo-700 py-1 px-3 rounded hover:bg-indigo-200">
                                + Añadir Plato
                            </button>
                        </div>

                        <div class="space-y-3">
                            <template x-for="(row, index) in rows" :key="index">
                                <div class="flex gap-2 items-start p-3 bg-gray-50 rounded border border-gray-100">
                                    <div class="flex-1">
                                        <select :name="'dishes[' + index + '][id]'"
                                            class="w-full border-gray-300 rounded-md text-sm" required>
                                            <option value="">Selecciona plato...</option>
                                            @foreach ($dishes as $dish)
                                                <option value="{{ $dish->id }}">{{ $dish->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="w-32 flex items-center gap-2">
                                        <input type="number" :name="'dishes[' + index + '][portions]'" step="0.5"
                                            value="1" class="w-full border-gray-300 rounded-md text-sm" required
                                            min="0.5">
                                        <span class="text-xs text-gray-500">rac.</span>
                                    </div>
                                    <button type="button" @click="removeDish(index)"
                                        class="text-red-500 hover:text-red-700 pt-1 px-2">&times;</button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <a href="{{ route('menus.index') }}"
                            class="text-gray-500 underline mr-4 self-center">Cancelar</a>
                        <x-primary-button>{{ __('Guardar en Calendario') }}</x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function menuForm() {
            return {
                rows: [{
                    id: '',
                    portions: 1
                }],
                addDish() {
                    this.rows.push({
                        id: '',
                        portions: 1
                    });
                },
                removeDish(index) {
                    if (this.rows.length > 1) this.rows.splice(index, 1);
                    else alert("Debes añadir al menos un plato.");
                }
            }
        }
    </script>
</x-app-layout>
