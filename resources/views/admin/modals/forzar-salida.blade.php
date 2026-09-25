{{--
    Modal: Forzar salida de un becario (Admin)
    Controlado desde public/js/admin/dashboard.js
--}}
<div id="modalForzarSalida"
     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 dark:bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 ease-out"
     role="dialog" aria-modal="true" aria-labelledby="modalForzarSalidaLabel">

    <div class="modal-dialog relative w-full max-w-md mx-4 bg-white dark:bg-[#1b1e24] text-gray-900 dark:text-white border border-[#EAE4D8] dark:border-white/10 rounded-2xl shadow-xl dark:shadow-2xl overflow-hidden transform scale-95 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]">

        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-red-500 to-transparent opacity-90"></div>

        {{-- Encabezado --}}
        <div class="flex items-center justify-between px-6 pt-6 pb-4">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center shrink-0 w-11 h-11 rounded-xl bg-red-100 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-600 dark:text-red-400 text-xl shadow-sm">
                    <ion-icon name="log-out-outline"></ion-icon>
                </div>
                <div>
                    <p class="text-xs font-bold tracking-widest text-gray-500 dark:text-gray-400 uppercase mb-1">Administrador</p>
                    <h5 class="text-lg font-bold m-0" id="modalForzarSalidaLabel">Forzar salida</h5>
                </div>
            </div>
            <button type="button" class="btn-cerrar-forzar text-gray-400 hover:text-gray-700 dark:hover:text-white transition-colors" aria-label="Cerrar">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="h-px mx-6 bg-gradient-to-r from-transparent via-gray-200 dark:via-white/10 to-transparent"></div>

        {{-- Cuerpo --}}
        <div class="px-6 py-5 space-y-3">
            <p class="text-sm text-gray-600 dark:text-gray-300 m-0">
                Becario: <span id="forzarNombreBecario" class="font-semibold text-gray-900 dark:text-white"></span>
            </p>
            <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-300 m-0">
                Se registrará la salida ahora mismo con la hora actual. Si el becario tiene una pausa activa, también se cerrará.
            </p>
            <div class="flex items-start gap-2 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-lg px-4 py-3">
                <ion-icon name="warning-outline" class="text-red-500 dark:text-red-400 text-lg mt-0.5 flex-shrink-0"></ion-icon>
                <p class="text-xs text-red-700 dark:text-red-400 m-0">
                    Esta acción no se puede deshacer desde aquí. Si necesitas corregir la hora, usa el módulo de Historial.
                </p>
            </div>
        </div>

        <div class="h-px mx-6 bg-gradient-to-r from-transparent via-gray-200 dark:via-white/10 to-transparent"></div>

        {{-- Footer --}}
        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 px-6 py-5 bg-slate-50 dark:bg-white/5">
            <button type="button"
                    class="btn-cerrar-forzar w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-white border border-gray-300 dark:border-white/20 rounded-lg hover:bg-gray-100 dark:hover:bg-white/10 transition-colors focus:outline-none">
                Cancelar
            </button>
            <button type="button"
                    id="btnConfirmarForzarSalida"
                    class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg shadow-[0_4px_14px_0_rgba(220,38,38,0.35)] hover:bg-red-500 hover:-translate-y-0.5 transition-all focus:ring-2 focus:ring-red-400/50 focus:outline-none">
                <ion-icon name="log-out-outline" class="text-lg"></ion-icon>
                Sí, registrar salida
            </button>
        </div>

    </div>
</div>

{{-- Toast de resultado --}}
<div id="toastForzarSalida"
     class="fixed bottom-5 right-5 z-[60] flex items-center gap-3 px-5 py-3.5 rounded-xl shadow-xl border text-sm font-medium opacity-0 pointer-events-none transition-all duration-300 translate-y-2"
     role="alert">
    <ion-icon id="toastForzarIcono" name="checkmark-circle-outline" class="text-xl flex-shrink-0"></ion-icon>
    <span id="toastForzarMensaje"></span>
</div>
