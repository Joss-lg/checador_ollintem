@props(['asistencias'])

<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">

    @php
        $stats = [
            ['label' => 'Registros', 'value' => $asistencias->total(), 'color' => 'text-gray-900 dark:text-white'],
            ['label' => 'Becarios', 'value' => $asistencias->pluck('user_id')->unique()->count(), 'color' => 'text-blue-600 dark:text-blue-400'],
            ['label' => 'Finalizados', 'value' => $asistencias->whereNotNull('hora_salida')->count(), 'color' => 'text-emerald-600 dark:text-emerald-400'],
            ['label' => 'En curso', 'value' => $asistencias->whereNull('hora_salida')->count(), 'color' => 'text-amber-600 dark:text-amber-400'],
        ];
    @endphp

    @foreach($stats as $stat)
    <div class="bg-white dark:bg-[#15181d] border border-[#EAE4D8] dark:border-white/10 rounded-xl shadow-sm h-full">
        <div class="p-3 sm:p-5">
            <small class="text-gray-500 dark:text-gray-400 uppercase font-semibold text-[0.75rem]">
                {{ $stat['label'] }}
            </small>
            <h3 class="font-bold {{ $stat['color'] }} mt-1 sm:mt-2 text-lg sm:text-xl">
                {{ $stat['value'] }}
            </h3>
        </div>
    </div>
    @endforeach
</div>