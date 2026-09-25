(function () {
    'use strict';

    // =========================================================================
    // CONFIGURACIÓN
    // =========================================================================
    const POLLING_MS = 1750;
    const TICK_MS    = 1000;

    // =========================================================================
    // ESTADO EN MEMORIA
    // =========================================================================
    let estadoAsistencias    = {};
    let intervaloSincronizacion = null;

    // =========================================================================
    // UTILIDADES
    // =========================================================================
    function formatoHMS(segundos) {
        segundos = Math.max(0, Math.floor(segundos));
        const h = String(Math.floor(segundos / 3600)).padStart(2, '0');
        const m = String(Math.floor((segundos % 3600) / 60)).padStart(2, '0');
        const s = String(segundos % 60).padStart(2, '0');
        return `${h}:${m}:${s}`;
    }

    function actualizarTexto(id, icono, texto) {
        const el = document.getElementById(id);
        if (!el) return;
        el.innerHTML = `<i class="bi ${icono} mr-1"></i>${texto}`;
    }

    // =========================================================================
    // TARJETAS RESUMEN
    // =========================================================================
    function actualizarTarjetasResumen(data) {
        let activos = 0, descanso = 0, finalizados = 0;
        data.forEach(a => {
            if (a.sin_registro) return;
            if (a.turno_terminado) finalizados++;
            else if (a.en_pausa) descanso++;
            else activos++;
        });
        document.getElementById('card-activos').textContent     = activos;
        document.getElementById('card-descanso').textContent    = descanso;
        document.getElementById('card-finalizados').textContent = finalizados;
    }

    // =========================================================================
    // BOTÓN FORZAR SALIDA
    // Sólo aparece si el becario tiene jornada activa (no terminada, no sin registro)
    // =========================================================================
    function htmlBtnForzarSalida(userId, nombre, turnoTerminado, sinRegistro) {
        if (turnoTerminado || sinRegistro) {
            return `<span class="text-gray-400 dark:text-gray-600 text-xs select-none">—</span>`;
        }
        return `
            <button
                class="btn-forzar-salida inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold
                       text-red-700 dark:text-red-400
                       bg-red-50 dark:bg-red-500/10
                       border border-red-200 dark:border-red-500/30
                       rounded-lg hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors"
                data-user-id="${userId}"
                data-user-name="${nombre}"
                title="Forzar salida de ${nombre}">
                <ion-icon name="log-out-outline" class="text-sm"></ion-icon>
                Forzar salida
            </button>`;
    }

    // =========================================================================
    // RENDER TABLA (desktop)
    // =========================================================================
    function crearFila(a) {
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-[#F9F6EE] dark:hover:bg-white/5 transition-colors';
        tr.setAttribute('data-user', a.user_id);
        tr.innerHTML = `
            <td class="py-3 text-center">
                <div class="flex items-center justify-center gap-2">
                    <div class="w-9 h-9 rounded-full bg-gray-500/25 border border-gray-500 flex items-center justify-center text-cyan-400 font-bold text-sm flex-shrink-0">
                        ${a.user_inicial}
                    </div>
                    <span class="text-black dark:text-white font-bold">${a.user_name}</span>
                </div>
            </td>
            <td class="py-3 text-center">${a.fecha}</td>
            <td class="py-3 text-center">
                <span id="entrada-${a.user_id}" class="inline-flex items-center rounded-full bg-green-500/25 text-green-400 px-3 py-2 text-sm font-medium">
                    <i class="bi bi-box-arrow-in-right mr-1"></i>${a.hora_entrada}
                </span>
            </td>
            <td class="py-3 text-center">
                <span id="salida-${a.user_id}" class="inline-flex items-center rounded-full bg-red-500/25 text-red-400 px-3 py-2 text-sm font-medium">
                    <i class="bi bi-box-arrow-left mr-1"></i>${a.hora_salida}
                </span>
            </td>
            <td class="py-3 text-center">
                <span id="pausas-${a.user_id}" class="inline-flex items-center rounded-full bg-yellow-500/25 text-yellow-400 px-3 py-2 text-sm font-medium">
                    <i class="bi bi-cup-hot mr-1"></i>${formatoHMS(a.pausas_segundos)}
                </span>
            </td>
            <td class="py-3 text-center">
                <span id="trabajado-${a.user_id}" class="inline-flex items-center rounded-full bg-cyan-500/25 text-cyan-400 px-3 py-2 text-sm font-medium">
                    <i class="bi bi-stopwatch mr-1"></i>${formatoHMS(a.trabajado_segundos)}
                </span>
            </td>
            <td class="py-3 text-center">
                <span id="estado-${a.user_id}" class="inline-flex items-center rounded-full px-3 py-2 text-sm font-medium ${a.estado.clase}">
                    ${a.estado.texto}
                </span>
            </td>
            <td class="py-3 text-center" id="td-accion-${a.user_id}">
                ${htmlBtnForzarSalida(a.user_id, a.user_name, a.turno_terminado, a.sin_registro)}
            </td>
        `;
        return tr;
    }

    function actualizarFila(a) {
        actualizarTexto('entrada-'   + a.user_id, 'bi-box-arrow-in-right', a.hora_entrada);
        actualizarTexto('salida-'    + a.user_id, 'bi-box-arrow-left',     a.hora_salida);
        actualizarTexto('pausas-'    + a.user_id, 'bi-cup-hot',            formatoHMS(a.pausas_segundos));
        actualizarTexto('trabajado-' + a.user_id, 'bi-stopwatch',          formatoHMS(a.trabajado_segundos));

        const elEstado = document.getElementById('estado-' + a.user_id);
        if (elEstado) {
            elEstado.className   = 'inline-flex items-center rounded-full px-3 py-2 text-sm font-medium ' + a.estado.clase;
            elEstado.textContent = a.estado.texto;
        }

        // Actualizar botón de acción (desaparece al terminar turno)
        const tdAccion = document.getElementById('td-accion-' + a.user_id);
        if (tdAccion) {
            tdAccion.innerHTML = htmlBtnForzarSalida(a.user_id, a.user_name, a.turno_terminado, a.sin_registro);
        }
    }

    function renderTablaVacia(tbody) {
        tbody.innerHTML = `
            <tr id="tabla-vacia">
                <td colspan="8" class="text-center text-gray-500 dark:text-gray-400 py-10">
                    <ion-icon name="time-outline" class="text-2xl block mb-2"></ion-icon>
                    No existen asistencias activas actualmente
                </td>
            </tr>`;
    }

    // =========================================================================
    // RENDER TARJETAS (móvil)
    // =========================================================================
    function crearTarjeta(a) {
        const div = document.createElement('div');
        div.className = 'bg-white dark:bg-white/[0.03] border border-[#EAE4D8] dark:border-white/10 rounded-xl overflow-hidden mb-3';
        div.setAttribute('data-user-card', a.user_id);
        div.innerHTML = `
            <div class="flex items-center justify-between px-4 py-3 border-b border-[#EAE4D8] dark:border-white/10">
                <div class="flex items-center gap-2 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-gray-500/25 border border-gray-500 flex items-center justify-center text-cyan-400 font-bold text-xs flex-shrink-0">
                        ${a.user_inicial}
                    </div>
                    <span class="text-gray-900 dark:text-white font-semibold text-sm">${a.user_name}</span>
                </div>
                <span id="estado-card-${a.user_id}" class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ${a.estado.clase}">
                    ${a.estado.texto}
                </span>
            </div>
            <div class="grid grid-cols-2 text-center">
                <div class="p-2.5 px-4 border-r border-b border-[#EAE4D8] dark:border-white/[0.08]">
                    <p class="m-0 text-[0.72rem] uppercase text-gray-400">Entrada</p>
                    <p id="entrada-card-${a.user_id}" class="mt-0.5 text-[0.85rem] text-green-600 dark:text-green-400">
                        <i class="bi bi-box-arrow-in-right mr-1"></i>${a.hora_entrada}
                    </p>
                </div>
                <div class="p-2.5 px-4 border-b border-[#EAE4D8] dark:border-white/[0.08]">
                    <p class="m-0 text-[0.72rem] uppercase text-gray-400">Salida</p>
                    <p id="salida-card-${a.user_id}" class="mt-0.5 text-[0.85rem] text-red-500 dark:text-red-400">
                        <i class="bi bi-box-arrow-left mr-1"></i>${a.hora_salida}
                    </p>
                </div>
                <div class="p-2.5 px-4 border-r border-[#EAE4D8] dark:border-white/[0.08]">
                    <p class="m-0 text-[0.72rem] uppercase text-gray-400">Pausas</p>
                    <p id="pausas-card-${a.user_id}" class="mt-0.5 text-[0.85rem] text-yellow-600 dark:text-yellow-400">
                        <i class="bi bi-cup-hot mr-1"></i>${formatoHMS(a.pausas_segundos)}
                    </p>
                </div>
                <div class="p-2.5 px-4">
                    <p class="m-0 text-[0.72rem] uppercase text-gray-400">Tiempo total</p>
                    <p id="trabajado-card-${a.user_id}" class="mt-0.5 text-[0.85rem] text-cyan-600 dark:text-cyan-400">
                        <i class="bi bi-stopwatch mr-1"></i>${formatoHMS(a.trabajado_segundos)}
                    </p>
                </div>
            </div>
            <div class="px-4 py-3 border-t border-[#EAE4D8] dark:border-white/[0.08] flex justify-end" id="td-accion-card-${a.user_id}">
                ${htmlBtnForzarSalida(a.user_id, a.user_name, a.turno_terminado, a.sin_registro)}
            </div>
        `;
        return div;
    }

    function actualizarTarjeta(a) {
        actualizarTexto('entrada-card-'   + a.user_id, 'bi-box-arrow-in-right', a.hora_entrada);
        actualizarTexto('salida-card-'    + a.user_id, 'bi-box-arrow-left',     a.hora_salida);
        actualizarTexto('pausas-card-'    + a.user_id, 'bi-cup-hot',            formatoHMS(a.pausas_segundos));
        actualizarTexto('trabajado-card-' + a.user_id, 'bi-stopwatch',          formatoHMS(a.trabajado_segundos));

        const elEstado = document.getElementById('estado-card-' + a.user_id);
        if (elEstado) {
            elEstado.className   = 'inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ' + a.estado.clase;
            elEstado.textContent = a.estado.texto;
        }

        const tdAccionCard = document.getElementById('td-accion-card-' + a.user_id);
        if (tdAccionCard) {
            tdAccionCard.innerHTML = htmlBtnForzarSalida(a.user_id, a.user_name, a.turno_terminado, a.sin_registro);
        }
    }

    function renderTarjetasVacio(contenedor) {
        contenedor.innerHTML = `
            <p id="tarjetas-vacio" class="text-center text-gray-500 dark:text-gray-400 py-4 mb-0">
                <ion-icon name="time-outline" class="text-2xl block mb-2"></ion-icon>
                No existen asistencias activas actualmente
            </p>`;
    }

    // =========================================================================
    // SINCRONIZACIÓN CON EL SERVIDOR
    // =========================================================================
    function sincronizar() {
        fetch(window.RUTAS.tiempos)
            .then(r => r.json())
            .then(data => {
                const usuariosServidor = [];
                const tbody            = document.getElementById('tabla-asistencias');
                const vacioTabla       = document.getElementById('tabla-vacia');
                if (vacioTabla && data.length > 0) vacioTabla.remove();

                const contenedorCards = document.getElementById('tarjetas-asistencias');
                const vacioCards      = document.getElementById('tarjetas-vacio');
                if (vacioCards && data.length > 0) vacioCards.remove();

                const ahora = Date.now();

                data.forEach(a => {
                    usuariosServidor.push(String(a.user_id));

                    const prev = estadoAsistencias[a.user_id];
                    let baseTime   = ahora;
                    let pTrabajado = a.trabajado_segundos;
                    let pPausas    = a.pausas_segundos;

                    // Anti-jumping: si el estado no cambió, mantenemos base local
                    if (prev && prev.enPausa === a.en_pausa && prev.turnoTerminado === a.turno_terminado) {
                        const deltaPrev         = (ahora - prev.lastSync) / 1000;
                        const trabajadoEstimado = prev.baseTrabajado + (!a.en_pausa ? deltaPrev : 0);
                        if (Math.abs(trabajadoEstimado - a.trabajado_segundos) < 5) {
                            baseTime   = prev.lastSync;
                            pTrabajado = prev.baseTrabajado;
                            pPausas    = prev.basePausas;
                        }
                    }

                    estadoAsistencias[a.user_id] = {
                        baseTrabajado:  pTrabajado,
                        basePausas:     pPausas,
                        lastSync:       baseTime,
                        enPausa:        a.en_pausa,
                        turnoTerminado: a.turno_terminado,
                        sinRegistro:    a.sin_registro,
                        userName:       a.user_name,
                    };

                    const deltaCalculado = (ahora - baseTime) / 1000;
                    a.trabajado_segundos = pTrabajado + (!a.en_pausa && !a.turnoTerminado && !a.sin_registro ? deltaCalculado : 0);
                    a.pausas_segundos    = pPausas    + ( a.en_pausa && !a.turnoTerminado && !a.sin_registro ? deltaCalculado : 0);

                    let fila = document.querySelector(`tr[data-user="${a.user_id}"]`);
                    if (!fila) tbody.appendChild(crearFila(a));
                    else        actualizarFila(a);

                    let tarjeta = document.querySelector(`[data-user-card="${a.user_id}"]`);
                    if (!tarjeta) contenedorCards.appendChild(crearTarjeta(a));
                    else          actualizarTarjeta(a);
                });

                // Limpiar usuarios que ya no aparecen
                tbody.querySelectorAll('tr[data-user]').forEach(fila => {
                    if (!usuariosServidor.includes(fila.dataset.user)) fila.remove();
                });
                contenedorCards.querySelectorAll('[data-user-card]').forEach(t => {
                    if (!usuariosServidor.includes(t.dataset.userCard)) t.remove();
                });
                Object.keys(estadoAsistencias).forEach(uid => {
                    if (!usuariosServidor.includes(String(uid))) delete estadoAsistencias[uid];
                });

                if (tbody.querySelectorAll('tr[data-user]').length === 0) renderTablaVacia(tbody);
                if (contenedorCards.querySelectorAll('[data-user-card]').length === 0) renderTarjetasVacio(contenedorCards);

                actualizarTarjetasResumen(data);
            })
            .catch(err => console.error('Error sincronizando asistencias:', err));
    }

    // =========================================================================
    // RELOJ LOCAL (entre polling)
    // =========================================================================
    function tick() {
        const ahora = Date.now();
        Object.keys(estadoAsistencias).forEach(userId => {
            const e = estadoAsistencias[userId];
            if (e.turnoTerminado || e.sinRegistro) return;
            const delta      = (ahora - e.lastSync) / 1000;
            const tTrabajado = e.baseTrabajado + (e.enPausa ? 0 : delta);
            const tPausas    = e.basePausas    + (e.enPausa ? delta : 0);
            actualizarTexto('pausas-'         + userId, 'bi-cup-hot',   formatoHMS(tPausas));
            actualizarTexto('trabajado-'      + userId, 'bi-stopwatch', formatoHMS(tTrabajado));
            actualizarTexto('pausas-card-'    + userId, 'bi-cup-hot',   formatoHMS(tPausas));
            actualizarTexto('trabajado-card-' + userId, 'bi-stopwatch', formatoHMS(tTrabajado));
        });
    }

    // =========================================================================
    // MODAL: FORZAR SALIDA
    // =========================================================================
    const modal        = document.getElementById('modalForzarSalida');
    const modalDialog  = modal ? modal.querySelector('.modal-dialog') : null;
    const btnConfirmar = document.getElementById('btnConfirmarForzarSalida');
    const modalNombre  = document.getElementById('forzarNombreBecario');
    const toast        = document.getElementById('toastForzarSalida');
    const toastIcono   = document.getElementById('toastForzarIcono');
    const toastMensaje = document.getElementById('toastForzarMensaje');

    let forzarUserId = null;
    let toastTimer   = null;

    function abrirModal(userId, nombre) {
        forzarUserId            = userId;
        modalNombre.textContent = nombre;
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modalDialog.classList.remove('scale-95');
        modalDialog.classList.add('scale-100');
    }

    function cerrarModal() {
        modal.classList.add('opacity-0', 'pointer-events-none');
        modalDialog.classList.remove('scale-100');
        modalDialog.classList.add('scale-95');
        forzarUserId = null;
    }

    function mostrarToast(ok, mensaje) {
        clearTimeout(toastTimer);
        if (ok) {
            toast.className = 'fixed bottom-5 right-5 z-[60] flex items-center gap-3 px-5 py-3.5 rounded-xl shadow-xl border text-sm font-medium transition-all duration-300 bg-green-50 dark:bg-green-900/40 border-green-200 dark:border-green-700 text-green-800 dark:text-green-300';
            toastIcono.setAttribute('name', 'checkmark-circle-outline');
        } else {
            toast.className = 'fixed bottom-5 right-5 z-[60] flex items-center gap-3 px-5 py-3.5 rounded-xl shadow-xl border text-sm font-medium transition-all duration-300 bg-red-50 dark:bg-red-900/40 border-red-200 dark:border-red-700 text-red-800 dark:text-red-300';
            toastIcono.setAttribute('name', 'alert-circle-outline');
        }
        toastMensaje.textContent = mensaje;
        toast.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-2');
        toastTimer = setTimeout(() => {
            toast.classList.add('opacity-0', 'pointer-events-none', 'translate-y-2');
        }, 3500);
    }

    // Abrir modal al hacer clic en el botón de la tabla/tarjeta
    document.addEventListener('click', e => {
        const btn = e.target.closest('.btn-forzar-salida');
        if (btn) {
            abrirModal(btn.dataset.userId, btn.dataset.userName);
            return;
        }
        // Cerrar modal con X o click fuera
        if (e.target.closest('.btn-cerrar-forzar') || e.target === modal) {
            cerrarModal();
        }
    });

    // Confirmar acción
    btnConfirmar && btnConfirmar.addEventListener('click', () => {
        if (!forzarUserId) return;

        btnConfirmar.disabled = true;

        fetch(`${window.RUTAS.forzarSalida}/${forzarUserId}`, {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.RUTAS.csrf,
            },
        })
        .then(r => r.json())
        .then(res => {
            cerrarModal();
            mostrarToast(res.ok, res.mensaje);
            if (res.ok) sincronizar();   // refresca la tabla inmediatamente
        })
        .catch(() => mostrarToast(false, 'Error de red. Intenta de nuevo.'))
        .finally(() => { btnConfirmar.disabled = false; });
    });

    // =========================================================================
    // POLLING Y ARRANQUE
    // =========================================================================
    function iniciarPolling() {
        clearInterval(intervaloSincronizacion);
        intervaloSincronizacion = setInterval(sincronizar, POLLING_MS);
    }

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) clearInterval(intervaloSincronizacion);
        else { sincronizar(); iniciarPolling(); }
    });

    sincronizar();
    iniciarPolling();
    setInterval(tick, TICK_MS);

})();
