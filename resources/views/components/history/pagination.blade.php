@props(['asistencias'])

<div class="mt-4 p-4 bg-white dark:bg-[#15181d] border border-[#EAE4D8] dark:border-white/10 rounded-2xl shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="text-sm text-gray-600 dark:text-gray-400">
            Mostrando <span class="font-semibold text-gray-900 dark:text-white">{{ $asistencias->firstItem() ?? 0 }}</span> 
            a <span class="font-semibold text-gray-900 dark:text-white">{{ $asistencias->lastItem() ?? 0 }}</span> 
            de <span class="font-semibold text-gray-900 dark:text-white">{{ $asistencias->total() }}</span> resultados
        </div>
        
        <div>
            {{-- Forzamos el uso de tu vista personalizada --}}
            {{ $asistencias->links('vendor.pagination.ollin') }}
        </div>
    </div>
</div>