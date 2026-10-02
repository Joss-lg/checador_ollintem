{{--
    Modal: Forzar salida de un becario (Admin)
    Controlado desde public/js/admin/dashboard.js
    Tokens de color: mismos que editar-usuario.blade.php (panel admin)
--}}
<div id="modalForzarSalida"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 dark:bg-black/70 backdrop-blur-sm dark:backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300 ease-out p-4"
     role="dialog" aria-modal="true" aria-labelledby="modalForzarSalidaLabel">

    <div class="modal-dialog relative w-full max-w-md flex flex-col bg-white dark:bg-[#15181d] text-gray-800 dark:text-white border border-[#EAE4D8] dark:border-white/10 rounded-2xl shadow-2xl dark:shadow-[0_20px_60px_-15px_rgba(0,0,0,0.7)] overflow-hidden transform scale-95 transition-transform duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]">

        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-red-500 to-transparent opacity-90"></div>

        {{-- Encabezado --}}
        <div class="flex items-center justify-between px-6 pt-6 pb-4 bg-[#F4F0E6] dark:bg-[#1a1d23]">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center shrink-0 w-11 h-11 rounded-xl bg-red-100 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-600 dark:text-red-400 text-xl dark:shadow-[0_0_20px_rgba(239,68,68,0.2)]">
                    <ion-icon name="log-out-outline"></ion-icon>
                </div>
                <div>
                    <p class="text-xs font-bold tracking-widest text-gray-500 dark:text-gray-500 uppercase mb-1">Administrador</p>
                    <h5 class="text-lg font-bold text-gray-900 dark:text-white m-0" id="modalForzarSalidaLabel">Forzar salida</h5>
                </div>
            </div>
            <button type="button" class="btn-cerrar-forzar shrink-0 text-gray-500 dark:text-gray-500 hover:text-gray-800 dark:hover:text-white dark:hover:bg-white/10 rounded-lg p-1.5 -mr-1.5 transition-colors" aria-label="Cerrar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="h-px bg-[#EAE4D8] dark:bg-white/[0.08]"></div>

        {{-- Cuerpo --}}
        <div class="px-6 py-5 space-y-3">
            <p class="text-sm text-gray-600 dark:text-gray-400 m-0">
                Becario: <span id="forzarNombreBecario" class="font-semibold text-gray-900 dark:text-white"></span>
            </p>
            <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-400 m-0">
                Se registrará la salida ahora mismo con la hora actual. Si el becario tiene una pausa activa, también se cerrará.
            </p>
            <div class="flex items-start gap-2 bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20 rounded-lg px-4 py-3">
                <ion-icon name="warning-outline" class="text-red-500 dark:text-red-400 text-lg mt-0.5 flex-shrink-0"></ion-icon>
                <p class="text-xs text-red-700 dark:text-red-400 m-0">
                    Esta acción no se puede deshacer desde aquí. Si necesitas corregir la hora, usa el módulo de Historial.
                </p>
            </div>
        </div>

        <div class="h-px bg-[#EAE4D8] dark:bg-white/[0.08]"></div>

        {{-- Footer --}}
        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 px-6 py-4 bg-[#F4F0E6] dark:bg-white/[0.03]">
            <button type="button"
                    class="btn-cerrar-forzar w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-white/5 border border-[#EAE4D8] dark:border-white/10 rounded-lg hover:bg-gray-50 dark:hover:bg-white/10 dark:hover:text-white transition-colors focus:outline-none">
                Cancelar
            </button>
            <button type="button"
                    id="btnConfirmarForzarSalida"
                    class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg shadow-[0_4px_14px_0_rgba(220,38,38,0.4)] hover:bg-red-500 hover:-translate-y-0.5 transition-all focus:ring-2 focus:ring-red-500/40 focus:outline-none">
                <ion-icon name="log-out-outline" class="text-lg"></ion-icon>
                Sí, registrar salida
            </button>
        </div>

    </div>
</div>
