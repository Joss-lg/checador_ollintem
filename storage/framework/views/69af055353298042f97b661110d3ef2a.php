
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['presenter']));

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

foreach (array_filter((['presenter']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="flex flex-col gap-3.5">

    
    <form action="<?php echo e(route('entrada')); ?>" method="POST" class="m-0">
        <?php echo csrf_field(); ?>
        <?php if (isset($component)) { $__componentOriginal7935060871e8e33293f522286b943df3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7935060871e8e33293f522286b943df3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.becario.accion-boton','data' => ['color' => 'blue','icon' => 'enter-outline','titulo' => 'Registrar entrada','descripcion' => 'Inicia el registro de tu turno','disabled' => ! $presenter->puedeEntrada,'destacar' => $presenter->destacar('entrada'),'as' => 'submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('becario.accion-boton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => 'blue','icon' => 'enter-outline','titulo' => 'Registrar entrada','descripcion' => 'Inicia el registro de tu turno','disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(! $presenter->puedeEntrada),'destacar' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($presenter->destacar('entrada')),'as' => 'submit']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7935060871e8e33293f522286b943df3)): ?>
<?php $attributes = $__attributesOriginal7935060871e8e33293f522286b943df3; ?>
<?php unset($__attributesOriginal7935060871e8e33293f522286b943df3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7935060871e8e33293f522286b943df3)): ?>
<?php $component = $__componentOriginal7935060871e8e33293f522286b943df3; ?>
<?php unset($__componentOriginal7935060871e8e33293f522286b943df3); ?>
<?php endif; ?>
    </form>

    
    <div>
        <?php if (isset($component)) { $__componentOriginal7935060871e8e33293f522286b943df3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7935060871e8e33293f522286b943df3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.becario.accion-boton','data' => ['color' => 'amber','icon' => 'cafe-outline','iconRotate' => 'rotate-3','titulo' => 'Gestionar pausa','descripcion' => 'Justificación obligatoria','disabled' => ! $presenter->puedePausar,'destacar' => $presenter->destacar('pausar'),'as' => 'button','onclick' => 'togglePausaMenu()']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('becario.accion-boton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => 'amber','icon' => 'cafe-outline','iconRotate' => 'rotate-3','titulo' => 'Gestionar pausa','descripcion' => 'Justificación obligatoria','disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(! $presenter->puedePausar),'destacar' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($presenter->destacar('pausar')),'as' => 'button','onclick' => 'togglePausaMenu()']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7935060871e8e33293f522286b943df3)): ?>
<?php $attributes = $__attributesOriginal7935060871e8e33293f522286b943df3; ?>
<?php unset($__attributesOriginal7935060871e8e33293f522286b943df3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7935060871e8e33293f522286b943df3)): ?>
<?php $component = $__componentOriginal7935060871e8e33293f522286b943df3; ?>
<?php unset($__componentOriginal7935060871e8e33293f522286b943df3); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal1a056b54032585d2d0081b3f13343978 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1a056b54032585d2d0081b3f13343978 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.becario.pausa-menu','data' => ['puedePausar' => $presenter->puedePausar]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('becario.pausa-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['puede-pausar' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($presenter->puedePausar)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1a056b54032585d2d0081b3f13343978)): ?>
<?php $attributes = $__attributesOriginal1a056b54032585d2d0081b3f13343978; ?>
<?php unset($__attributesOriginal1a056b54032585d2d0081b3f13343978); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1a056b54032585d2d0081b3f13343978)): ?>
<?php $component = $__componentOriginal1a056b54032585d2d0081b3f13343978; ?>
<?php unset($__componentOriginal1a056b54032585d2d0081b3f13343978); ?>
<?php endif; ?>
    </div>

    
    <form id="formFinalizarPausa" action="<?php echo e(route('pausa.finalizar')); ?>" method="POST" class="m-0">
        <?php echo csrf_field(); ?>
        <?php if (isset($component)) { $__componentOriginal7935060871e8e33293f522286b943df3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7935060871e8e33293f522286b943df3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.becario.accion-boton','data' => ['color' => 'blue','icon' => 'play-outline','titulo' => 'Finalizar pausa','descripcion' => 'Reanuda tus actividades','disabled' => ! $presenter->puedeReanudar,'destacar' => $presenter->destacar('reanudar'),'as' => 'button','onclick' => 'openModal(\'modalFinalizarPausa\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('becario.accion-boton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => 'blue','icon' => 'play-outline','titulo' => 'Finalizar pausa','descripcion' => 'Reanuda tus actividades','disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(! $presenter->puedeReanudar),'destacar' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($presenter->destacar('reanudar')),'as' => 'button','onclick' => 'openModal(\'modalFinalizarPausa\')']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7935060871e8e33293f522286b943df3)): ?>
<?php $attributes = $__attributesOriginal7935060871e8e33293f522286b943df3; ?>
<?php unset($__attributesOriginal7935060871e8e33293f522286b943df3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7935060871e8e33293f522286b943df3)): ?>
<?php $component = $__componentOriginal7935060871e8e33293f522286b943df3; ?>
<?php unset($__componentOriginal7935060871e8e33293f522286b943df3); ?>
<?php endif; ?>
    </form>

    
    <form id="formSalida" action="<?php echo e(route('salida')); ?>" method="POST" class="m-0">
        <?php echo csrf_field(); ?>
        <?php if (isset($component)) { $__componentOriginal7935060871e8e33293f522286b943df3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7935060871e8e33293f522286b943df3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.becario.accion-boton','data' => ['color' => 'red','icon' => 'exit-outline','iconRotate' => 'rotate-3','titulo' => 'Registrar salida','descripcion' => 'Finaliza tu turno','disabled' => ! $presenter->puedeSalir,'destacar' => $presenter->destacar('salir'),'as' => 'button','onclick' => 'openModal(\'modalSalida\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('becario.accion-boton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => 'red','icon' => 'exit-outline','iconRotate' => 'rotate-3','titulo' => 'Registrar salida','descripcion' => 'Finaliza tu turno','disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(! $presenter->puedeSalir),'destacar' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($presenter->destacar('salir')),'as' => 'button','onclick' => 'openModal(\'modalSalida\')']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7935060871e8e33293f522286b943df3)): ?>
<?php $attributes = $__attributesOriginal7935060871e8e33293f522286b943df3; ?>
<?php unset($__attributesOriginal7935060871e8e33293f522286b943df3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7935060871e8e33293f522286b943df3)): ?>
<?php $component = $__componentOriginal7935060871e8e33293f522286b943df3; ?>
<?php unset($__componentOriginal7935060871e8e33293f522286b943df3); ?>
<?php endif; ?>
    </form>

    
    <div class="bg-slate-50 dark:bg-slate-800/50 border border-gray-200 dark:border-white/[0.06] rounded-2xl mt-auto p-4 flex gap-3 items-start transition-colors duration-300">
        <span class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/15 border border-blue-200 dark:border-blue-400/25 flex items-center justify-center shrink-0">
            <ion-icon name="information-circle-outline"></ion-icon>
        </span>
        <div>
            <p class="font-bold text-sm mb-0 text-gray-900 dark:text-white">Recuerda registrar tus pausas</p>
            <p class="text-gray-500 dark:text-slate-400 mb-0 text-[0.75rem]">Para llevar un control correcto de tu tiempo laboral.</p>
        </div>
    </div>

</div><?php /**PATH C:\Users\tortu\Checador-Online\resources\views/components/becario/panel-acciones.blade.php ENDPATH**/ ?>