<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Calendario de Comidas') }}
            </h2>
            <div class="flex space-x-2">
                {{-- Botón PDF NUEVO --}}
                <a href="{{ route('menus.export_pdf', ['date' => $date->format('Y-m-d')]) }}"
                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded shadow text-sm flex items-center">
                    <span class="mr-1">📄</span> PDF Semanal
                </a>

                <a href="{{ route('menus.create', ['date' => $date->format('Y-m-d')]) }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow text-sm">
                    + Planificar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Navegación Semanal --}}
            <div class="flex justify-between items-center mb-6 bg-white p-4 rounded-lg shadow-sm">
                <a href="{{ route('menus.index', ['date' => $startOfWeek->copy()->subWeek()->format('Y-m-d')]) }}"
                    class="text-gray-600 hover:text-indigo-600 font-bold">
                    &larr; Semana Anterior
                </a>

                <div class="text-center">
                    <span class="text-lg font-bold text-gray-800">
                        {{ $startOfWeek->format('d M') }} - {{ $endOfWeek->format('d M, Y') }}
                    </span>
                    @if (!$date->isToday())
                        <br>
                        <a href="{{ route('menus.index') }}" class="text-xs text-indigo-500 hover:underline">Ir a
                            Hoy</a>
                    @endif
                </div>

                <a href="{{ route('menus.index', ['date' => $startOfWeek->copy()->addWeek()->format('Y-m-d')]) }}"
                    class="text-gray-600 hover:text-indigo-600 font-bold">
                    Semana Siguiente &rarr;
                </a>
            </div>

            {{-- Grid del Calendario (7 Columnas) --}}
            <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
                @foreach (range(0, 6) as $dayIndex)
                    @php
                        $currentDay = $startOfWeek->copy()->addDays($dayIndex);
                        $dayKey = $currentDay->format('Y-m-d');
                        $dayMenus = $menus[$dayKey] ?? collect();
                        $isToday = $currentDay->isToday();
                    @endphp

                    <div class="flex flex-col h-full">
                        {{-- Cabecera del Día --}}
                        <div
                            class="text-center p-2 rounded-t-lg {{ $isToday ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                            <div class="font-bold uppercase text-xs">{{ $currentDay->translatedFormat('D') }}</div>
                            <div class="text-lg font-bold">{{ $currentDay->format('d') }}</div>
                        </div>

                        {{-- Cuerpo del Día --}}
                        <div
                            class="bg-white p-2 rounded-b-lg shadow-sm flex-1 min-h-[200px] border border-gray-100 space-y-2">

                            {{-- Botón rápido añadir --}}
                            <a href="{{ route('menus.create', ['date' => $dayKey]) }}"
                                class="block w-full text-center text-xs border border-dashed border-gray-300 text-gray-400 p-1 rounded hover:bg-gray-50 mb-2">
                                + Añadir
                            </a>

                            {{-- Lista de Menús del día --}}
                            @foreach ($dayMenus as $menu)
                                <a href="{{ route('menus.show', $menu) }}"
                                    class="block p-2 rounded text-xs border-l-4 hover:shadow-md transition bg-gray-50 border-indigo-400">
                                    <div class="font-bold text-gray-700 mb-1">
                                        {{-- Icono según tipo --}}
                                        @switch($menu->meal_type)
                                            @case('breakfast')
                                                🍳
                                            @break

                                            @case('lunch')
                                                🍽️
                                            @break

                                            @case('dinner')
                                                🌙
                                            @break

                                            @default
                                                🍴
                                        @endswitch
                                        {{ ucfirst($menu->meal_type) }}
                                    </div>
                                    <div class="text-gray-500 truncate">
                                        @foreach ($menu->dishes as $dish)
                                            {{ $dish->name }}@if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </div>
                                </a>
                            @endforeach

                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
