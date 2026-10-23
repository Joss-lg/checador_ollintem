<div class="bg-white dark:bg-gray-900 border border-[#EAE4D8] dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
    <div class="flex justify-between items-center px-3 sm:px-5 py-4 border-b border-[#EAE4D8] dark:border-gray-700">
        <h5 class="mb-0 text-gray-900 dark:text-white font-bold text-base sm:text-lg">
            <ion-icon name="grid-outline" class="mr-2 text-blue-500"></ion-icon> Registros
        </h5>
        <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 text-xs sm:text-sm px-3 py-1 font-medium">
            {{ $asistencias->total() }} registros
        </span>
    </div>

    {{-- Móvil: sin scroll horizontal (table-fixed). Escritorio: igual que antes. --}}
    <div class="overflow-x-hidden md:overflow-x-auto">
        <table class="w-full table-fixed md:table-auto text-gray-800 dark:text-gray-200 align-middle mb-0">
            <thead class="bg-gray-50 dark:bg-gray-800/50">
                <tr class="text-gray-500 dark:text-gray-400 border-b border-[#EAE4D8] dark:border-gray-700">
                    <th class="px-2 md:px-4 py-3 text-left text-[0.65rem] md:text-xs uppercase font-bold">Becario</th>
                    <th class="w-[4.5rem] md:w-auto px-2 md:px-4 py-3 text-left text-[0.65rem] md:text-xs uppercase font-bold">Fecha</th>
                    <th class="hidden md:table-cell px-4 py-3 text-left text-xs uppercase font-bold">Entrada</th>
                    <th class="hidden md:table-cell px-4 py-3 text-left text-xs uppercase font-bold">Salida</th>
                    <th class="hidden md:table-cell px-4 py-3 text-left text-xs uppercase font-bold">Pausas</th>
                    <th class="hidden md:table-cell px-4 py-3 text-left text-xs uppercase font-bold">Tiempo pausa</th>
                    <th class="w-[4.25rem] md:w-auto px-2 md:px-4 py-3 text-left text-[0.65rem] md:text-xs uppercase font-bold">
                        <span class="md:hidden">Tiempo</span>
                        <span class="hidden md:inline">Tiempo trabajado</span>
                    </th>
                    <th class="w-[2.75rem] md:w-auto px-2 md:px-4 py-3 text-left text-[0.65rem] md:text-xs uppercase font-bold">
                        <span class="sr-only md:not-sr-only">Acciones</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#EAE4D8] dark:divide-gray-700">
                @forelse($asistencias as $asistencia)
                    <x-history.row :asistencia="$asistencia" />
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-10 text-gray-500 dark:text-gray-400">
                            <ion-icon name="folder-outline" class="text-4xl"></ion-icon>
                            <p class="mt-2">No existen registros.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @include('components.history.modal')
</div>