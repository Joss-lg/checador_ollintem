<?php $__env->startSection('content'); ?>

<div class="fixed inset-0 z-0 flex justify-center items-center overflow-hidden p-5 bg-[#eef4fb] dark:bg-[#050b16] transition-colors duration-500">

    <!-- Textura de red sutil -->
    <div class="absolute inset-0 opacity-[0.05] dark:opacity-[0.08]" style="background-image: radial-gradient(circle, #1d4ed8 1px, transparent 1px); background-size: 26px 26px;"></div>

    <!-- Anillos de acceso (radar): cian > azul > morado (se apaga hacia afuera) -->
    <div class="pointer-events-none absolute inset-0 flex items-center justify-center">
        <div class="ollin-ring absolute w-[420px] h-[420px] rounded-full border border-cyan-400/35"></div>
        <div class="ollin-ring absolute w-[580px] h-[580px] rounded-full border border-blue-500/25" style="animation-delay: 1.3s;"></div>
        <div class="ollin-ring absolute w-[740px] h-[740px] rounded-full border border-violet-500/15" style="animation-delay: 2.6s;"></div>
    </div>

    <!-- Resplandor ambiental detrás de la tarjeta: azul que se funde hacia morado (complemento) -->
    <div class="absolute w-[480px] h-[480px] rounded-full blur-[90px] opacity-40 dark:opacity-30" style="background: radial-gradient(circle, rgba(37,99,235,.55) 0%, rgba(124,58,237,.22) 55%, transparent 75%);"></div>

    <!-- Fondo para TEMA CLARO (se muestra por defecto, se oculta en oscuro) -->
    <div class="absolute inset-0 bg-center bg-no-repeat bg-[length:900px] opacity-[0.035] blur-[5px] scale-[1.15] block dark:hidden"
         style="background-image:url('<?php echo e(asset('images/ollintem-logo-blanc.png')); ?>')"></div>

    <!-- Fondo para TEMA OSCURO (se oculta en claro, se muestra en oscuro) -->
    <div class="absolute inset-0 bg-center bg-no-repeat bg-[length:900px] opacity-[0.07] blur-[5px] scale-[1.15] hidden dark:block"
         style="background-image:url('<?php echo e(asset('images/ollintem-logo.png')); ?>')"></div>

    <!-- Capa de superposición -->
    <div class="absolute inset-0 bg-white/30 dark:bg-[#04070f]/60 transition-colors duration-500"></div>

    <!-- Tarjeta principal con borde cian nítido -->
    <div class="relative z-10 w-full max-w-[390px] rounded-[26px] border-2 border-cyan-400/70 dark:border-cyan-400/50 bg-white/85 dark:bg-[#0d1420]/[.92] backdrop-blur-2xl shadow-[0_0_0_1px_rgba(34,211,238,.15),0_0_40px_-6px_rgba(34,211,238,.55)] dark:shadow-[0_0_0_1px_rgba(34,211,238,.12),0_0_55px_-6px_rgba(34,211,238,.4)] overflow-hidden transition-all duration-500">

        <!-- Línea de escaneo -->
        <div class="relative h-[3px] w-full bg-cyan-400/15 dark:bg-cyan-400/10 overflow-hidden">
            <div class="ollin-scan absolute inset-y-0 w-1/3 bg-gradient-to-r from-transparent via-cyan-300 dark:via-cyan-200 to-transparent"></div>
        </div>

        <div class="text-center px-5 pt-5 pb-1.5">

            <!-- Logotipo con marcas de esquina (bordes cian) -->
            <div class="relative w-[190px] mx-auto mb-4 py-4">
                <span class="absolute top-0 left-0 w-5 h-5 border-t-2 border-l-2 border-cyan-400"></span>
                <span class="absolute top-0 right-0 w-5 h-5 border-t-2 border-r-2 border-cyan-400"></span>
                <span class="absolute bottom-0 left-0 w-5 h-5 border-b-2 border-l-2 border-cyan-400"></span>
                <span class="absolute bottom-0 right-0 w-5 h-5 border-b-2 border-r-2 border-cyan-400"></span>

                <!-- Logo para el TEMA CLARO -->
                <img src="<?php echo e(asset('images/ollintem-logo-blanc.png')); ?>"
                     class="w-[170px] mx-auto block dark:hidden">

                <!-- Logo para el TEMA OSCURO -->
                <img src="<?php echo e(asset('images/ollintem-logo.png')); ?>"
                     class="w-[170px] mx-auto hidden dark:block">
            </div>

            <div class="flex items-center justify-center gap-1.5 mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                <span class="text-[11px] tracking-[0.2em] uppercase text-cyan-600 dark:text-cyan-400 font-semibold">Acceso seguro</span>
            </div>

            <h2 class="text-gray-800 dark:text-white text-[2rem] font-bold mb-0.5 leading-[1.15] tracking-tight">Bienvenido de nuevo</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-0">Inicia sesión en tu espacio de trabajo</p>
        </div>

        <div class="p-5">

            <form method="POST" action="<?php echo e(route('login')); ?>">
                <?php echo csrf_field(); ?>

                <div class="mb-3">
                    <label class="text-gray-700 dark:text-white font-medium dark:font-normal block mb-1.5">Correo electrónico</label>

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-cyan-600/70 dark:text-cyan-400/70">
                            <ion-icon name="mail-outline" class="text-lg"></ion-icon>
                        </span>
                        <input
                            type="email"
                            name="email"
                            value="<?php echo e(old('email')); ?>"
                            class="w-full bg-gray-50 dark:bg-[#0f1724] border <?php echo e($errors->has('email') ? 'border-red-500' : 'border-gray-300 dark:border-white/[.08]'); ?> text-gray-900 dark:text-white p-3.5 pl-11 rounded-xl box-border focus:bg-white dark:focus:bg-[#101b2c] focus:border-cyan-400 focus:shadow-[0_0_0_.20rem_rgba(34,211,238,.25)] focus:outline-none transition-colors"
                            required
                            autofocus>
                    </div>

                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="block text-red-500 dark:text-red-400 text-sm mt-1.5">
                            <strong><?php echo e($message); ?></strong>
                        </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3">
                    <label class="text-gray-700 dark:text-white font-medium dark:font-normal block mb-1.5">Contraseña</label>

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-cyan-600/70 dark:text-cyan-400/70">
                            <ion-icon name="lock-closed-outline" class="text-lg"></ion-icon>
                        </span>
                        <input
                            type="password"
                            name="password"
                            class="w-full bg-gray-50 dark:bg-[#0f1724] border <?php echo e($errors->has('password') ? 'border-red-500' : 'border-gray-300 dark:border-white/[.08]'); ?> text-gray-900 dark:text-white p-3.5 pl-11 rounded-xl box-border focus:bg-white dark:focus:bg-[#101b2c] focus:border-cyan-400 focus:shadow-[0_0_0_.20rem_rgba(34,211,238,.25)] focus:outline-none transition-colors"
                            required>
                    </div>

                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="block text-red-500 dark:text-red-400 text-sm mt-1.5">
                            <strong><?php echo e($message); ?></strong>
                        </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="flex items-center gap-2 mb-3">
                    <input
                        type="checkbox"
                        name="remember"
                        id="remember"
                        class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-[#0f1724] text-cyan-500 focus:ring-cyan-400">

                    <label class="text-gray-600 dark:text-gray-300 select-none cursor-pointer" for="remember">
                        Recordar mi sesión
                    </label>
                </div>

                <button class="w-full p-3 border-2 border-cyan-400/60 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold transition-all duration-300 cursor-pointer hover:-translate-y-0.5 shadow-[0_0_22px_-4px_rgba(34,211,238,.55)] hover:shadow-[0_0_28px_-4px_rgba(34,211,238,.65),0_0_45px_-10px_rgba(124,58,237,.35)] flex items-center justify-center gap-2">
                    <span>Acceder al sistema</span>
                    <ion-icon name="log-in-outline" class="text-xl"></ion-icon>
                </button>

            </form>

        </div>

    </div>

</div>

<style>
    @keyframes ollinPing {
        0%   { transform: scale(.85); opacity: .6; }
        70%  { opacity: 0; }
        100% { transform: scale(1.15); opacity: 0; }
    }
    .ollin-ring { animation: ollinPing 4.2s ease-out infinite; }

    @keyframes ollinScan {
        0%   { left: -40%; }
        100% { left: 110%; }
    }
    .ollin-scan { animation: ollinScan 3.2s ease-in-out infinite; }

    @media (prefers-reduced-motion: reduce) {
        .ollin-ring, .ollin-scan { animation: none !important; }
    }
</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\tortu\Checador-Online\resources\views/auth/login.blade.php ENDPATH**/ ?>