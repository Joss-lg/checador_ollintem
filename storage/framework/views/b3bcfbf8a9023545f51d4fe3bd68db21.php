<div class="d-flex justify-content-end mb-3">
    <button type="button" onclick="openModal('modalRegistrarBecario')" 
            class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-blue-600 dark:text-blue-400 bg-white dark:bg-white/5 border border-[#EAE4D8] dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/10 hover:border-blue-200 dark:hover:border-blue-500/30 transition-all shadow-sm focus:outline-none">
        <ion-icon name="person-add"></ion-icon>
        <span>Nuevo Becario</span>
    </button>
</div>

<div id="modalRegistrarBecario" 
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 dark:bg-black/70 backdrop-blur-sm dark:backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300 ease-out p-4" 
     role="dialog" aria-modal="true">
    
    <div class="modal-dialog relative w-full max-w-2xl max-h-[90vh] flex flex-col bg-white dark:bg-[#15181d] text-gray-800 dark:text-white border border-[#EAE4D8] dark:border-white/10 rounded-2xl shadow-2xl dark:shadow-[0_20px_60px_-15px_rgba(0,0,0,0.7)] overflow-hidden transform scale-95 transition-transform duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]">

        <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-blue-500 to-transparent opacity-0 dark:opacity-90"></div>

       <div class="shrink-0 flex items-center justify-between px-6 pt-6 pb-4 bg-[#F4F0E6] dark:bg-[#1a1d23]">
            <div class="flex items-center gap-4 min-w-0">
                <div class="flex items-center justify-center shrink-0 w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 text-blue-700 dark:text-blue-400 text-xl dark:shadow-[0_0_20px_rgba(59,130,246,0.2)]">
+                  <ion-icon name="person"></ion-icon>
                </div>
                <div class="min-w-0">
                    <h5 class="text-lg font-bold text-gray-900 dark:text-white m-0 truncate">Registrar Nuevo Becario</h5>
                </div>
            </div>

            <button type="button" class="btn-close-modal shrink-0 text-gray-500 dark:text-gray-500 hover:text-gray-800 dark:hover:text-white dark:hover:bg-white/10 rounded-lg p-1.5 -mr-1.5 transition-colors" aria-label="Cerrar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="shrink-0 h-px bg-[#EAE4D8] dark:bg-white/[0.08]"></div>

       <form id="formRegistrarBecario" action="<?php echo e(route('admin.becarios.store')); ?>" method="POST" class="flex flex-col min-h-0 flex-grow">
            <?php echo csrf_field(); ?>
            <div class="px-6 py-5 overflow-y-auto flex-grow min-h-0">

                <!-- Alerta de Contraseña Dinámica (Oculta por defecto) -->
<div id="passwordAlert" class="hidden overflow-hidden transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] opacity-0 -translate-y-4 mb-6">
    <div class="flex items-start gap-3 p-4 text-sm text-red-800 dark:text-red-400 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-xl shadow-sm">
        <div class="flex items-center justify-center shrink-0 w-8 h-8 rounded-lg bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400">
            <ion-icon name="alert-circle-outline" class="text-lg"></ion-icon>
        </div>
        <div class="flex-1 pt-1.5">
            <span class="font-bold block mb-0.5 text-red-900 dark:text-red-300">Error de Seguridad</span>
            <span id="passwordAlertMessage">Las contraseñas no coinciden.</span>
        </div>
        <button type="button" onclick="ocultarAlertaContrasena()" class="text-red-500 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 p-1.5 rounded-lg transition-colors mt-0.5">
            <ion-icon name="close-outline"></ion-icon>
        </button>
    </div>
</div>

<!-- Opcional: Mostrar errores que vengan del backend de Laravel -->
<?php if($errors->has('password')): ?>
<div class="mb-6 flex items-start gap-3 p-4 text-sm text-red-800 dark:text-red-400 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-xl shadow-sm">
    <div class="flex items-center justify-center shrink-0 w-8 h-8 rounded-lg bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400">
        <ion-icon name="shield-exclamation-outline" class="text-lg"></ion-icon>
    </div>
    <div class="flex-1 pt-1.5">
        <span class="font-bold block mb-0.5 text-red-900 dark:text-red-300">Error del Servidor</span>
        <span><?php echo e($errors->first('password')); ?></span>
    </div>
</div>
<?php endif; ?>
                
                <div class="mb-6">
                    <label class="flex items-center gap-2 text-xs font-bold tracking-widest text-gray-500 dark:text-gray-500 uppercase mb-4">
                        <ion-icon name="people-circle" class="text-gray-400 dark:text-gray-600"></ion-icon> Información del Becario
                    </label>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nombre Completo</label>
                            <input type="text" name="name" id="nombre_becario"
                                   class="w-full px-4 py-2.5 bg-white dark:bg-white/[0.04] border border-[#EAE4D8] dark:border-white/10 rounded-lg text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/40 focus:border-blue-400 dark:focus:border-blue-500/60 transition-all"
                                   onkeyup="generarEmailAuto()" placeholder="Ej. Juan Pérez" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Usuario de Acceso (Correo)</label>
                            <div class="flex flex-col sm:flex-row rounded-lg shadow-sm">
                                <span class="inline-flex items-center px-4 py-2.5 bg-gray-50 dark:bg-white/[0.06] border border-b-0 sm:border-b sm:border-r-0 border-[#EAE4D8] dark:border-white/10 rounded-t-lg sm:rounded-t-none sm:rounded-l-lg text-gray-500 dark:text-gray-500">
                                   <ion-icon name="mail-outline"></ion-icon>
                                </span>
                                <input type="text" id="email_prefix" readonly placeholder="usuario"
                                       class="w-full min-w-0 px-4 py-2.5 bg-gray-50 dark:bg-white/[0.02] border-x sm:border-x-0 sm:border-y border-[#EAE4D8] dark:border-white/10 text-gray-500 dark:text-gray-500 focus:outline-none cursor-not-allowed">
                                <span class="inline-flex items-center px-4 py-2.5 bg-gray-50 dark:bg-white/[0.06] border border-t-0 sm:border-t sm:border-l-0 border-[#EAE4D8] dark:border-white/10 rounded-b-lg sm:rounded-b-none sm:rounded-r-lg text-gray-500 dark:text-gray-500 text-sm">
                                    @ollintem.com.mx
                                </span>
                            </div>
                            <input type="hidden" name="email" id="email_corporativo">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="flex items-center gap-2 text-xs font-bold tracking-widest text-gray-500 dark:text-gray-500 uppercase mb-4">
                        <ion-icon name="shield-lock-outline" class="text-gray-400 dark:text-gray-600"></ion-icon> Configuración de Seguridad
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Contraseña</label>
                            <input type="password" name="password" placeholder="••••••••" required
                                   class="w-full px-4 py-2.5 bg-white dark:bg-white/[0.04] border border-[#EAE4D8] dark:border-white/10 rounded-lg text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/40 focus:border-blue-400 dark:focus:border-blue-500/60 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" placeholder="••••••••" required
                                   class="w-full px-4 py-2.5 bg-white dark:bg-white/[0.04] border border-[#EAE4D8] dark:border-white/10 rounded-lg text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/40 focus:border-blue-400 dark:focus:border-blue-500/60 transition-all">
                        </div>
                    </div>
                </div>

                <input type="hidden" name="role" value="becario">
            </div>

            <div class="shrink-0 flex flex-col-reverse sm:flex-row justify-end gap-3 px-6 py-4 bg-[#F4F0E6] dark:bg-white/[0.03] border-t border-[#EAE4D8] dark:border-white/[0.08]">
                <button type="button" class="btn-close-modal w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-white/5 border border-[#EAE4D8] dark:border-white/10 rounded-lg hover:bg-gray-50 dark:hover:bg-white/10 dark:hover:text-white transition-colors focus:outline-none">
                    Cancelar
                </button>
                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 dark:hover:bg-blue-500 shadow-sm dark:shadow-[0_4px_20px_-2px_rgba(37,99,235,0.5)] hover:-translate-y-0.5 dark:hover:-translate-y-[2px] transition-all focus:outline-none">
                    Guardar Becario
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function generarEmailAuto() {
    let nombre = document.getElementById('nombre_becario').value;
    let emailBase = nombre.toLowerCase().replace(/\s+/g, '.');
    document.getElementById('email_prefix').value = emailBase;
    document.getElementById('email_corporativo').value = emailBase + "@ollintem.com.mx";
}

// Funciones para la alerta dinámica
function mostrarAlertaContrasena(mensaje) {
    const alertBox = document.getElementById('passwordAlert');
    const alertMsg = document.getElementById('passwordAlertMessage');
    
    // Cambiar el mensaje
    alertMsg.textContent = mensaje;
    
    // Quitar el hidden para que ocupe espacio
    alertBox.classList.remove('hidden');
    
    // Pequeño retraso para que el navegador procese el renderizado y aplique la animación
    setTimeout(() => {
        alertBox.classList.remove('opacity-0', '-translate-y-4');
        alertBox.classList.add('opacity-100', 'translate-y-0');
    }, 10);
}

function ocultarAlertaContrasena() {
    const alertBox = document.getElementById('passwordAlert');
    
    // Iniciar animación de salida
    alertBox.classList.remove('opacity-100', 'translate-y-0');
    alertBox.classList.add('opacity-0', '-translate-y-4');
    
    // Esperar a que termine la animación para ocultar el div
    setTimeout(() => {
        alertBox.classList.add('hidden');
    }, 300);
}

// Validación al enviar el formulario
document.getElementById('formRegistrarBecario').addEventListener('submit', function(e) {
    // Obtener los valores de los inputs de contraseña
    const password = document.querySelector('input[name="password"]').value;
    const passwordConfirm = document.querySelector('input[name="password_confirmation"]').value;
    
    // 1. Validar longitud mínima
    if (password.length < 8) {
        e.preventDefault(); // Detiene el envío
        mostrarAlertaContrasena('La contraseña debe tener al menos 8 caracteres para ser segura.');
        return;
    }

    // 2. Validar que coincidan
    if (password !== passwordConfirm) {
        e.preventDefault(); // Detiene el envío
        mostrarAlertaContrasena('Las contraseñas no coinciden. Por favor, verifícalas e inténtalo de nuevo.');
        return;
    }
    
    // Si todo está bien, ocultamos cualquier alerta previa y el formulario se enviará normalmente
    ocultarAlertaContrasena();
});

// Opcional: Ocultar la alerta automáticamente cuando el usuario empiece a escribir de nuevo
document.querySelector('input[name="password"]').addEventListener('input', ocultarAlertaContrasena);
document.querySelector('input[name="password_confirmation"]').addEventListener('input', ocultarAlertaContrasena);
</script><?php /**PATH C:\Users\tortu\Checador-Online\resources\views/admin/modals/registrar-becario.blade.php ENDPATH**/ ?>