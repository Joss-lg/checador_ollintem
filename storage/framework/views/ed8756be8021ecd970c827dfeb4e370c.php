<div class="bg-white dark:bg-gray-900 border border-[#EAE4D8] dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
    <div class="flex justify-between items-center px-5 py-4 border-b border-[#EAE4D8] dark:border-gray-700">
        <h5 class="mb-0 text-gray-900 dark:text-white font-bold text-lg">
            <ion-icon name="grid-outline"class="mr-2 text-blue-500"></ion-icon> Registros
        </h5>
        <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 text-sm px-3 py-1 font-medium">
            <?php echo e($asistencias->total()); ?> registros
        </span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-gray-800 dark:text-gray-200 align-middle mb-0">
            <thead class="bg-gray-50 dark:bg-gray-800/50">
                <tr class="text-gray-500 dark:text-gray-400 border-b border-[#EAE4D8] dark:border-gray-700">
                    <th class="px-4 py-3 text-left text-xs uppercase font-bold">Becario</th>
                    <th class="px-4 py-3 text-left text-xs uppercase font-bold">Fecha</th>
                    <th class="hidden md:table-cell px-4 py-3 text-left text-xs uppercase font-bold">Entrada</th>
                    <th class="hidden md:table-cell px-4 py-3 text-left text-xs uppercase font-bold">Salida</th>
                    <th class="hidden md:table-cell px-4 py-3 text-left text-xs uppercase font-bold">Pausas</th>
                    <th class="hidden md:table-cell px-4 py-3 text-left text-xs uppercase font-bold">Tiempo pausa</th>
                    <th class="px-4 py-3 text-left text-xs uppercase font-bold">Tiempo trabajado</th>
                    <th class="hidden md:table-cell px-4 py-3 text-left text-xs uppercase font-bold">Horas extra</th>
                    <th class="px-4 py-3 text-left text-xs uppercase font-bold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#EAE4D8] dark:divide-gray-700">
                <?php $__empty_1 = true; $__currentLoopData = $asistencias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asistencia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php if (isset($component)) { $__componentOriginal160392786d2d97d542a5bf7514086146 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal160392786d2d97d542a5bf7514086146 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.history.row','data' => ['asistencia' => $asistencia]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('history.row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['asistencia' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($asistencia)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal160392786d2d97d542a5bf7514086146)): ?>
<?php $attributes = $__attributesOriginal160392786d2d97d542a5bf7514086146; ?>
<?php unset($__attributesOriginal160392786d2d97d542a5bf7514086146); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal160392786d2d97d542a5bf7514086146)): ?>
<?php $component = $__componentOriginal160392786d2d97d542a5bf7514086146; ?>
<?php unset($__componentOriginal160392786d2d97d542a5bf7514086146); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="text-center py-10 text-gray-500 dark:text-gray-400">
                            <ion-icon name="folder-outline"class="text-4xl"></ion-icon>
                            <p class="mt-2">No existen registros.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php echo $__env->make('components.history.modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div><?php /**PATH C:\Users\tortu\Checador-Online\resources\views/components/history/table.blade.php ENDPATH**/ ?>