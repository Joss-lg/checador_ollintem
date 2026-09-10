<div class="rounded-2xl bg-white dark:bg-gray-800/50 border border-[#EAE4D8] dark:border-gray-700 p-4 mb-6">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">

        
        <div class="lg:col-span-3">
            <a
                href="<?php echo e(route('admin.historial')); ?>"
                class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 dark:border-slate-500 text-gray-600 dark:text-slate-300 px-4 py-2 text-sm font-medium hover:bg-gray-100 dark:hover:bg-white/5 hover:border-gray-400 transition-colors"
            >
                <ion-icon name="arrow-back-outline"></ion-icon>
                Regresar al historial
            </a>
        </div>

        
        <div class="lg:col-span-5">
            <form class="grid grid-cols-1 sm:grid-cols-2 gap-2">

                <input
                    type="date"
                    class="w-full rounded-lg bg-white dark:bg-slate-950 border border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >

                <input
                    type="date"
                    class="w-full rounded-lg bg-white dark:bg-slate-950 border border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >

            </form>
        </div>

        
        <div class="lg:col-span-4">
            <div class="flex flex-col sm:flex-row gap-2 sm:justify-end">

                <a href="<?php echo e(route('admin.reportes.excel', $user)); ?>"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 transition-colors">
                   <ion-icon name="document-text-outline"></ion-icon>
                    Exportar Excel
                </a>

                <a href="<?php echo e(route('admin.reportes.pdf.becario', $user)); ?>"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 transition-colors">
                    <ion-icon name="document-text-outline"></ion-icon>
                    Exportar PDF
                </a>

            </div>
        </div>

    </div>

</div><?php /**PATH C:\Users\tortu\Checador-Online\resources\views/admin/historial/components/acciones.blade.php ENDPATH**/ ?>