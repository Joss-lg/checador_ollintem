
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['estadoInfo', 'estadoBanner', 'inicial']));

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

foreach (array_filter((['estadoInfo', 'estadoBanner', 'inicial']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="entrada bg-white dark:bg-slate-900/85 backdrop-blur-[15px] border border-[#EAE4D8] dark:border-white/[0.08] rounded-3xl flex justify-between items-center px-4 sm:px-5 py-3.5 mb-5 shadow-sm dark:shadow-lg transition-colors duration-300">
    <div class="flex items-center gap-3">
        <img src="<?php echo e(asset('images/isotipo.webp')); ?>" alt="Logo OllinCheck" class="w-6 h-6">
        <div>
            <h1 class="text-lg font-bold uppercase mb-0 tracking-[2px] leading-none text-gray-900 dark:text-white">
                OLLIN<span class="text-blue-600 dark:text-blue-500">CHECK</span>
            </h1>
            <p class="text-[0.7rem] text-gray-500 dark:text-slate-400 mt-0.5 flex items-center gap-1.5">
                <span class="relative flex h-1.5 w-1.5">
                    <span class="<?php echo e($estadoBanner['pulse'] ? 'animate-ping' : ''); ?> absolute inline-flex h-full w-full rounded-full <?php echo e($estadoBanner['dot']); ?> opacity-60"></span>
                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 <?php echo e($estadoBanner['dot']); ?>"></span>
                </span>
                <?php echo e($estadoInfo['label']); ?>

            </p>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <span class="hidden sm:flex items-center gap-2.5 text-sm font-semibold text-gray-900 dark:text-white">
            <span class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-500/15 border border-blue-200 dark:border-blue-400/30 text-blue-700 dark:text-blue-300 text-xs font-bold flex items-center justify-center">
                <?php echo e($inicial); ?>

            </span>
            <?php echo e(auth()->check() ? auth()->user()->name : 'Usuario'); ?>

        </span>
        
        <form action="<?php echo e(route('logout')); ?>" method="POST" class="mb-0">
            <?php echo csrf_field(); ?>
            <button type="submit" class="inline-flex items-center gap-1.5 text-sm border border-red-500/60 text-red-600 dark:text-red-400 hover:bg-red-500 hover:text-white hover:border-red-500 rounded-full px-3.5 py-1.5 transition-colors duration-200">
                <ion-icon name="power-outline"></ion-icon> Salir
            </button>
        </form>
    </div>
</div><?php /**PATH C:\Users\Usuario\checador_ollintem\resources\views/components/becario/header.blade.php ENDPATH**/ ?>