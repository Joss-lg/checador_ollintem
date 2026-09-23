{{--
    Modal para que el admin corrija hora de entrada/salida de una jornada.
    El JS del historial rellena los campos antes de abrir el modal.
    Tokens de color: mismos que editar-usuario.blade.php (panel admin).
--}}
<div id="modalEditarJornada"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 dark:bg-black/70 backdrop-blur-sm dark:backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300 ease-out p-4"
     role="dialog" aria-modal="true" aria-labelledby="modalEditarJornadaLabel">

    <div class="modal-dialog relative w-full max-w-md flex flex-col bg-white dark:bg-[#15181d] text-gray-800 dark:text-white border border-[#EAE4D8] dark:border-white/10 rounded-2xl shadow-2xl dark:shadow-[0_20px_60px_-15px_rgba(0,0,0,0.7)] overflow-hidden transform scale-95 transition-transform duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]">

        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-blue-500 to-transparent opacity-90"></div>

        {{-- Encabezado --}}
        <div class="flex items-center justify-between px-6 pt-6 pb-4 bg-[#F4F0E6] dark:bg-[#1a1d23]">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center shrink-0 w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 text-blue-700 dark:text-blue-400 text-xl dark:shadow-[0_0_20px_rgba(59,130,246,0.2)]">
                    <ion-icon name="create-outline"></ion-icon>
                </div>
                <div>
                    <p class="text-xs font-bold tracking-widest text-gray-500 dark:text-gray-500 uppercase mb-1">Administración</p>
                    <h5 class="text-lg font-bold text-gray-900 dark:text-white m-0" id="modalEditarJornadaLabel">Editar jornada</h5>
                </div>
            </div>
            <button type="button" onclick="closeModal('modalEditarJornada')"
                    class="shrink-0 text-gray-500 dark:text-gray-500 hover:text-gray-800 dark:hover:text-white dark:hover:bg-white/10 rounded-lg p-1.5 -mr-1.5 transition-colors" aria-label="Cerrar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="h-px bg-[#EAE4D8] dark:bg-white/[0.08]"></div>

        {{-- Info solo lectura --}}
        <div class="px-6 pt-4 pb-2 grid grid-cols-2 gap-3">
            <div>
                <p class="text-xs font-bold tracking-widest text-gray-500 dark:text-gray-500 uppercase mb-1">Becario</p>
                <p id="editarBecarioNombre" class="text-sm font-semibold text-gray-800 dark:text-white mb-0">—</p>
            </div>
            <div>
                <p class="text-xs font-bold tracking-widest text-gray-500 dark:text-gray-500 uppercase mb-1">Fecha</p>
                <p id="editarFecha" class="text-sm font-semibold text-gray-800 dark:text-white mb-0">—</p>
            </div>
        </div>

        <div class="h-px mx-6 mt-4 bg-[#EAE4D8] dark:bg-white/[0.08]"></div>

        {{-- Formulario --}}
        <form id="formEditarJornada" method="POST" action="">
            @csrf
            @method('PATCH')

            <div class="px-6 py-5 space-y-4">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="editarHoraEntrada" class="block text-xs font-bold tracking-widest text-gray-500 dark:text-gray-500 uppercase mb-1.5">
                            Hora de entrada
                        </label>
                        <input type="time" step="1"
                               id="editarHoraEntrada" name="hora_entrada"
                               class="w-full px-3 py-2.5 bg-white dark:bg-white/[0.04] border border-[#EAE4D8] dark:border-white/10 rounded-lg text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/40 focus:border-blue-400 dark:focus:border-blue-500/60 transition-all">
                    </div>
                    <div>
                        <label for="editarHoraSalida" class="block text-xs font-bold tracking-widest text-gray-500 dark:text-gray-500 uppercase mb-1.5">
                            Hora de salida
                        </label>
                        <input type="time" step="1"
                               id="editarHoraSalida" name="hora_salida"
                               class="w-full px-3 py-2.5 bg-white dark:bg-white/[0.04] border border-[#EAE4D8] dark:border-white/10 rounded-lg text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/40 focus:border-blue-400 dark:focus:border-blue-500/60 transition-all">
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-600">Vacío = jornada abierta.</p>
                    </div>
                </div>

                <div>
                    <label for="editarMotivo" class="block text-xs font-bold tracking-widest text-gray-500 dark:text-gray-500 uppercase mb-1.5">
                        Motivo de la corrección <span class="text-red-500">*</span>
                    </label>
                    <textarea id="editarMotivo" name="motivo" rows="2" required
                              placeholder="Ej: Olvidó registrar salida, trabajó hasta las 2pm."
                              class="w-full px-3 py-2.5 bg-white dark:bg-white/[0.04] border border-[#EAE4D8] dark:border-white/10 rounded-lg text-sm text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-600 resize-none focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/40 focus:border-blue-400 dark:focus:border-blue-500/60 transition-all"></textarea>
                </div>

            </div>

            <div class="h-px bg-[#EAE4D8] dark:bg-white/[0.08]"></div>

            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 px-6 py-4 bg-[#F4F0E6] dark:bg-white/[0.03]">
                <button type="button" onclick="closeModal('modalEditarJornada')"
                        class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-white/5 border border-[#EAE4D8] dark:border-white/10 rounded-lg hover:bg-gray-50 dark:hover:bg-white/10 dark:hover:text-white transition-colors focus:outline-none">
                    Cancelar
                </button>
                <button type="submit"
                        class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg shadow-[0_4px_14px_0_rgba(37,99,235,0.39)] hover:bg-blue-500 hover:-translate-y-0.5 transition-all focus:ring-2 focus:ring-blue-500/40 focus:outline-none">
                    <ion-icon name="save-outline" class="text-lg"></ion-icon>
                    Guardar cambios
                </button>
            </div>

        </form>
    </div>
</div>
