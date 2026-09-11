<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['puedePausar']));

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

foreach (array_filter((['puedePausar']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div id="pausaMenu" class="max-h-0 opacity-0 overflow-hidden transition-all duration-300 ease-out">
    <form action="<?php echo e(route('becario.iniciarPausa')); ?>" method="POST"
          class="bg-white dark:bg-slate-800/50 border border-amber-500/30 rounded-2xl p-3 mt-2 transition-colors duration-300">
        <?php echo csrf_field(); ?>
        <label class="block text-sm text-amber-600 dark:text-amber-400 font-bold uppercase mb-2 text-[0.7rem]">
            Motivo de pausa
        </label>
        <select name="motivo" class="w-full bg-white dark:bg-[#0f1724] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white text-sm rounded-lg px-3 py-2 mb-3 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/25 focus:outline-none transition-colors">
            <option value="Almuerzo">Almuerzo</option>
            <option value="Personal">Personal</option>
        </select>
        <button type="submit"
                class="w-full bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold uppercase text-sm rounded-lg px-4 py-2.5 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                <?php if(!$puedePausar): echo 'disabled'; endif; ?>>
            Iniciar ahora
        </button>
    </form>
</div><?php /**PATH C:\Users\Usuario\checador_ollintem\resources\views/components/becario/pausa-menu.blade.php ENDPATH**/ ?>