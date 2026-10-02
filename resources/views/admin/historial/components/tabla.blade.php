<div class="rounded-2xl bg-white dark:bg-[#15181d] border border-[#EAE4D8] dark:border-white/10 shadow-sm overflow-hidden transition-colors">
    <div class="px-4 py-3 border-b border-[#EAE4D8] dark:border-white/10 bg-[#F4F0E6] dark:bg-white/[0.03] rounded-t-2xl">
        <h5 class="font-bold text-base mb-0 text-gray-800 dark:text-white">
            <ion-icon name="calendar-outline" class="mr-2 text-blue-600 dark:text-cyan-400"></ion-icon>
            Historial de jornadas
        </h5>
    </div>

    {{-- Tabla normal (tablet / desktop) --}}
    <div class="hidden md:block overflow-x-auto">

        <table class="w-full text-sm text-left">

            <thead>
                <tr class="bg-[#F4F0E6] dark:bg-white/[0.03] border-b border-[#EAE4D8] dark:border-white/10 text-gray-600 dark:text-gray-400 uppercase text-xs tracking-wide transition-colors">
                    <th class="px-4 py-3 font-semibold">Fecha</th>
                    <th class="px-4 py-3 font-semibold">Entrada</th>
                    <th class="px-4 py-3 font-semibold">Salida</th>
                    <th class="px-4 py-3 font-semibold">Pausas</th>
                    <th class="px-4 py-3 font-semibold">Tiempo pausa</th>
                    <th class="px-4 py-3 font-semibold">Tiempo trabajado</th>
                    <th class="px-4 py-3 font-semibold text-center">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#EAE4D8] dark:divide-white/10">
            @forelse($asistencias as $asistencia)
                <tr class="hover:bg-[#F9F6EE] dark:hover:bg-white/5 transition-colors">

                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                        {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}
                    </td>

                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                        {{ $asistencia->hora_entrada
                            ? \Carbon\Carbon::parse($asistencia->hora_entrada)->format('h:i:s A')
                            : '--' }}
                    </td>

                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                        {{ $asistencia->hora_salida
                            ? \Carbon\Carbon::parse($asistencia->hora_salida)->format('h:i:s A')
                            : '--' }}
                    </td>

                    <td class="px-4 py-3">
                        <span class="inline-flex items-center justify-center rounded-md bg-sky-100 dark:bg-sky-500/10 text-sky-700 dark:text-sky-400 text-xs font-semibold px-2 py-1 border border-sky-200 dark:border-sky-500/20">
                            {{ $asistencia->pausas->count() }}
                        </span>
                    </td>

                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                        {{ $asistencia->tiempoPausas() }}
                    </td>

                    <td class="px-4 py-3 font-mono text-gray-800 dark:text-gray-200">
                        {{ $asistencia->formatoTiempo($asistencia->tiempoTrabajado()) }}
                    </td>

                    <td class="px-4 py-3 text-center">
                        <button type="button"
                                onclick="abrirEditarJornada(
                                    {{ $asistencia->id }},
                                    '{{ addslashes($asistencia->user->name) }}',
                                    '{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}',
                                    '{{ $asistencia->hora_entrada ?? '' }}',
                                    '{{ $asistencia->hora_salida ?? '' }}'
                                )"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg border border-blue-200 dark:border-cyan-500/30 text-blue-600 dark:text-cyan-400 bg-blue-50 dark:bg-cyan-500/10 hover:bg-blue-100 dark:hover:bg-cyan-500/20 transition-colors">
                            <ion-icon name="create-outline"></ion-icon>
                            Editar
                        </button>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-500 dark:text-gray-400">
                        No existen registros.
                    </td>
                </tr>
            @endforelse
            </tbody>

        </table>

    </div>

    {{-- Tarjetas (móvil) --}}
    <div class="block md:hidden p-3 space-y-3">

        @forelse($asistencias as $asistencia)

            <div class="rounded-xl bg-white dark:bg-white/[0.02] border border-[#EAE4D8] dark:border-white/10 overflow-hidden transition-colors">

                <div class="flex justify-between items-center px-4 py-2.5 border-b border-[#EAE4D8] dark:border-white/10 font-semibold text-gray-900 dark:text-white bg-[#F9F6EE] dark:bg-white/[0.03]">
                    <span>
                        <ion-icon name="calendar-outline" class="text-blue-600 dark:text-cyan-400"></ion-icon>
                        {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}
                    </span>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold rounded-md bg-emerald-100 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 px-2.5 py-1">
                            {{ $asistencia->formatoTiempo($asistencia->tiempoTrabajado()) }}
                        </span>
                        <button type="button"
                                onclick="abrirEditarJornada(
                                    {{ $asistencia->id }},
                                    '{{ addslashes($asistencia->user->name) }}',
                                    '{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}',
                                    '{{ $asistencia->hora_entrada ?? '' }}',
                                    '{{ $asistencia->hora_salida ?? '' }}'
                                )"
                                class="inline-flex items-center p-1.5 rounded-lg border border-blue-200 dark:border-cyan-500/30 text-blue-600 dark:text-cyan-400 bg-blue-50 dark:bg-cyan-500/10 hover:bg-blue-100 dark:hover:bg-cyan-500/20 transition-colors"
                                title="Editar jornada">
                            <ion-icon name="create-outline"></ion-icon>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-2">

                    <div class="px-4 py-2.5 border-r border-b border-[#EAE4D8] dark:border-white/10">
                        <p class="m-0 text-xs text-gray-500 dark:text-gray-400">Entrada</p>
                        <p class="mt-0.5 mb-0 text-sm text-gray-800 dark:text-white">
                            {{ $asistencia->hora_entrada
                                ? \Carbon\Carbon::parse($asistencia->hora_entrada)->format('h:i:s A')
                                : '--' }}
                        </p>
                    </div>

                    <div class="px-4 py-2.5 border-b border-[#EAE4D8] dark:border-white/10">
                        <p class="m-0 text-xs text-gray-500 dark:text-gray-400">Salida</p>
                        <p class="mt-0.5 mb-0 text-sm text-gray-800 dark:text-white">
                            {{ $asistencia->hora_salida
                                ? \Carbon\Carbon::parse($asistencia->hora_salida)->format('h:i:s A')
                                : '--' }}
                        </p>
                    </div>

                    <div class="px-4 py-2.5 col-span-2">
                        <p class="m-0 text-xs text-gray-500 dark:text-gray-400">Pausas</p>
                        <p class="mt-0.5 mb-0 text-sm text-gray-800 dark:text-white">
                            {{ $asistencia->pausas->count() }}
                            <span class="text-xs text-gray-400 dark:text-gray-500">
                                ({{ $asistencia->tiempoPausas() }})
                            </span>
                        </p>
                    </div>

                </div>

            </div>

        @empty

            <p class="text-center text-gray-500 dark:text-gray-400 py-6 mb-0">
                No existen registros.
            </p>

        @endforelse

    </div>

</div>