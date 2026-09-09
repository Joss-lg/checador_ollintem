<tr class="border-t border-[#EAE4D8] dark:border-gray-700 hover:bg-[#F9F6EE] dark:hover:bg-gray-800 transition-colors">

    
    <td class="px-4 py-3">
        <strong class="text-gray-900 dark:text-white">
            <?php echo e($asistencia->user->name); ?>

        </strong>
        <br>
        <small class="text-gray-500 dark:text-gray-400">
            <?php echo e($asistencia->user->email); ?>

        </small>
    </td>

    
    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
        <?php echo e(\Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y')); ?>

    </td>

    
    <td class="hidden md:table-cell px-4 py-3 text-gray-700 dark:text-gray-300">
        <?php if($asistencia->hora_entrada): ?>
            <?php echo e(\Carbon\Carbon::parse($asistencia->hora_entrada)->format('h:i:s A')); ?>

        <?php else: ?>
            --
        <?php endif; ?>
    </td>

    
    <td class="hidden md:table-cell px-4 py-3 text-gray-700 dark:text-gray-300">
        <?php if($asistencia->hora_salida): ?>
            <?php echo e(\Carbon\Carbon::parse($asistencia->hora_salida)->format('h:i:s A')); ?>

        <?php else: ?>
            --
        <?php endif; ?>
    </td>

    
    <td class="hidden md:table-cell px-4 py-3">
        <span class="inline-flex items-center justify-center rounded-full bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-300 text-sm px-2.5 py-1 font-medium">
            <?php echo e($asistencia->pausas->count()); ?>

        </span>
    </td>

    
    <td class="hidden md:table-cell px-4 py-3 text-gray-700 dark:text-gray-300">
        <?php echo e($asistencia->tiempoPausas()); ?>

    </td>

    
    <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-200">
        <?php echo e($asistencia->formatoTiempo($asistencia->tiempoTrabajado())); ?>

    </td>

    
    <td class="hidden md:table-cell px-4 py-3">
        <?php if($asistencia->tiempoHorasExtras() > 0): ?>
            <span class="text-emerald-700 dark:text-emerald-400 font-bold">
                <?php echo e($asistencia->horasExtrasTotalFormato()); ?>

            </span>
        <?php else: ?>
            <span class="text-gray-400 dark:text-gray-600">
                00:00:00
            </span>
        <?php endif; ?>
    </td>

    
    <td class="px-4 py-3">
        <a href="<?php echo e(route('admin.historial.reporte', $asistencia->user->id)); ?>"
           class="inline-flex items-center gap-1 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 bg-white dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg px-3 py-1.5 text-sm transition-colors no-underline">
            <ion-icon name="document-outline" class="mr-1"></ion-icon>
            Reporte
        </a>
    </td>
</tr><?php /**PATH C:\Users\tortu\Checador-Online\resources\views/components/history/row.blade.php ENDPATH**/ ?>