<div class="grid grid-cols-2 md:grid-cols-12 gap-4 mb-6">

    
    <div class="col-span-2 md:col-span-6 lg:col-span-4">
        <div class="rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-[#EAE4D8] dark:border-gray-700 h-full p-4">
            <h4 class="font-bold text-lg mb-1 text-gray-900 dark:text-white">
                <?php echo e($user->name); ?>

            </h4>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-0">
                <?php echo e($user->email); ?>

            </p>
        </div>
    </div>

    
    <div class="col-span-1 md:col-span-2 lg:col-span-3">
        <div class="rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-[#EAE4D8] dark:border-gray-700 h-full p-4 text-center">
            <h3 class="font-bold text-2xl mb-1 text-gray-900 dark:text-white">
                <?php echo e($resumen['jornadas']); ?>

            </h3>
            <small class="text-gray-500 dark:text-gray-400 text-xs uppercase font-semibold">
                Jornadas
            </small>
        </div>
    </div>

    
    <div class="col-span-1 md:col-span-2 lg:col-span-3">
        <div class="rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-[#EAE4D8] dark:border-gray-700 h-full p-4 text-center">
            <h5 class="font-bold text-lg text-blue-600 dark:text-blue-400 mb-1">
                <?php echo e($resumen['horas_trabajadas']); ?>

            </h5>
            <small class="text-gray-500 dark:text-gray-400 text-xs uppercase font-semibold">
                Trabajadas
            </small>
        </div>
    </div>

    
    <div class="col-span-1 md:col-span-2 lg:col-span-2">
        <div class="rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-[#EAE4D8] dark:border-gray-700 h-full p-4 text-center">
            <h5 class="font-bold text-lg text-amber-600 dark:text-amber-400 mb-1">
                <?php echo e($resumen['tiempo_pausa']); ?>

            </h5>
            <small class="text-gray-500 dark:text-gray-400 text-xs uppercase font-semibold">
                Pausas
            </small>
        </div>
    </div>

</div><?php /**PATH C:\Users\tortu\Checador-Online\resources\views/admin/historial/components/resumen.blade.php ENDPATH**/ ?>