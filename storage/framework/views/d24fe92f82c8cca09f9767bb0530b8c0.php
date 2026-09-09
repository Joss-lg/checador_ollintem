<?php $__env->startSection('content'); ?>
<div class="w-full px-3 sm:px-4">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2">
        <h2 class="text-gray-900 dark:text-white font-bold mb-0 text-xl sm:text-3xl">
            <ion-icon name="people" class="text-blue-600 dark:text-blue-400 mr-2"></ion-icon>Administración de Usuarios
        </h2>
        
        <?php echo $__env->make('admin.modals.registrar-becario', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <div class="bg-white dark:bg-[#1b1e24] border border-[#EAE4D8] dark:border-white/10 shadow-xl rounded-2xl">
        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="w-full text-gray-800 dark:text-gray-200 mb-0 align-middle">
                    <thead>
                        <tr class="text-gray-600 dark:text-gray-400 bg-[#F4F0E6] dark:bg-white/5 border-b border-[#EAE4D8] dark:border-white/10">
                            <th class="pl-3 sm:pl-4 py-3 font-semibold uppercase text-[0.8rem] sm:text-xs">Nombre</th>
                            <th class="py-3 font-semibold uppercase text-[0.8rem] sm:text-xs">Email</th>
                            <th class="py-3 font-semibold uppercase text-[0.8rem] sm:text-xs">Rol</th>
                            <th class="text-right pr-3 sm:pr-4 py-3 font-semibold uppercase text-[0.8rem] sm:text-xs">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-[#F9F6EE] dark:hover:bg-white/5 border-b border-[#EAE4D8] dark:border-white/10 last:border-0 transition-colors">
                            <td class="pl-3 sm:pl-4 py-3 text-[0.8rem] sm:text-base">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-full bg-[#F4F0E6] dark:bg-white/10 border border-[#EAE4D8] dark:border-white/10 flex items-center justify-center text-blue-700 dark:text-blue-400 font-bold text-[0.75rem] sm:text-[0.9rem] flex-shrink-0">
                                        <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                    </div>
                                    <span class="text-gray-800 dark:text-gray-100 font-medium"><?php echo e($user->name); ?></span>
                                </div>
                            </td>
                            <td class="py-3 text-[0.8rem] sm:text-base">
                                <span class="text-gray-600 dark:text-gray-400"><?php echo e($user->email); ?></span>
                            </td>
                            <td class="py-3">
                                <span class="inline-flex items-center rounded-full font-semibold text-[0.7rem] px-2.5 py-1 sm:text-xs sm:px-3 sm:py-1.5
                                    <?php if(strtolower($user->role) === 'admin'): ?> bg-blue-100 text-blue-700 border border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20
                                    <?php else: ?> bg-gray-100 text-gray-700 border border-gray-200 dark:bg-white/5 dark:text-gray-400 dark:border-white/10
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst($user->role)); ?>

                                </span>
                            </td>
                            <td class="text-right pr-3 sm:pr-4 py-3">
                                <div class="flex justify-end gap-2 flex-nowrap">
                                    
                                    <button type="button"
                                            class="w-7 h-7 sm:w-[34px] sm:h-[34px] rounded-full p-0 inline-flex items-center justify-center border-none text-white text-[0.75rem] sm:text-[0.95rem] transition-all duration-200 bg-gradient-to-br from-cyan-500 to-cyan-600 shadow-[0_2px_6px_rgba(8,145,178,0.3)] hover:text-white hover:-translate-y-[2px] hover:scale-[1.05] hover:shadow-[0_4px_12px_rgba(8,145,178,0.4)] focus:outline-none focus:ring-2 focus:ring-cyan-300 dark:focus:ring-cyan-400/50"
                                            title="Editar"
                                            onclick="prepararEdicion('<?php echo e($user->id); ?>', '<?php echo e(addslashes($user->name)); ?>', '<?php echo e($user->role); ?>', '<?php echo e($user->email); ?>')">
                                        <ion-icon name="pencil"></ion-icon>
                                    </button>

                                    <?php if($user->id != 1): ?>
                                    <form action="<?php echo e(route('users.delete', $user->id)); ?>" method="POST" class="inline" onsubmit="return confirm('¿Seguro?')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit"
                                                class="w-7 h-7 sm:w-[34px] sm:h-[34px] rounded-full p-0 inline-flex items-center justify-center border-none text-white text-[0.75rem] sm:text-[0.95rem] transition-all duration-200 bg-gradient-to-br from-red-500 to-red-600 shadow-[0_2px_6px_rgba(220,38,38,0.3)] hover:text-white hover:-translate-y-[2px] hover:scale-[1.05] hover:shadow-[0_4px_12px_rgba(220,38,38,0.4)] focus:outline-none focus:ring-2 focus:ring-red-300 dark:focus:ring-red-400/50"
                                                title="Eliminar">
                                            <ion-icon name="trash"></ion-icon>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php echo $__env->make('admin.modals.editar-usuario', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<script>
    // 1. Función global para abrir cualquier modal
    window.openModal = function(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100', 'pointer-events-auto');
        }
    };

    // 2. Lógica para cerrar modales (se ejecuta al cargar la página)
    document.addEventListener('DOMContentLoaded', () => {
        const closeButtons = document.querySelectorAll('.btn-close-modal');
        
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modal = this.closest('[role="dialog"]');
                if (modal) {
                    modal.classList.add('opacity-0', 'pointer-events-none');
                    modal.classList.remove('opacity-100', 'pointer-events-auto');
                }
            });
        });
    });
</script>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\tortu\Checador-Online\resources\views/home.blade.php ENDPATH**/ ?>