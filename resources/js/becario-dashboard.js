// --- Helpers genéricos de modal ---
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        const dialog = modal.querySelector('.modal-dialog');

        modal.classList.remove('opacity-0', 'pointer-events-none');

        setTimeout(() => {
            if (dialog) {
                dialog.classList.remove('scale-95');
                dialog.classList.add('scale-100');
            }
        }, 10);
    };

    window.closeModal = function(modalId) {
        const modal = typeof modalId === 'string' ? document.getElementById(modalId) : modalId;
        if (!modal) return;

        const dialog = modal.querySelector('.modal-dialog');

        if (dialog) {
            dialog.classList.remove('scale-100');
            dialog.classList.add('scale-95');
        }

        modal.classList.add('opacity-0');

        setTimeout(() => {
            modal.classList.add('pointer-events-none');
        }, 300);
    };

    // --- Collapse animado del menú de pausa (JS plano, con altura + opacidad) ---
    window.togglePausaMenu = function() {
        const el = document.getElementById('pausaMenu');
        const chevron = document.getElementById('pausaChevron');
        if (!el) return;

        const cerrado = el.classList.contains('max-h-0');

        if (cerrado) {
            el.classList.remove('max-h-0', 'opacity-0');
            el.classList.add('max-h-[320px]', 'opacity-100');
            if (chevron) chevron.classList.add('rotate-180');
        } else {
            el.classList.remove('max-h-[320px]', 'opacity-100');
            el.classList.add('max-h-0', 'opacity-0');
            if (chevron) chevron.classList.remove('rotate-180');
        }
    };

    document.addEventListener('DOMContentLoaded', () => {

        // --- Entrada escalonada de las tarjetas principales ---
        document.querySelectorAll('.entrada').forEach((el, i) => {
            if (!el.style.animationDelay) {
                el.style.animationDelay = `${i * 0.08}s`;
            }
        });

        // --- Notificaciones: se muestran solas y desaparecen a los 3s ---
        document.querySelectorAll('[data-toast]').forEach((toastEl) => {
            const cerrarToast = () => {
                toastEl.style.transition = 'opacity 0.3s ease';
                toastEl.style.opacity = '0';
                setTimeout(() => toastEl.remove(), 300);
            };

            const btnCerrar = toastEl.querySelector('[data-toast-close]');
            if (btnCerrar) btnCerrar.addEventListener('click', cerrarToast);

            setTimeout(cerrarToast, 3000);
        });

        // --- Reloj analógico + digital, formato 12h con AM/PM ---
        function actualizarReloj() {
            const ahora = new Date();
            const horas24 = ahora.getHours();
            const minutos = ahora.getMinutes();
            const segundos = ahora.getSeconds();

            const gradosHora = (horas24 % 12) * 30 + minutos * 0.5;
            const gradosMin = minutos * 6;
            const gradosSeg = segundos * 6;

            const manecillaHora = document.getElementById('reloj-hora');
            const manecillaMin = document.getElementById('reloj-min');
            const manecillaSeg = document.getElementById('reloj-seg');
            if (manecillaHora) manecillaHora.style.transform = `rotate(${gradosHora}deg)`;
            if (manecillaMin) manecillaMin.style.transform = `rotate(${gradosMin}deg)`;
            if (manecillaSeg) manecillaSeg.style.transform = `rotate(${gradosSeg}deg)`;

            let horas12 = horas24 % 12;
            horas12 = horas12 === 0 ? 12 : horas12;
            const meridiano = horas24 >= 12 ? 'PM' : 'AM';

            const digital = document.getElementById('reloj-digital');
            if (digital) {
                digital.innerHTML = `${String(horas12).padStart(2, '0')}:${String(minutos).padStart(2, '0')}:${String(segundos).padStart(2, '0')} <span class="text-lg sm:text-xl text-blue-500">${meridiano}</span>`;
            }

            const fecha = document.getElementById('reloj-fecha');
            if (fecha) {
                fecha.textContent = ahora.toLocaleDateString('es-ES', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            }
        }

        // --- Tiempos de trabajo / pausa en vivo ---
        function formatearDuracion(totalSegundos) {
            const h = String(Math.floor(totalSegundos / 3600)).padStart(2, '0');
            const m = String(Math.floor((totalSegundos % 3600) / 60)).padStart(2, '0');
            const s = String(Math.floor(totalSegundos % 60)).padStart(2, '0');
            return `${h}:${m}:${s}`;
        }

        function calcularTiempos() {
            const cfg = window.checadorConfig || {};
            const ahora = Date.now();
            let segundosTrabajados = 0;
            let segundosPausados = Number(cfg.segundosPausaAcumulados) || 0;

            const inicio = cfg.horaEntrada ? new Date(cfg.horaEntrada).getTime() : null;

            if (inicio && !isNaN(inicio)) {
                if (cfg.estado === 'pausado' && cfg.pausaInicio) {
                    const pausaInicio = new Date(cfg.pausaInicio).getTime();
                    if (!isNaN(pausaInicio)) {
                        segundosTrabajados = Math.floor((pausaInicio - inicio) / 1000) - segundosPausados;
                        segundosPausados += Math.floor((ahora - pausaInicio) / 1000);
                    }
                } else if (cfg.estado === 'terminado') {
                    const finRaw = cfg.horaSalida ? new Date(cfg.horaSalida).getTime() : ahora;
                    const fin = isNaN(finRaw) ? ahora : finRaw;
                    segundosTrabajados = Math.floor((fin - inicio) / 1000) - segundosPausados;
                } else if (cfg.estado === 'trabajando') {
                    segundosTrabajados = Math.floor((ahora - inicio) / 1000) - segundosPausados;
                }
            }

            segundosTrabajados = Math.max(0, segundosTrabajados || 0);
            segundosPausados = Math.max(0, segundosPausados || 0);

            const elTrabajado = document.getElementById('tiempoTrabajado');
            const elPausa = document.getElementById('tiempoPausa');
            if (elTrabajado) elTrabajado.textContent = formatearDuracion(segundosTrabajados);
            if (elPausa) elPausa.textContent = formatearDuracion(segundosPausados);
        }

        actualizarReloj();
        calcularTiempos();
        setInterval(() => {
            actualizarReloj();
            calcularTiempos();
        }, 1000);


        // ==========================================
        // EVENTOS DE LOS MODALES (ACCIONES)
        // ==========================================

        const btnConfirmarPausa = document.getElementById('confirmarFinalizarPausa');
        if (btnConfirmarPausa) {
            btnConfirmarPausa.addEventListener('click', () => {
                document.getElementById('formFinalizarPausa').submit();
            });
        }

        const btnConfirmarSalida = document.getElementById('confirmarSalida');
        if (btnConfirmarSalida) {
            btnConfirmarSalida.addEventListener('click', () => {
                document.getElementById('formSalida').submit();
            });
        }


        // ==========================================
        // EVENTOS PARA CERRAR MODALES
        // ==========================================

        document.querySelectorAll('.btn-close-modal').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const modal = e.target.closest('[role="dialog"]');
                if (modal) window.closeModal(modal);
            });
        });

        document.querySelectorAll('[role="dialog"]').forEach(modal => {
            modal.addEventListener('mousedown', (e) => {
                if (e.target === modal) {
                    window.closeModal(modal);
                }
            });
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const modalesAbiertos = document.querySelectorAll('[role="dialog"]:not(.opacity-0)');
                modalesAbiertos.forEach(modal => window.closeModal(modal));
            }
        });

    });

    // ==========================================
    // DETECCIÓN DE INACTIVIDAD
    // =========================================================================
    // PING PERIÓDICO + NOTIFICACIÓN DEL SISTEMA
    // =========================================================================
    // Pregunta "¿sigues trabajando?" cada 60 min SIN importar si hay actividad
    // en el navegador — salta igual si estás en VS Code, Word o cualquier app.
    // =========================================================================
    document.addEventListener('DOMContentLoaded', function () {

        const cfg = window.checadorConfig || {};
        if (cfg.estado !== 'trabajando') return;

        const PING_MS  = 60 * 60 * 1000;
        const CLOSE_MS =  5 * 60 * 1000;

        const modal      = document.getElementById('modalInactividad');
        const cuenta     = document.getElementById('cuentaRegresivaInactividad');
        const btnSigo    = document.getElementById('btnSigoTrabajando');
        const formSalida = document.getElementById('formSalidaInactividad');

        if (!modal || !cuenta || !btnSigo || !formSalida) {
            console.warn('[Checador] Faltan elementos del modal de inactividad.');
            return;
        }

        let pingTimer      = null;
        let intervalDisplay = null;
        let cuentaFin      = null;
        let intervalSonido = null;

        // Audio (iOS necesita desbloqueo en primer toque)
        let audioCtx = null;
        function obtenerAudioCtx() {
            if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            if (audioCtx.state === 'suspended') audioCtx.resume();
            return audioCtx;
        }
        function desbloquearAudio() { obtenerAudioCtx(); }
        document.addEventListener('touchstart', desbloquearAudio, { once: true, passive: true });
        document.addEventListener('mousedown',  desbloquearAudio, { once: true });

        function reproducirAlerta() {
            try {
                const ctx = obtenerAudioCtx();
                [0, 0.25].forEach(function(offset) {
                    var osc = ctx.createOscillator(), gain = ctx.createGain();
                    osc.connect(gain); gain.connect(ctx.destination);
                    osc.type = 'sine'; osc.frequency.value = 880;
                    var t = ctx.currentTime + offset;
                    gain.gain.setValueAtTime(0, t);
                    gain.gain.linearRampToValueAtTime(0.35, t + 0.01);
                    gain.gain.linearRampToValueAtTime(0,    t + 0.18);
                    osc.start(t); osc.stop(t + 0.2);
                });
            } catch(e) { console.warn('[Checador] Web Audio no disponible:', e); }
        }
        function iniciarSonidoRepetido() {
            reproducirAlerta();
            intervalSonido = setInterval(reproducirAlerta, 30000);
        }
        function detenerSonido() { clearInterval(intervalSonido); intervalSonido = null; }

        // Notificación del sistema (aparece aunque el navegador esté minimizado)
        function pedirPermisoNotificaciones() {
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }
        }
        setTimeout(pedirPermisoNotificaciones, 2000);
        document.addEventListener('click',      pedirPermisoNotificaciones, { once: true });
        document.addEventListener('touchstart', pedirPermisoNotificaciones, { once: true, passive: true });

        function enviarNotificacion() {
            if (!('Notification' in window) || Notification.permission !== 'granted') return;
            new Notification('Checador — Ollintem', {
                body: 'Han pasado 60 minutos. Tienes 5 min para confirmar que sigues trabajando.',
                icon: '/favicon.ico',
                tag:  'checador-ping',
                requireInteraction: true,
            });
        }

        // Abrir / cerrar modal
        function mostrarModal() {
            window.openModal('modalInactividad');
            iniciarCuentaRegresiva();
            iniciarSonidoRepetido();
            enviarNotificacion();
        }
        function ocultarModal() {
            window.closeModal('modalInactividad');
            detenerCuentaRegresiva();
            detenerSonido();
        }

        // Cuenta regresiva
        function formatearCuenta(ms) {
            var s = Math.max(0, Math.ceil(ms / 1000));
            return String(Math.floor(s / 60)).padStart(2, '0') + ':' + String(s % 60).padStart(2, '0');
        }
        function iniciarCuentaRegresiva() {
            cuentaFin = Date.now() + CLOSE_MS;
            cuenta.textContent = formatearCuenta(CLOSE_MS);
            intervalDisplay = setInterval(function() {
                var restante = cuentaFin - Date.now();
                cuenta.textContent = formatearCuenta(restante);
                if (restante <= 0) { detenerCuentaRegresiva(); detenerSonido(); formSalida.submit(); }
            }, 1000);
        }
        function detenerCuentaRegresiva() { clearInterval(intervalDisplay); intervalDisplay = null; }

        // Ping periódico fijo — NO depende de eventos de teclado/ratón
        function programarPing() {
            clearTimeout(pingTimer);
            pingTimer = setTimeout(mostrarModal, PING_MS);
        }

        btnSigo.addEventListener('click', function() {
            ocultarModal();
            programarPing();
        });

        programarPing();

    }); // fin DOMContentLoaded



// ============================================================
// AVISO AL CERRAR LA VENTANA con jornada activa
// ============================================================
// Si el becario intenta cerrar la pestaña o el navegador con
// una jornada en curso, el browser muestra un diálogo nativo
// de confirmación ("¿Seguro que quieres salir?").
//
// LIMITACIÓN CONOCIDA: los navegadores modernos no muestran el
// mensaje personalizado — siempre muestran el suyo propio, pero
// sí bloquean el cierre hasta que el usuario confirme.
// Solo se activa si hay jornada activa (estado 'trabajando' o 'pausado').
// ============================================================
(function registrarAvisoSalida() {
    const cfg = window.checadorConfig || {};
    if (cfg.estado !== 'trabajando' && cfg.estado !== 'pausado') return;

    window.addEventListener('beforeunload', function (e) {
        e.preventDefault();
        // Chrome requiere returnValue para mostrar el diálogo.
        e.returnValue = 'Tienes una jornada activa. ¿Seguro que quieres cerrar el checador?';
        return e.returnValue;
    });
})();


// ============================================================
// NOTIFICACIONES DEL SISTEMA — permiso al cargar
// ============================================================
// El bloque del ping periódico ya usa Notification, pero el
// permiso se pide en el primer clic. Aquí lo pedimos también
// en DOMContentLoaded para que en la primera carga salga el
// diálogo del navegador y el usuario lo autorice de una vez,
// sin tener que esperar 60 minutos a que llegue el primer ping.
// ============================================================
document.addEventListener('DOMContentLoaded', function () {
    const cfg = window.checadorConfig || {};
    // Solo pedir si hay jornada activa y el browser soporta notificaciones.
    if (cfg.estado !== 'trabajando' && cfg.estado !== 'pausado') return;
    if (!('Notification' in window)) return;
    if (Notification.permission === 'default') {
        // Pequeño delay para no chocar con el diálogo de carga de la página.
        setTimeout(() => Notification.requestPermission(), 2000);
    }
});


// ============================================================
// POLLING DE ESTADO — detecta si el admin cerró la jornada
// ============================================================
// Cada 30 segundos consulta /api/estado-jornada. Si el servidor
// reporta 'terminado' y el estado local era 'trabajando' o 'pausado',
// recarga la página para que el becario vea su jornada cerrada.
// ============================================================
(function pollingEstadoJornada() {
    const cfg = window.checadorConfig || {};

    // Solo aplica si hay jornada activa
    if (cfg.estado !== 'trabajando' && cfg.estado !== 'pausado') return;

    const INTERVALO_MS = 30 * 1000; // cada 30 segundos

    function verificarEstado() {
        fetch('/api/estado-jornada', {
            headers: { 'Accept': 'application/json' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            // Si el servidor dice 'terminado' pero localmente seguíamos activos
            // significa que el admin forzó la salida — recargar la página.
            if (data.estado === 'terminado') {
                window.location.reload();
            }
        })
        .catch(function() {
            // Error de red — ignorar silenciosamente y reintentar en el siguiente ciclo
        });
    }

    setInterval(verificarEstado, INTERVALO_MS);
})();