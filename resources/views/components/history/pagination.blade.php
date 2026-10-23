@props(['asistencias'])

<div class="mt-4 p-3 sm:p-4 bg-white dark:bg-[#15181d] border border-[#EAE4D8] dark:border-white/10 rounded-2xl shadow-sm">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="text-xs sm:text-sm text-center sm:text-left text-gray-600 dark:text-gray-400">
            Mostrando <span class="font-semibold text-gray-900 dark:text-white">{{ $asistencias->firstItem() ?? 0 }}</span>
            a <span class="font-semibold text-gray-900 dark:text-white">{{ $asistencias->lastItem() ?? 0 }}</span>
            de <span class="font-semibold text-gray-900 dark:text-white">{{ $asistencias->total() }}</span> resultados
        </div>

        <div class="w-full sm:w-auto">
            {{-- Forzamos el uso de tu vista personalizada --}}
            {{ $asistencias->links('vendor.pagination.ollin') }}
        </div>
    </div>
</div>