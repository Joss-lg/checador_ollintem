<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['meses']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['meses']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<form method="GET" class="mb-4">

    <div class="bg-white dark:bg-gray-900 border border-[#EAE4D8] dark:border-gray-700 rounded-2xl shadow-sm">

        <div class="p-5">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-end">

                
                <div class="lg:col-span-4">
                    <label class="block text-gray-600 dark:text-gray-400 text-sm mb-1.5 font-medium">
                        Buscar becario
                    </label>
                    <input type="text" name="search"
                           class="w-full bg-white dark:bg-gray-800 border border-[#EAE4D8] dark:border-gray-700 text-gray-800 dark:text-gray-200 rounded-lg px-3 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900 focus:outline-none"
                           placeholder="Nombre o correo..."
                           value="<?php echo e(request('search')); ?>">
                </div>

                
                <div class="lg:col-span-2">
                    <label class="block text-gray-600 dark:text-gray-400 text-sm mb-1.5 font-medium">
                        Semana
                    </label>
                    <select name="semana"
                            class="w-full bg-white dark:bg-gray-800 border border-[#EAE4D8] dark:border-gray-700 text-gray-800 dark:text-gray-200 rounded-lg px-3 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900 focus:outline-none">
                        <option value="">Todas</option>
                        <option value="1" <?php if(request('semana')==1): echo 'selected'; endif; ?>>1 - 7</option>
                        <option value="2" <?php if(request('semana')==2): echo 'selected'; endif; ?>>8 - 14</option>
                        <option value="3" <?php if(request('semana')==3): echo 'selected'; endif; ?>>15 - 21</option>
                        <option value="4" <?php if(request('semana')==4): echo 'selected'; endif; ?>>22 - 28</option>
                        <option value="5" <?php if(request('semana')==5): echo 'selected'; endif; ?>>29 - Fin</option>
                    </select>
                </div>

                
                <div class="lg:col-span-2">
                    <label class="block text-gray-600 dark:text-gray-400 text-sm mb-1.5 font-medium">
                        Mes
                    </label>
                    <select name="mes"
                            class="w-full bg-white dark:bg-gray-800 border border-[#EAE4D8] dark:border-gray-700 text-gray-800 dark:text-gray-200 rounded-lg px-3 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900 focus:outline-none">
                        <option value="">Todos</option>
                        <?php $__currentLoopData = $meses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($mes->numero_mes); ?>" <?php if(request('mes')==$mes->numero_mes): echo 'selected'; endif; ?>>
                                <?php echo e(\Carbon\Carbon::create()->month($mes->numero_mes)->translatedFormat('F')); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="lg:col-span-2">
                    <label class="block text-gray-600 dark:text-gray-400 text-sm mb-1.5 font-medium">
                        Orden
                    </label>
                    <select name="order"
                            class="w-full bg-white dark:bg-gray-800 border border-[#EAE4D8] dark:border-gray-700 text-gray-800 dark:text-gray-200 rounded-lg px-3 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900 focus:outline-none">
                        <option value="az" <?php if(request('order')=='az'): echo 'selected'; endif; ?>>A-Z</option>
                        <option value="za" <?php if(request('order')=='za'): echo 'selected'; endif; ?>>Z-A</option>
                        <option value="recent" <?php if(request('order')=='recent'): echo 'selected'; endif; ?>>Más reciente</option>
                        <option value="oldest" <?php if(request('order')=='oldest'): echo 'selected'; endif; ?>>Más antiguo</option>
                    </select>
                </div>

                
                <div class="lg:col-span-2">
                    <div class="grid gap-2">
                        <button class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-2.5 transition-colors shadow-sm">
                           <ion-icon name="search-outline"></ion-icon> Buscar
                        </button>
                        <a href="<?php echo e(route('admin.historial')); ?>"
                           class="inline-flex items-center justify-center gap-2 border border-[#EAE4D8] dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg px-4 py-2.5 transition-colors no-underline">
                            <ion-icon name="refresh-outline"></ion-icon> Limpiar
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form><?php /**PATH C:\Users\tortu\Checador-Online\resources\views/components/history/search.blade.php ENDPATH**/ ?>