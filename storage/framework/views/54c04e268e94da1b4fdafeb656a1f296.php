<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['asistencias']));

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

foreach (array_filter((['asistencias']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">

    <?php
        $stats = [
            ['label' => 'Registros', 'value' => $asistencias->total(), 'color' => 'text-gray-900 dark:text-white'],
            ['label' => 'Becarios', 'value' => $asistencias->pluck('user_id')->unique()->count(), 'color' => 'text-blue-600 dark:text-blue-400'],
            ['label' => 'Finalizados', 'value' => $asistencias->whereNotNull('hora_salida')->count(), 'color' => 'text-emerald-600 dark:text-emerald-400'],
            ['label' => 'En curso', 'value' => $asistencias->whereNull('hora_salida')->count(), 'color' => 'text-amber-600 dark:text-amber-400'],
        ];
    ?>

    <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="bg-white dark:bg-gray-900 border border-[#EAE4D8] dark:border-gray-700 rounded-xl shadow-sm h-full">
        <div class="p-5">
            <small class="text-gray-500 dark:text-gray-400 uppercase font-semibold text-[0.75rem]">
                <?php echo e($stat['label']); ?>

            </small>
            <h3 class="font-bold <?php echo e($stat['color']); ?> mt-2 text-xl">
                <?php echo e($stat['value']); ?>

            </h3>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div><?php /**PATH C:\Users\tortu\Checador-Online\resources\views/components/history/stats.blade.php ENDPATH**/ ?>