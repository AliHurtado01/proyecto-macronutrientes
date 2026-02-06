<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Administración') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Estadísticas Globales de la Plataforma</h3>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
                    <div class="p-4 bg-blue-50 rounded border border-blue-200">
                        <span class="block text-3xl font-bold text-blue-600">{{ $stats['total_users'] }}</span>
                        <span class="text-gray-500">Usuarios Registrados</span>
                    </div>
                    <div class="p-4 bg-green-50 rounded border border-green-200">
                        <span class="block text-3xl font-bold text-green-600">{{ $stats['total_products'] }}</span>
                        <span class="text-gray-500">Productos Totales</span>
                    </div>
                    <div class="p-4 bg-purple-50 rounded border border-purple-200">
                        <span class="block text-3xl font-bold text-purple-600">{{ $stats['total_dishes'] }}</span>
                        <span class="text-gray-500">Platos Creados</span>
                    </div>
                    <div class="p-4 bg-yellow-50 rounded border border-yellow-200">
                        <span class="block text-3xl font-bold text-yellow-600">{{ $stats['total_menus'] }}</span>
                        <span class="text-gray-500">Menús Planificados</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
