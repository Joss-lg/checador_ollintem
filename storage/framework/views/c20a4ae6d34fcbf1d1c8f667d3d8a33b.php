<?php if($paginator->hasPages()): ?>
<nav role="navigation" aria-label="Paginación" class="flex flex-col sm:flex-row items-center justify-between gap-3">

    
    <div>
        <p class="text-gray-600 dark:text-gray-400 text-sm mb-0">
            Mostrando
            <span class="font-semibold text-gray-900 dark:text-white"><?php echo e($paginator->firstItem()); ?></span>
            a
            <span class="font-semibold text-gray-900 dark:text-white"><?php echo e($paginator->lastItem()); ?></span>
            de
            <span class="font-semibold text-gray-900 dark:text-white"><?php echo e($paginator->total()); ?></span>
            resultados
        </p>
    </div>

    <ul class="flex items-center gap-1 mb-0 list-none p-0">

        
        <?php if($paginator->onFirstPage()): ?>
            <li>
                <span class="inline-flex items-center justify-center rounded-lg min-w-[1.9rem] px-2 py-1 text-[0.8rem] sm:min-w-[2.25rem] sm:px-3 sm:py-1.5 sm:text-sm bg-white dark:bg-transparent border border-gray-200 dark:border-gray-700 text-gray-400 dark:text-gray-600">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </span>
            </li>
        <?php else: ?>
            <li>
                <a href="<?php echo e($paginator->previousPageUrl()); ?>"
                   class="inline-flex items-center justify-center rounded-lg min-w-[1.9rem] px-2 py-1 text-[0.8rem] sm:min-w-[2.25rem] sm:px-3 sm:py-1.5 sm:text-sm bg-white dark:bg-gray-800 border border-[#EAE4D8] dark:border-gray-700 text-gray-700 dark:text-gray-300 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-gray-700 hover:border-blue-400 dark:hover:text-white hover:-translate-y-0.5">
                   <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </li>
        <?php endif; ?>

        
        <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            
            <?php if(is_string($element)): ?>
                <li>
                    <span class="inline-flex items-center justify-center rounded-lg min-w-[1.9rem] px-2 py-1 text-[0.8rem] sm:min-w-[2.25rem] sm:px-3 sm:py-1.5 sm:text-sm border-0 bg-transparent text-gray-500 dark:text-gray-500"><?php echo e($element); ?></span>
                </li>
            <?php endif; ?>

            
            <?php if(is_array($element)): ?>
                <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($page == $paginator->currentPage()): ?>
                        <li>
                            <span class="inline-flex items-center justify-center rounded-lg min-w-[1.9rem] px-2 py-1 text-[0.8rem] sm:min-w-[2.25rem] sm:px-3 sm:py-1.5 sm:text-sm font-semibold bg-blue-600 border border-blue-600 text-white shadow-sm"><?php echo e($page); ?></span>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="<?php echo e($url); ?>"
                               class="inline-flex items-center justify-center rounded-lg min-w-[1.9rem] px-2 py-1 text-[0.8rem] sm:min-w-[2.25rem] sm:px-3 sm:py-1.5 sm:text-sm bg-white dark:bg-gray-800 border border-[#EAE4D8] dark:border-gray-700 text-gray-700 dark:text-gray-300 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-gray-700 hover:border-blue-400 dark:hover:text-white hover:-translate-y-0.5"><?php echo e($page); ?></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if($paginator->hasMorePages()): ?>
            <li>
                <a href="<?php echo e($paginator->nextPageUrl()); ?>"
                   class="inline-flex items-center justify-center rounded-lg min-w-[1.9rem] px-2 py-1 text-[0.8rem] sm:min-w-[2.25rem] sm:px-3 sm:py-1.5 sm:text-sm bg-white dark:bg-gray-800 border border-[#EAE4D8] dark:border-gray-700 text-gray-700 dark:text-gray-300 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-gray-700 hover:border-blue-400 dark:hover:text-white hover:-translate-y-0.5">
                    <ion-icon name="chevron-forward-outline"></ion-icon>
                </a>
            </li>
        <?php else: ?>
            <li>
                <span class="inline-flex items-center justify-center rounded-lg min-w-[1.9rem] px-2 py-1 text-[0.8rem] sm:min-w-[2.25rem] sm:px-3 sm:py-1.5 sm:text-sm bg-white dark:bg-transparent border border-gray-200 dark:border-gray-700 text-gray-400 dark:text-gray-600">
                    <ion-icon name="chevron-forward-outline"></ion-icon>
                </span>
            </li>
        <?php endif; ?>

    </ul>
</nav>
<?php endif; ?><?php /**PATH C:\Users\tortu\Checador-Online\resources\views/vendor/pagination/ollin.blade.php ENDPATH**/ ?>