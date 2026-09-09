
<?php $__env->startSection('content'); ?>
<script>
    window.ROL_ACTUAL = "<?php echo e(auth()->user()->role); ?>";
</script>
<div class="w-full px-3 md:px-4">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center text-center sm:text-left mb-4 gap-2">
        <h2 class="text-gray-900 dark:text-white font-bold mb-0 text-lg sm:text-xl lg:text-3xl transition-colors">
           <ion-icon name="time-outline" class="text-blue-600 dark:text-cyan-400 mr-2"></ion-icon>Panel de Control: Asistencias
        </h2>
    </div>

    <div class="grid grid-cols-3 gap-3 mb-4">

        <div class="bg-white dark:bg-gray-900 border border-[#EAE4D8] dark:border-gray-700 shadow-lg rounded-2xl h-full transition-colors">
            <div class="px-2 sm:px-3 py-2 sm:py-3 text-center">
                <div class="text-gray-500 dark:text-gray-400 text-[0.68rem] sm:text-sm">Becarios Activos</div>
                <h2 id="card-activos" class="text-green-600 dark:text-green-500 font-bold mb-0 text-lg sm:text-2xl">0</h2>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-[#EAE4D8] dark:border-gray-700 shadow-lg rounded-2xl h-full transition-colors">
            <div class="px-2 sm:px-3 py-2 sm:py-3 text-center">
                <div class="text-gray-500 dark:text-gray-400 text-[0.68rem] sm:text-sm">En Descanso</div>
                <h2 id="card-descanso" class="text-blue-600 dark:text-cyan-400 font-bold mb-0 text-lg sm:text-2xl">0</h2>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-[#EAE4D8] dark:border-gray-700 shadow-lg rounded-2xl h-full transition-colors">
            <div class="px-2 sm:px-3 py-2 sm:py-3 text-center">
                <div class="text-gray-500 dark:text-gray-400 text-[0.68rem] sm:text-sm">Turnos Finalizados</div>
                <h2 id="card-finalizados" class="text-yellow-600 dark:text-yellow-400 font-bold mb-0 text-lg sm:text-2xl">0</h2>
            </div>
        </div>

        <div class="col-span-3">
            <div class="bg-white dark:bg-gray-900 border border-[#EAE4D8] dark:border-gray-700 shadow-xl rounded-2xl transition-colors">
                <div class="p-0 overflow-hidden">

                    
                    <div class="overflow-x-auto hidden md:block">
                        <table class="w-full text-gray-800 dark:text-white mb-0 align-middle text-center transition-colors" style="min-width: 800px;">
                            <thead>
                                <tr class="text-gray-600 dark:text-gray-400 bg-[#F4F0E6] dark:bg-white/[0.03] border-b border-[#EAE4D8] dark:border-gray-700 transition-colors">
                                    <th class="py-3 font-semibold uppercase text-xs text-center">Becario</th>
                                    <th class="py-3 font-semibold uppercase text-xs text-center">Fecha</th>
                                    <th class="py-3 font-semibold uppercase text-xs text-center">Entrada</th>
                                    <th class="py-3 font-semibold uppercase text-xs text-center">Salida</th>
                                    <th class="py-3 font-semibold uppercase text-xs text-center">Pausas</th>
                                    <th class="py-3 font-semibold uppercase text-xs text-center">Tiempo Total</th>
                                    <th class="py-3 font-semibold uppercase text-xs text-center">Horas Extras</th>
                                    <th class="py-3 font-semibold uppercase text-xs text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-asistencias">
                                <tr id="tabla-vacia" class="hover:bg-[#F9F6EE] dark:hover:bg-white/5 transition-colors">
                                    <td colspan="8" class="text-center text-gray-500 dark:text-gray-400 py-10">
                                        <ion-icon name="time-outline" class="text-2xl block mb-2"></ion-icon>
                                        No existen asistencias activas actualmente
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    
                    <div id="tarjetas-asistencias" class="block md:hidden p-3">
                        <p id="tarjetas-vacio" class="text-center text-gray-500 dark:text-gray-400 py-4 mb-0 transition-colors">
                            <ion-icon name="time-outline" class="text-2xl block mb-2"></ion-icon>
                            No existen asistencias activas actualmente
                        </p>
                    </div>

                    <div class="hidden md:block lg:hidden text-center text-gray-500 dark:text-gray-400 text-xs py-2 border-t border-[#EAE4D8] dark:border-gray-700 bg-[#F4F0E6] dark:bg-white/[0.02] transition-colors">
                        <i class="bi bi-arrow-left-right mr-1"></i>Desliza para ver toda la tabla
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>


<script>
    window.RUTAS = {
        tiempos: "<?php echo e(route('admin.tiempos')); ?>"
    };
</script>
<script src="<?php echo e(asset('js/admin/dashboard.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\tortu\Checador-Online\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>