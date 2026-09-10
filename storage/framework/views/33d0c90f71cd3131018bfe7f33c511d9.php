<div class="rounded-2xl bg-white dark:bg-gray-900 border border-[#EAE4D8] dark:border-gray-700 shadow-sm overflow-hidden">
<div class="px-4 py-3 border-b border-[#EAE4D8] dark:border-gray-700 bg-[#F5F2EA] dark:bg-gray-800 rounded-t-2xl">
    <h5 class="font-bold text-base mb-0 text-gray-800 dark:text-white">
        <ion-icon name="calendar-outline" class="mr-2 text-blue-600 dark:text-blue-400"></ion-icon>
        Historial de jornadas
    </h5>
</div>

    
    <div class="hidden md:block overflow-x-auto">

        <table class="w-full text-sm text-left">

            <thead>
                <tr class="bg-[#F5F2EA] dark:bg-gray-800 border-b border-[#EAE4D8] dark:border-gray-700 text-gray-600 dark:text-gray-400 uppercase text-xs tracking-wide">
                    <th class="px-4 py-3 font-semibold">Fecha</th>
                    <th class="px-4 py-3 font-semibold">Entrada</th>
                    <th class="px-4 py-3 font-semibold">Salida</th>
                    <th class="px-4 py-3 font-semibold">Pausas</th>
                    <th class="px-4 py-3 font-semibold">Tiempo pausa</th>
                    <th class="px-4 py-3 font-semibold">Tiempo trabajado</th>
                </tr>
            </thead>

            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $asistencias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asistencia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b border-[#EAE4D8] dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">

                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                        <?php echo e(\Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y')); ?>

                    </td>

                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                        <?php echo e($asistencia->hora_entrada
                            ? \Carbon\Carbon::parse($asistencia->hora_entrada)->format('h:i:s A')
                            : '--'); ?>

                    </td>

                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                        <?php echo e($asistencia->hora_salida
                            ? \Carbon\Carbon::parse($asistencia->hora_salida)->format('h:i:s A')
                            : '--'); ?>

                    </td>

                    <td class="px-4 py-3">
                        <span class="inline-flex items-center justify-center rounded-md bg-sky-100 dark:bg-sky-900/20 text-sky-700 dark:text-sky-300 text-xs font-semibold px-2 py-1">
                            <?php echo e($asistencia->pausas->count()); ?>

                        </span>
                    </td>

                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                        <?php echo e($asistencia->tiempoPausas()); ?>

                    </td>

                    <td class="px-4 py-3 font-mono text-gray-800 dark:text-gray-200">
                        <?php echo e($asistencia->formatoTiempo($asistencia->tiempoTrabajado())); ?>

                    </td>

                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center py-6 text-gray-500 dark:text-gray-400">
                        No existen registros.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>

        </table>

    </div>

    
    <div class="block md:hidden p-3 space-y-3">

        <?php $__empty_1 = true; $__currentLoopData = $asistencias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asistencia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <div class="rounded-xl bg-white dark:bg-gray-800/50 border border-[#EAE4D8] dark:border-gray-700 overflow-hidden">

                <div class="flex justify-between items-center px-4 py-2.5 border-b border-[#EAE4D8] dark:border-gray-700 font-semibold text-gray-900 dark:text-white">
                    <span>
                        <ion-icon name="calendar-outline" class="text-blue-600 dark:text-blue-400"></ion-icon>
                        <?php echo e(\Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y')); ?>

                    </span>
                    <span class="text-sm font-semibold rounded-md bg-emerald-100 dark:bg-emerald-600/20 text-emerald-700 dark:text-emerald-300 px-2.5 py-1">
                        <?php echo e($asistencia->formatoTiempo($asistencia->tiempoTrabajado())); ?>

                    </span>
                </div>

                <div class="grid grid-cols-2">

                    <div class="px-4 py-2.5 border-r border-b border-[#EAE4D8] dark:border-gray-700">
                        <p class="m-0 text-xs text-gray-500 dark:text-slate-400">Entrada</p>
                        <p class="mt-0.5 mb-0 text-sm text-gray-800 dark:text-white">
                            <?php echo e($asistencia->hora_entrada
                                ? \Carbon\Carbon::parse($asistencia->hora_entrada)->format('h:i:s A')
                                : '--'); ?>

                        </p>
                    </div>

                    <div class="px-4 py-2.5 border-b border-[#EAE4D8] dark:border-gray-700">
                        <p class="m-0 text-xs text-gray-500 dark:text-slate-400">Salida</p>
                        <p class="mt-0.5 mb-0 text-sm text-gray-800 dark:text-white">
                            <?php echo e($asistencia->hora_salida
                                ? \Carbon\Carbon::parse($asistencia->hora_salida)->format('h:i:s A')
                                : '--'); ?>

                        </p>
                    </div>

                    <div class="px-4 py-2.5 col-span-2">
                        <p class="m-0 text-xs text-gray-500 dark:text-slate-400">Pausas</p>
                        <p class="mt-0.5 mb-0 text-sm text-gray-800 dark:text-white">
                            <?php echo e($asistencia->pausas->count()); ?>

                            <span class="text-xs text-gray-400 dark:text-slate-500">
                                (<?php echo e($asistencia->tiempoPausas()); ?>)
                            </span>
                        </p>
                    </div>

                </div>

            </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

            <p class="text-center text-gray-500 dark:text-gray-400 py-6 mb-0">
                No existen registros.
            </p>

        <?php endif; ?>

    </div>

</div><?php /**PATH C:\Users\tortu\Checador-Online\resources\views/admin/historial/components/tabla.blade.php ENDPATH**/ ?>