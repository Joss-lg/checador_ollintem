

<?php $__env->startSection('content'); ?>

<div class="w-full px-3 sm:px-4">

    <?php if (isset($component)) { $__componentOriginal16d6074a1bfd9fa93b21b9d57b429d32 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16d6074a1bfd9fa93b21b9d57b429d32 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.history.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('history.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal16d6074a1bfd9fa93b21b9d57b429d32)): ?>
<?php $attributes = $__attributesOriginal16d6074a1bfd9fa93b21b9d57b429d32; ?>
<?php unset($__attributesOriginal16d6074a1bfd9fa93b21b9d57b429d32); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal16d6074a1bfd9fa93b21b9d57b429d32)): ?>
<?php $component = $__componentOriginal16d6074a1bfd9fa93b21b9d57b429d32; ?>
<?php unset($__componentOriginal16d6074a1bfd9fa93b21b9d57b429d32); ?>
<?php endif; ?>

    <div class="flex flex-wrap gap-3 mb-4">

        <a href="<?php echo e(route('admin.reportes.pdf.general', request()->query())); ?>"
           class="inline-flex flex-1 sm:flex-none items-center justify-center sm:justify-start gap-2 rounded-lg px-4 py-2 no-underline font-semibold text-white text-[0.85rem] sm:text-[0.9rem] border border-transparent transition-all duration-200
                 bg-gradient-to-b from-red-600 to-red-700 border-red-400/30 shadow-[0_3px_10px_rgba(153,27,27,0.4)]
                 hover:text-white hover:-translate-y-0.5 hover:shadow-[0_6px_16px_rgba(153,27,27,0.55)] hover:brightness-110
                 focus-visible:text-white focus-visible:-translate-y-0.5 focus-visible:shadow-[0_6px_16px_rgba(153,27,27,0.55)] focus-visible:brightness-110
                 active:translate-y-0 active:scale-[0.97]">
            <ion-icon name="document-text-outline"></ion-icon>
            Descargar PDF
        </a>

        <a href="<?php echo e(route('admin.reportes.general.excel', request()->query())); ?>"
           class="inline-flex flex-1 sm:flex-none items-center justify-center sm:justify-start gap-2 rounded-lg px-4 py-2 no-underline font-semibold text-white text-[0.85rem] sm:text-[0.9rem] border border-transparent transition-all duration-200
                 bg-gradient-to-b from-emerald-600 to-emerald-700 border-emerald-400/30 shadow-[0_3px_10px_rgba(6,95,70,0.4)]
                 hover:text-white hover:-translate-y-0.5 hover:shadow-[0_6px_16px_rgba(6,95,70,0.55)] hover:brightness-110
                 focus-visible:text-white focus-visible:-translate-y-0.5 focus-visible:shadow-[0_6px_16px_rgba(6,95,70,0.55)] focus-visible:brightness-110
                 active:translate-y-0 active:scale-[0.97]">
          <ion-icon name="document-text-outline"></ion-icon>
            Exportar Excel
        </a>

    </div>

    <?php if (isset($component)) { $__componentOriginal2f2d5b02bc526944c0b1fdd592227601 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f2d5b02bc526944c0b1fdd592227601 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.history.stats','data' => ['asistencias' => $asistencias]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('history.stats'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['asistencias' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($asistencias)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2f2d5b02bc526944c0b1fdd592227601)): ?>
<?php $attributes = $__attributesOriginal2f2d5b02bc526944c0b1fdd592227601; ?>
<?php unset($__attributesOriginal2f2d5b02bc526944c0b1fdd592227601); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2f2d5b02bc526944c0b1fdd592227601)): ?>
<?php $component = $__componentOriginal2f2d5b02bc526944c0b1fdd592227601; ?>
<?php unset($__componentOriginal2f2d5b02bc526944c0b1fdd592227601); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginalf691a1765ff09ae50fd7e36a97f6d1aa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf691a1765ff09ae50fd7e36a97f6d1aa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.history.search','data' => ['meses' => $meses]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('history.search'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['meses' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($meses)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf691a1765ff09ae50fd7e36a97f6d1aa)): ?>
<?php $attributes = $__attributesOriginalf691a1765ff09ae50fd7e36a97f6d1aa; ?>
<?php unset($__attributesOriginalf691a1765ff09ae50fd7e36a97f6d1aa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf691a1765ff09ae50fd7e36a97f6d1aa)): ?>
<?php $component = $__componentOriginalf691a1765ff09ae50fd7e36a97f6d1aa; ?>
<?php unset($__componentOriginalf691a1765ff09ae50fd7e36a97f6d1aa); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginalf6b17b51fc2b8006f6ec4a462f70b057 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf6b17b51fc2b8006f6ec4a462f70b057 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.history.table','data' => ['asistencias' => $asistencias]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('history.table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['asistencias' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($asistencias)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf6b17b51fc2b8006f6ec4a462f70b057)): ?>
<?php $attributes = $__attributesOriginalf6b17b51fc2b8006f6ec4a462f70b057; ?>
<?php unset($__attributesOriginalf6b17b51fc2b8006f6ec4a462f70b057); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf6b17b51fc2b8006f6ec4a462f70b057)): ?>
<?php $component = $__componentOriginalf6b17b51fc2b8006f6ec4a462f70b057; ?>
<?php unset($__componentOriginalf6b17b51fc2b8006f6ec4a462f70b057); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal87e71e07b1bf847a924d23ab500b2b2b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal87e71e07b1bf847a924d23ab500b2b2b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.history.pagination','data' => ['asistencias' => $asistencias]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('history.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['asistencias' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($asistencias)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal87e71e07b1bf847a924d23ab500b2b2b)): ?>
<?php $attributes = $__attributesOriginal87e71e07b1bf847a924d23ab500b2b2b; ?>
<?php unset($__attributesOriginal87e71e07b1bf847a924d23ab500b2b2b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal87e71e07b1bf847a924d23ab500b2b2b)): ?>
<?php $component = $__componentOriginal87e71e07b1bf847a924d23ab500b2b2b; ?>
<?php unset($__componentOriginal87e71e07b1bf847a924d23ab500b2b2b); ?>
<?php endif; ?>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\tortu\Checador-Online\resources\views/admin/historial/index.blade.php ENDPATH**/ ?>