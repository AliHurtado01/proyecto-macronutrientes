<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Menú Semanal</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #444;
            margin-bottom: 5px;
        }

        .dates {
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            bg-color: #f2f2f2;
            font-weight: bold;
        }

        .day-header {
            background-color: #e0e7ff;
            color: #1e1b4b;
            padding: 10px;
            font-weight: bold;
            margin-top: 15px;
            border-radius: 4px;
        }

        .meal-row {
            margin-bottom: 5px;
        }

        .meal-title {
            font-weight: bold;
            color: #2563eb;
            font-size: 12px;
            text-transform: uppercase;
        }

        .dish-list {
            margin-left: 10px;
            font-size: 14px;
        }

        .nutrients {
            font-size: 10px;
            color: #666;
            margin-left: 10px;
        }
    </style>
</head>

<body>

    <h1>Planificación Nutricional</h1>
    <div class="dates">
        Semana del {{ $startOfWeek->format('d/m/Y') }} al {{ $endOfWeek->format('d/m/Y') }}
    </div>

    @foreach (range(0, 6) as $dayIndex)
        @php
            $currentDay = $startOfWeek->copy()->addDays($dayIndex);
            $dayKey = $currentDay->format('Y-m-d');
            $dayMenus = $menus[$dayKey] ?? collect();
        @endphp

        @if ($dayMenus->count() > 0)
            <div class="day-container">
                <div class="day-header">
                    {{ $currentDay->translatedFormat('l, d \d\e F') }}
                </div>

                <table style="margin-top: 5px;">
                    @foreach ($dayMenus as $menu)
                        <tr>
                            <td width="20%" style="background-color: #f9fafb;">
                                <div class="meal-title">{{ ucfirst(__($menu->meal_type)) }}</div>
                            </td>
                            <td>
                                @foreach ($menu->dishes as $dish)
                                    <div class="dish-name">• {{ $dish->name }}
                                        <small>(x{{ (float) $dish->pivot->portions }})</small></div>
                                @endforeach

                                @php $totals = $menu->calculateNutrients(); @endphp
                                <div class="nutrients">
                                    Total: {{ (int) $totals['calories'] }} kcal | {{ (int) $totals['protein'] }}g Prot |
                                    {{ (int) $totals['fat'] }}g Grasa
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif
    @endforeach

    <div style="margin-top: 30px; text-align: center; font-size: 10px; color: #999;">
        Generado por App Nutrientes - {{ now()->format('d/m/Y H:i') }}
    </div>

</body>

</html>
