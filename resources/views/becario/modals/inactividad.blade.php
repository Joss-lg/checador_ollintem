{{--
    Modal de inactividad — se abre desde becario-dashboard.js cada PING_MS.
    Tokens de color: mismos que confirmar_descanso.blade.php y finalizar_turno.blade.php.
--}}
<div id="modalInactividad"
     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 dark:bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 ease-out"
     role="dialog" aria-modal="true" aria-labelledby="modalInactividadLabel">

    <div class="modal-dialog relative w-full max-w-md mx-4 bg-white dark:bg-[#1b1e24] text-gray-900 dark:text-white border border-[#EAE4D8] dark:border-white/10 rounded-2xl shadow-xl dark:shadow-2xl overflow-hidden transform scale-95 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]">

        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-amber-400 to-transparent opacity-90"></div>

        {{-- Encabezado --}}
        <div class="flex items-center gap-4 px-6 pt-6 pb-4">
            <div class="flex items-center justify-center shrink-0 w-11 h-11 rounded-xl bg-amber-100 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 text-amber-600 dark:text-amber-400 text-xl shadow-sm dark:shadow-[0_0_15px_rgba(245,158,11,0.15)] transition-colors">
                <ion-icon name="time-outline"></ion-icon>
            </div>
            <div>
                <p class="text-xs font-bold tracking-widest text-gray-500 dark:text-gray-400 uppercase mb-1">Aviso de actividad</p>
                <h5 class="text-lg font-bold m-0" id="modalInactividadLabel">¿Sigues trabajando?</h5>
            </div>
        </div>

        <div class="h-px mx-6 bg-[#EAE4D8] dark:bg-white/10 transition-colors"></div>

        {{-- Cuerpo --}}
        <div class="px-6 py-5 space-y-3">
            <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-300 m-0">
                Han pasado 60 minutos desde que iniciaste tu jornada.
                Si no confirmas, tu salida se registrará automáticamente en:
            </p>
            <div class="flex items-center justify-center py-2">
                <span id="cuentaRegresivaInactividad"
                      class="text-4xl font-bold tabular-nums text-amber-500 dark:text-amber-400">
                    05:00
                </span>
            </div>
        </div>

        <div class="h-px mx-6 bg-[#EAE4D8] dark:bg-white/10 transition-colors"></div>

        {{-- Footer --}}
        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 px-6 py-5 bg-slate-50 dark:bg-white/5 transition-colors">

            <form id="formSalidaInactividad" method="POST" action="{{ route('salida.inactividad') }}">
                @csrf
                <button type="submit"
                        class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 rounded-lg hover:bg-gray-50 dark:hover:bg-white/10 dark:hover:text-white transition-colors focus:outline-none">
                    Registrar mi salida ahora
                </button>
            </form>

            <button type="button"
                    id="btnSigoTrabajando"
                    class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-amber-500 rounded-lg shadow-[0_4px_14px_0_rgba(245,158,11,0.35)] hover:bg-amber-400 hover:-translate-y-0.5 transition-all focus:ring-2 focus:ring-amber-400/50 focus:outline-none">
                <ion-icon name="checkmark-outline" class="text-lg"></ion-icon>
                Sigo trabajando
            </button>
        </div>

    </div>
</div>
