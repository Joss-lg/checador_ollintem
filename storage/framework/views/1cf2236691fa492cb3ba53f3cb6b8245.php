<?php $__env->startSection('content'); ?>

<script>
    window.ROL_ACTUAL = "<?php echo e(auth()->user()->role); ?>";
</script>

<?php
    $presenter = $presenter ?? new \App\Support\EstadoTurnoPresenter($estado ?? null);
    $inicial = auth()->check() ? mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) : 'U';
?>

<?php echo app('Illuminate\Foundation\Vite')(['resources/css/becario-dashboard.css']); ?>




<div class="relative min-h-screen text-gray-900 dark:text-white overflow-hidden transition-colors duration-300">
    
    
    <div class="pointer-events-none absolute inset-0 -z-0 overflow-hidden hidden dark:block">
        <div class="absolute -top-24 -left-24 w-[420px] h-[420px] rounded-full bg-blue-600/10 blur-[110px]"></div>
        <div class="absolute top-1/3 -right-32 w-[420px] h-[420px] rounded-full bg-amber-500/10 blur-[120px]"></div>
        <div class="absolute bottom-0 left-1/4 w-[360px] h-[360px] rounded-full bg-red-600/5 blur-[110px]"></div>
    </div>

    <div class="relative max-w-[1320px] mx-auto py-5 px-3 md:px-4">

        <?php if (isset($component)) { $__componentOriginalc87fdb70793f695d236602e5a5714b0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc87fdb70793f695d236602e5a5714b0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.becario.header','data' => ['estadoInfo' => $presenter->info,'estadoBanner' => $presenter->banner,'inicial' => $inicial]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('becario.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['estado-info' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($presenter->info),'estado-banner' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($presenter->banner),'inicial' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($inicial)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc87fdb70793f695d236602e5a5714b0a)): ?>
<?php $attributes = $__attributesOriginalc87fdb70793f695d236602e5a5714b0a; ?>
<?php unset($__attributesOriginalc87fdb70793f695d236602e5a5714b0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc87fdb70793f695d236602e5a5714b0a)): ?>
<?php $component = $__componentOriginalc87fdb70793f695d236602e5a5714b0a; ?>
<?php unset($__componentOriginalc87fdb70793f695d236602e5a5714b0a); ?>
<?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            <div class="lg:col-span-7">
                <?php if (isset($component)) { $__componentOriginalc71cd63e0b8b9ddf434728b46bf9e870 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc71cd63e0b8b9ddf434728b46bf9e870 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.becario.panel-reloj','data' => ['estadoInfo' => $presenter->info,'estadoBanner' => $presenter->banner]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('becario.panel-reloj'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['estado-info' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($presenter->info),'estado-banner' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($presenter->banner)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc71cd63e0b8b9ddf434728b46bf9e870)): ?>
<?php $attributes = $__attributesOriginalc71cd63e0b8b9ddf434728b46bf9e870; ?>
<?php unset($__attributesOriginalc71cd63e0b8b9ddf434728b46bf9e870); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc71cd63e0b8b9ddf434728b46bf9e870)): ?>
<?php $component = $__componentOriginalc71cd63e0b8b9ddf434728b46bf9e870; ?>
<?php unset($__componentOriginalc71cd63e0b8b9ddf434728b46bf9e870); ?>
<?php endif; ?>
            </div>
            
            <div class="lg:col-span-5">
                
                <div class="entrada bg-white dark:bg-slate-900/85 backdrop-blur-[15px] border border-[#EAE4D8] dark:border-white/[0.08] rounded-3xl p-5 sm:p-6 h-full flex flex-col shadow-sm dark:shadow-lg transition-all" style="animation-delay:.16s">
                    
                    <div class="flex items-center justify-between mb-4">
                        <p class="uppercase text-gray-500 dark:text-slate-500 font-bold tracking-[2px] text-[0.7rem] mb-0">Acciones</p>
                        <span class="h-px flex-1 ml-3 bg-gradient-to-r from-gray-200 dark:from-white/10 to-transparent"></span>
                    </div>

                    <?php if (isset($component)) { $__componentOriginal215e3f2eb7fbd0bcec568075f84f8d6b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal215e3f2eb7fbd0bcec568075f84f8d6b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.becario.panel-acciones','data' => ['presenter' => $presenter]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('becario.panel-acciones'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['presenter' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($presenter)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal215e3f2eb7fbd0bcec568075f84f8d6b)): ?>
<?php $attributes = $__attributesOriginal215e3f2eb7fbd0bcec568075f84f8d6b; ?>
<?php unset($__attributesOriginal215e3f2eb7fbd0bcec568075f84f8d6b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal215e3f2eb7fbd0bcec568075f84f8d6b)): ?>
<?php $component = $__componentOriginal215e3f2eb7fbd0bcec568075f84f8d6b; ?>
<?php unset($__componentOriginal215e3f2eb7fbd0bcec568075f84f8d6b); ?>
<?php endif; ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $__env->make('becario.modals.confirmar_descanso', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('becario.modals.finalizar_turno', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
    window.checadorConfig = {
        estado: <?php echo json_encode($presenter->estado, 15, 512) ?>,
        horaEntrada: <?php echo json_encode($horaEntrada ?? null, 15, 512) ?>,
        pausaInicio: <?php echo json_encode($pausaInicio ?? null, 15, 512) ?>,
        horaSalida: <?php echo json_encode($horaSalida ?? null, 15, 512) ?>,
        segundosPausaAcumulados: <?php echo e((int) ($segundosPausaAcumulados ?? 0)); ?>

    };
</script>
<?php echo app('Illuminate\Foundation\Vite')('resources/js/becario-dashboard.js'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\checador_ollintem\resources\views/becario/dashboard.blade.php ENDPATH**/ ?>