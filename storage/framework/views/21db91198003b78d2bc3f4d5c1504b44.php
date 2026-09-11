<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'color'         => 'blue',   // blue | amber | red
    'icon'          => 'log-in-outline',
    'iconRotate'    => '-rotate-3',
    'titulo'        => '',
    'descripcion'   => '',
    'disabled'      => false,
    'destacar'      => false,
    'as'            => 'submit', // 'submit' | 'button'
    'onclick'       => null,
]));

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

foreach (array_filter(([
    'color'         => 'blue',   // blue | amber | red
    'icon'          => 'log-in-outline',
    'iconRotate'    => '-rotate-3',
    'titulo'        => '',
    'descripcion'   => '',
    'disabled'      => false,
    'destacar'      => false,
    'as'            => 'submit', // 'submit' | 'button'
    'onclick'       => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $paletas = [
        'blue' => [
            'border'       => 'border-gray-200 dark:border-blue-500/40',
            'hoverBorder'  => 'hover:border-gray-300 dark:hover:border-blue-400/70',
            'hoverShadow'  => 'hover:shadow-lg dark:hover:shadow-[0_18px_35px_-15px_rgba(37,99,235,0.55)]',
            'bar'          => 'bg-blue-500',
            'glow'         => 'bg-blue-500/10',
            'iconBg'       => 'bg-gradient-to-br from-blue-500 to-blue-700',
            'iconShadow'   => 'shadow-[0_8px_20px_-4px_rgba(37,99,235,0.4)]',
            'texto'        => 'text-blue-800 dark:text-blue-300',
            'badgeTexto'   => 'text-blue-700 dark:text-blue-300',
            'badgeBg'      => 'bg-blue-100 dark:bg-blue-500/10',
            'badgeBorder'  => 'border-blue-200 dark:border-blue-400/30',
            'dot'          => 'bg-blue-500 dark:bg-blue-400',
            'destacarRing' => 'ring-1 ring-blue-500/20 dark:ring-blue-400/40 shadow-[0_0_20px_-5px_rgba(59,130,246,0.3)]',
        ],
        'amber' => [
            'border'       => 'border-gray-200 dark:border-amber-500/40',
            'hoverBorder'  => 'hover:border-gray-300 dark:hover:border-amber-400/70',
            'hoverShadow'  => 'hover:shadow-lg dark:hover:shadow-[0_18px_35px_-15px_rgba(217,119,6,0.55)]',
            'bar'          => 'bg-amber-500',
            'glow'         => 'bg-amber-500/10',
            'iconBg'       => 'bg-gradient-to-br from-amber-500 to-amber-700',
            'iconShadow'   => 'shadow-[0_8px_20px_-4px_rgba(217,119,6,0.4)]',
            'texto'        => 'text-amber-800 dark:text-amber-300',
            'badgeTexto'   => 'text-amber-700 dark:text-amber-300',
            'badgeBg'      => 'bg-amber-100 dark:bg-amber-500/10',
            'badgeBorder'  => 'border-amber-200 dark:border-amber-400/30',
            'dot'          => 'bg-amber-500 dark:bg-amber-400',
            'destacarRing' => 'ring-1 ring-amber-500/20 dark:ring-amber-400/40 shadow-[0_0_20px_-5px_rgba(245,158,11,0.3)]',
        ],
        'red' => [
            'border'       => 'border-gray-200 dark:border-red-500/40',
            'hoverBorder'  => 'hover:border-gray-300 dark:hover:border-red-400/70',
            'hoverShadow'  => 'hover:shadow-lg dark:hover:shadow-[0_18px_35px_-15px_rgba(220,38,38,0.55)]',
            'bar'          => 'bg-red-500',
            'glow'         => 'bg-red-500/10',
            'iconBg'       => 'bg-gradient-to-br from-red-500 to-red-700',
            'iconShadow'   => 'shadow-[0_8px_20px_-4px_rgba(185,28,28,0.4)]',
            'texto'        => 'text-red-800 dark:text-red-300',
            'badgeTexto'   => 'text-red-700 dark:text-red-300',
            'badgeBg'      => 'bg-red-100 dark:bg-red-500/10',
            'badgeBorder'  => 'border-red-200 dark:border-red-400/30',
            'dot'          => 'bg-red-500 dark:bg-red-400',
            'destacarRing' => 'ring-1 ring-red-500/20 dark:ring-red-400/40 shadow-[0_0_20px_-5px_rgba(239,68,68,0.3)]',
        ],
    ];

    $p = $paletas[$color] ?? $paletas['blue'];

    $baseClasses = "group relative isolate w-full text-left overflow-hidden rounded-2xl border p-4 sm:p-5 flex items-center gap-4
                    bg-white dark:bg-slate-900/60 {$p['border']}
                    transition-all duration-300 ease-out
                    hover:-translate-y-1 {$p['hoverBorder']} {$p['hoverShadow']}
                    active:translate-y-0 active:scale-[0.98]
                    disabled:opacity-30 disabled:cursor-not-allowed disabled:pointer-events-none disabled:hover:translate-y-0 disabled:hover:shadow-none
                    " . ($destacar ? $p['destacarRing'] : '');
?>

<button
    type="<?php echo e($as === 'submit' ? 'submit' : 'button'); ?>"
    <?php if($onclick): ?> onclick="<?php echo e($onclick); ?>" <?php endif; ?>
    <?php if($disabled): echo 'disabled'; endif; ?>
    <?php echo e($attributes->merge(['class' => $baseClasses])); ?>

>
    <span class="absolute left-0 top-0 h-full w-1 <?php echo e($p['bar']); ?> origin-center scale-y-0 group-hover:scale-y-100 transition-transform duration-300 rounded-r"></span>
    <span class="pointer-events-none absolute -right-8 -top-8 w-28 h-28 rounded-full <?php echo e($p['glow']); ?> blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 hidden dark:block"></span>

    <?php if($destacar): ?>
        <span class="absolute top-3 right-3 flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider <?php echo e($p['badgeTexto']); ?> <?php echo e($p['badgeBg']); ?> border <?php echo e($p['badgeBorder']); ?> rounded-full px-2 py-0.5">
            <span class="w-1.5 h-1.5 rounded-full <?php echo e($p['dot']); ?> animate-pulse"></span>
            Disponible
        </span>
    <?php endif; ?>

    <span class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex-shrink-0 flex items-center justify-center text-xl text-white
               <?php echo e($p['iconBg']); ?> <?php echo e($p['iconShadow']); ?>

               transition-transform duration-300 group-hover:scale-110 group-hover:<?php echo e($iconRotate); ?>">
        <ion-icon name="<?php echo e($icon); ?>"></ion-icon>
    </span>

    <span class="relative flex-1 min-w-0">
        <span class="block font-bold text-base sm:text-lg <?php echo e($p['texto']); ?> tracking-tight"><?php echo e($titulo); ?></span>
        <span class="block text-sm text-gray-500 dark:text-slate-400 mt-0.5"><?php echo e($descripcion); ?></span>
    </span>

    <ion-icon name="chevron-forward-outline" class="relative text-gray-300 dark:text-white/0 group-hover:text-gray-500 dark:group-hover:text-white/50 transition-all duration-300 group-hover:translate-x-1"></ion-icon>
</button><?php /**PATH C:\Users\Usuario\checador_ollintem\resources\views/components/becario/accion-boton.blade.php ENDPATH**/ ?>