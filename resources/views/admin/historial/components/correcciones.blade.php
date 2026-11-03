{{-- Bitácora de correcciones manuales (tabla correcciones_asistencia) --}}
<div class="mt-6 rounded-2xl bg-white dark:bg-gray-900 border border-[#EAE4D8] dark:border-gray-700 shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-[#EAE4D8] dark:border-gray-700 bg-[#F5F2EA] dark:bg-gray-800 flex items-center justify-between">
        <h5 class="font-bold text-base mb-0 text-gray-800 dark:text-white">
            <ion-icon name="create-outline" class="mr-2 text-amber-600 dark:text-amber-400"></ion-icon>
            Correcciones de horas
        </h5>
        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $correcciones->count() }}</span>
    </div>

    @if($correcciones->isEmpty())
        <p class="text-center text-sm text-gray-500 dark:text-gray-400 py-6 mb-0">
            Sin correcciones registradas en este periodo.
        </p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-[#EAE4D8] dark:border-gray-700 text-gray-600 dark:text-gray-400 uppercase text-xs tracking-wide">
                        <th class="px-4 py-3 font-semibold">Jornada</th>
                        <th class="px-4 py-3 font-semibold">Antes</th>
                        <th class="px-4 py-3 font-semibold">Después</th>
                        <th class="px-4 py-3 font-semibold">Motivo</th>
                        <th class="px-4 py-3 font-semibold">Corrigió</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($correcciones as $c)
                        <tr class="border-b border-[#EAE4D8] dark:border-gray-700 last:border-0 text-gray-700 dark:text-gray-300">
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($c->asistencia->fecha)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap font-mono text-xs">
                                {{ $c->entrada_antes ?? '--' }} – {{ $c->salida_antes ?? '--' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap font-mono text-xs">
                                {{ $c->entrada_despues ?? '--' }} – {{ $c->salida_despues ?? '--' }}
                            </td>
                            <td class="px-4 py-3 min-w-[12rem]">{{ $c->motivo }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ $c->admin->name ?? 'Usuario eliminado' }}
                                <span class="block text-xs text-gray-400">{{ $c->created_at->format('d/m/Y h:i A') }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
