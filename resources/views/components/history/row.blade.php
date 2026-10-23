<tr class="border-t border-[#EAE4D8] dark:border-gray-700 hover:bg-[#F9F6EE] dark:hover:bg-gray-800 transition-colors">

    {{-- Becario (en móvil el nombre y el correo se recortan con "…" para no ensanchar la tabla) --}}
    <td class="px-2 md:px-4 py-3 min-w-0">
        <strong class="block truncate md:whitespace-normal text-[0.8rem] md:text-base text-gray-900 dark:text-white"
                title="{{ $asistencia->user->name }}">
            {{ $asistencia->user->name }}
        </strong>
        <small class="block truncate md:whitespace-normal text-[0.65rem] md:text-[0.875em] text-gray-500 dark:text-gray-400"
               title="{{ $asistencia->user->email }}">
            {{ $asistencia->user->email }}
        </small>
    </td>

    {{-- Fecha (corta en móvil: dd/mm/aa) --}}
    <td class="px-2 md:px-4 py-3 text-xs md:text-base text-gray-700 dark:text-gray-300">
        <span class="md:hidden">{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/y') }}</span>
        <span class="hidden md:inline">{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}</span>
    </td>

    {{-- Entrada --}}
    <td class="hidden md:table-cell px-4 py-3 text-gray-700 dark:text-gray-300">
        @if($asistencia->hora_entrada)
            {{ \Carbon\Carbon::parse($asistencia->hora_entrada)->format('h:i:s A') }}
        @else
            --
        @endif
    </td>

    {{-- Salida --}}
    <td class="hidden md:table-cell px-4 py-3 text-gray-700 dark:text-gray-300">
        @if($asistencia->hora_salida)
            {{ \Carbon\Carbon::parse($asistencia->hora_salida)->format('h:i:s A') }}
        @else
            --
        @endif
    </td>

    {{-- Número de pausas --}}
    <td class="hidden md:table-cell px-4 py-3">
        <span class="inline-flex items-center justify-center rounded-full bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-300 text-sm px-2.5 py-1 font-medium">
            {{ $asistencia->pausas->count() }}
        </span>
    </td>

    {{-- Tiempo total pausas --}}
    <td class="hidden md:table-cell px-4 py-3 text-gray-700 dark:text-gray-300">
        {{ $asistencia->tiempoPausas() }}
    </td>

    {{-- Tiempo trabajado --}}
    <td class="px-2 md:px-4 py-3 text-xs md:text-base font-medium text-gray-800 dark:text-gray-200">
        {{ $asistencia->formatoTiempo($asistencia->tiempoTrabajado()) }}
    </td>

    {{-- Acción (en móvil solo el ícono) --}}
    <td class="px-2 md:px-4 py-3">
        <a href="{{ route('admin.historial.reporte', $asistencia->user->id) }}"
           title="Reporte" aria-label="Reporte"
           class="inline-flex items-center justify-center gap-1 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 bg-white dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg h-7 w-7 md:h-auto md:w-auto md:px-3 md:py-1.5 text-sm transition-colors no-underline">
            <ion-icon name="document-outline" class="md:mr-1"></ion-icon>
            <span class="hidden md:inline">Reporte</span>
        </a>
    </td>
</tr>