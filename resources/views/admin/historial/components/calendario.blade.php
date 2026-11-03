{{--
    Historial del becario como calendario mensual.
    Usa las mismas $asistencias del reporte (ya filtradas por desde/hasta);
    se muestra un mes a la vez y se cambia de mes con Alpine, sin recargar.
    Clic en un día con jornada → mismo modal de "Editar jornada" que la lista.
--}}
@php
    // Jornadas por mes ("YYYY-MM"), del más reciente al más antiguo
    $porMes = $asistencias
        ->groupBy(fn ($a) => substr($a->fecha, 0, 7))
        ->sortKeysDesc();

    $hoy = now()->toDateString();

    // Segundos → "8h 28m"
    $duracion = function (int $segundos): string {
        $h = intdiv($segundos, 3600);
        $m = intdiv($segundos % 3600, 60);
        return $h > 0 ? "{$h}h " . str_pad($m, 2, '0', STR_PAD_LEFT) . 'm' : "{$m}m";
    };

    // "09:31:38" → "9:31 a.m."
    $hora = function (?string $valor): string {
        if (!$valor) return '--';
        $c = \Carbon\Carbon::createFromTimeString($valor);
        return $c->format('g:i') . ($c->hour < 12 ? ' a.m.' : ' p.m.');
    };

    $diasSemana = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
@endphp

<div class="rounded-2xl bg-white dark:bg-gray-900 border border-[#EAE4D8] dark:border-gray-700 shadow-sm overflow-hidden"
     x-data="{ mes: 0, total: {{ $porMes->count() }} }">

    @if($porMes->isEmpty())
        <p class="text-center py-10 text-gray-500 dark:text-gray-400 mb-0">
            <ion-icon name="calendar-outline" class="text-3xl block mx-auto mb-2"></ion-icon>
            No existen registros en este periodo.
        </p>
    @else
        @foreach($porMes as $clave => $jornadasMes)
            @php
                $inicioMes = \Carbon\Carbon::createFromFormat('Y-m-d', $clave . '-01')->startOfDay();
                $desde     = $inicioMes->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
                $hasta     = $inicioMes->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SUNDAY);
                $porDia    = $jornadasMes->groupBy('fecha');

                $segundosMes = $jornadasMes->sum(fn ($a) => $a->tiempoTrabajado());
            @endphp

            <div x-show="mes === {{ $loop->index }}" @if(!$loop->first) x-cloak @endif>

                {{-- Encabezado del mes --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-4 py-3 border-b border-[#EAE4D8] dark:border-gray-700 bg-[#F5F2EA] dark:bg-gray-800">
                    <div class="flex items-center gap-2">
                        {{-- "Anterior" = mes más antiguo (índice mayor) --}}
                        <button type="button" @click="mes = Math.min(mes + 1, total - 1)" :disabled="mes >= total - 1"
                                class="p-1.5 rounded-lg border border-[#EAE4D8] dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
                                aria-label="Mes anterior">
                            <ion-icon name="chevron-back-outline"></ion-icon>
                        </button>

                        <h5 class="font-bold text-base mb-0 text-gray-800 dark:text-white capitalize min-w-[10rem] text-center">
                            <ion-icon name="calendar-outline" class="mr-1 text-blue-600 dark:text-blue-400 align-middle"></ion-icon>
                            {{ $inicioMes->translatedFormat('F Y') }}
                        </h5>

                        <button type="button" @click="mes = Math.max(mes - 1, 0)" :disabled="mes <= 0"
                                class="p-1.5 rounded-lg border border-[#EAE4D8] dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
                                aria-label="Mes siguiente">
                            <ion-icon name="chevron-forward-outline"></ion-icon>
                        </button>
                    </div>

                    <div class="flex items-center gap-3 text-xs text-gray-600 dark:text-gray-400">
                        <span><strong class="text-gray-900 dark:text-white">{{ $porDia->count() }}</strong> días</span>
                        <span><strong class="text-gray-900 dark:text-white">{{ $duracion($segundosMes) }}</strong> trabajadas</span>
                    </div>
                </div>

                {{-- Días de la semana --}}
                <div class="grid grid-cols-7 border-b border-[#EAE4D8] dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    @foreach($diasSemana as $dia)
                        <div class="py-2 text-center text-[0.65rem] sm:text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            {{ $dia }}
                        </div>
                    @endforeach
                </div>

                {{-- Cuadrícula --}}
                <div class="grid grid-cols-7">
                    @for($dia = $desde->copy(); $dia->lte($hasta); $dia->addDay())
                        @php
                            $fecha     = $dia->toDateString();
                            $delMes    = $dia->month === $inicioMes->month;
                            $jornada   = $delMes ? $porDia->get($fecha)?->first() : null;
                            $finDeSem  = $dia->isWeekend();
                            $esHoy     = $fecha === $hoy;
                            $abierta   = $jornada && !$jornada->hora_salida;
                            $corregida = $jornada && $jornada->motivo_correccion;
                        @endphp

                        <div class="min-h-[4.5rem] sm:min-h-[6.5rem] border-b border-r border-[#EAE4D8] dark:border-gray-700/70 p-1 sm:p-2
                                    {{ !$delMes ? 'bg-gray-50/60 dark:bg-gray-900/40' : ($finDeSem ? 'bg-[#FBF9F4] dark:bg-gray-800/30' : '') }}">

                            {{-- Número del día --}}
                            <div class="flex items-center justify-between mb-1">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-semibold
                                             {{ $esHoy ? 'bg-blue-600 text-white' : ($delMes ? 'text-gray-700 dark:text-gray-300' : 'text-gray-300 dark:text-gray-600') }}">
                                    {{ $dia->day }}
                                </span>
                                @if($corregida)
                                    <ion-icon name="create-outline" class="text-amber-500 text-sm" title="Corregida: {{ $jornada->motivo_correccion }}"></ion-icon>
                                @endif
                            </div>

                            @if($jornada)
                                <button type="button"
                                        onclick="(window.abrirEditarJornada||function(){})(
                                            {{ $jornada->id }},
                                            @js($jornada->user->name),
                                            @js($dia->format('d/m/Y')),
                                            @js($jornada->hora_entrada ?? ''),
                                            @js($jornada->hora_salida ?? '')
                                        )"
                                        title="Editar jornada del {{ $dia->format('d/m/Y') }}"
                                        class="w-full text-left rounded-lg px-1.5 py-1 sm:px-2 sm:py-1.5 border transition-colors
                                               {{ $abierta
                                                   ? 'bg-amber-50 dark:bg-amber-500/10 border-amber-200 dark:border-amber-500/30 hover:bg-amber-100 dark:hover:bg-amber-500/20'
                                                   : 'bg-emerald-50 dark:bg-emerald-500/10 border-emerald-200 dark:border-emerald-500/30 hover:bg-emerald-100 dark:hover:bg-emerald-500/20' }}">
                                    <span class="block font-mono font-bold text-[0.65rem] sm:text-sm {{ $abierta ? 'text-amber-700 dark:text-amber-300' : 'text-emerald-700 dark:text-emerald-300' }}">
                                        {{ $duracion($jornada->tiempoTrabajado()) }}
                                    </span>
                                    <span class="hidden sm:block text-[0.7rem] text-gray-600 dark:text-gray-400 leading-tight mt-0.5">
                                        {{ $hora($jornada->hora_entrada) }} – {{ $abierta ? 'en curso' : $hora($jornada->hora_salida) }}
                                    </span>
                                    @if($jornada->pausas->count() > 0)
                                        <span class="hidden sm:flex items-center gap-1 text-[0.65rem] text-gray-500 dark:text-gray-400 mt-0.5">
                                            <ion-icon name="cafe-outline"></ion-icon>
                                            {{ $jornada->pausas->count() }} · {{ $jornada->tiempoPausas() }}
                                        </span>
                                    @endif
                                </button>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        @endforeach

        {{-- Leyenda --}}
        <div class="flex flex-wrap items-center gap-4 px-4 py-3 text-xs text-gray-500 dark:text-gray-400">
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-emerald-200 dark:bg-emerald-500/40"></span> Jornada cerrada</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-amber-200 dark:bg-amber-500/40"></span> En curso</span>
            <span class="flex items-center gap-1.5"><ion-icon name="create-outline" class="text-amber-500"></ion-icon> Corregida por un admin</span>
            <span class="flex items-center gap-1.5"><ion-icon name="hand-left-outline"></ion-icon> Clic en un día para editar</span>
        </div>
    @endif
</div>
