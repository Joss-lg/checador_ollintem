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

<div class="mt-4 p-4 bg-white dark:bg-gray-900 border border-[#EAE4D8] dark:border-gray-700 rounded-2xl shadow-sm">
    <div class="flex items-center justify-between">
        <div class="text-sm text-gray-600 dark:text-gray-400">
            Mostrando <span class="font-semibold text-gray-900 dark:text-white"><?php echo e($asistencias->firstItem() ?? 0); ?></span> 
            a <span class="font-semibold text-gray-900 dark:text-white"><?php echo e($asistencias->lastItem() ?? 0); ?></span> 
            de <span class="font-semibold text-gray-900 dark:text-white"><?php echo e($asistencias->total()); ?></span> resultados
        </div>
        
        <div>
            
            <?php echo e($asistencias->links('vendor.pagination.ollin')); ?>

        </div>
    </div>
</div><?php /**PATH C:\Users\tortu\Checador-Online\resources\views/components/history/pagination.blade.php ENDPATH**/ ?>