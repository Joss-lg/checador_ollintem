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
    // ==========================================
    //
    // Flujo:
    //  1. El usuario lleva WARN_MINUTES sin mover el ratón, teclear ni hacer clic.
    //  2. Se abre el modal de inactividad con una cuenta regresiva de CLOSE_MINUTES.
    //  3a. Si pulsa "Sigo trabajando" → se cierra el modal y se reinicia el timer.
    //  3b. Si no responde antes de que llegue a 0 → se envía el form POST /salida/inactividad.
    //
    // Solo se activa si el estado es 'trabajando' (no en pausa, no al terminar).
    // ==========================================

    // El detector de inactividad se inicializa cuando el DOM ya está
    // completamente cargado. Sin esto, getElementById devuelve null
    // porque el script corre antes de que el navegador pinte el HTML.
    document.addEventListener('DOMContentLoaded', function () {

        const cfg = window.checadorConfig || {};

        // Solo tiene sentido cuando hay jornada activa sin pausa.
        if (cfg.estado !== 'trabajando') return;

        // ── Configuración de tiempos ──────────────────────────────────────
        // WARN_MS:  inactividad antes de mostrar el aviso     (60 min)
        // CLOSE_MS: cuenta regresiva antes de registrar salida  (5 min)
        const WARN_MS  = 60 * 60 * 1000;
        const CLOSE_MS =  5 * 60 * 1000;
        // ─────────────────────────────────────────────────────────────────

        const modal      = document.getElementById('modalInactividad');
        const cuenta     = document.getElementById('cuentaRegresivaInactividad');
        const btnSigo    = document.getElementById('btnSigoTrabajando');
        const formSalida = document.getElementById('formSalidaInactividad');

        // Si algún elemento del modal no está en el DOM algo falló en el Blade.
        if (!modal || !cuenta || !btnSigo || !formSalida) {
            console.warn('[Inactividad] No se encontraron los elementos del modal. Verifica que @include(\'becario.modals.inactividad\') esté en dashboard.blade.php.');
            return;
        }

        let timerInactividad = null;
        let intervalDisplay  = null;
        let cuentaFin        = null;

        // ── Sonido de alerta con Web Audio API ───────────────────────────
        // No necesita ningún archivo de audio externo: genera el tono
        // directamente en el navegador. Suena cuando aparece el modal
        // y cada 30 segundos mientras sigue abierto.
        //
        // iOS Safari bloquea el audio hasta que el usuario haya tocado
        // la pantalla al menos una vez. El truco es crear el AudioContext
        // en la primera interacción real (touchstart/click) y hacer un
        // resume() ahí mismo — eso lo "desbloquea" para el resto de la
        // sesión, incluso cuando el sonido lo dispara un timer sin toque.
        let audioCtx       = null;
        let intervalSonido = null;

        function obtenerAudioCtx() {
            if (!audioCtx) {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            // En iOS el contexto arranca en estado 'suspended' hasta el primer
            // gesto. resume() es necesario aquí para que los sonidos futuros
            // (disparados por timer, sin toque) funcionen.
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
            return audioCtx;
        }

        // Desbloquear en cuanto el usuario toque/haga clic por primera vez.
        function desbloquearAudio() {
            obtenerAudioCtx();
            // Una vez desbloqueado no necesitamos seguir escuchando.
            document.removeEventListener('touchstart', desbloquearAudio);
            document.removeEventListener('mousedown',  desbloquearAudio);
        }
        document.addEventListener('touchstart', desbloquearAudio, { once: true, passive: true });
        document.addEventListener('mousedown',  desbloquearAudio, { once: true });

        function reproducirAlerta() {
            try {
                const ctx = obtenerAudioCtx();

                // Dos pitidos cortos seguidos (bip-bip).
                [0, 0.25].forEach(offset => {
                    const osc  = ctx.createOscillator();
                    const gain = ctx.createGain();

                    osc.connect(gain);
                    gain.connect(ctx.destination);

                    osc.type            = 'sine';
                    osc.frequency.value = 880; // La5 — tono de aviso, no molesto

                    const t = ctx.currentTime + offset;
                    gain.gain.setValueAtTime(0, t);
                    gain.gain.linearRampToValueAtTime(0.35, t + 0.01);
                    gain.gain.linearRampToValueAtTime(0,    t + 0.18);

                    osc.start(t);
                    osc.stop(t + 0.2);
                });
            } catch (e) {
                console.warn('[Inactividad] Web Audio no disponible:', e);
            }
        }

        function iniciarSonidoRepetido() {
            reproducirAlerta(); // suena al abrir
            intervalSonido = setInterval(reproducirAlerta, 30_000); // cada 30 seg
        }

        function detenerSonido() {
            clearInterval(intervalSonido);
            intervalSonido = null;
        }

        // ── Abrir y cerrar modal ──────────────────────────────────────────
        function mostrarModal() {
            window.openModal('modalInactividad');
            iniciarCuentaRegresiva();
            iniciarSonidoRepetido();
        }

        function ocultarModal() {
            window.closeModal('modalInactividad');
            detenerCuentaRegresiva();
            detenerSonido();
        }

        // ── Cuenta regresiva ──────────────────────────────────────────────
        function formatearCuenta(ms) {
            const totalSeg = Math.max(0, Math.ceil(ms / 1000));
            const m = String(Math.floor(totalSeg / 60)).padStart(2, '0');
            const s = String(totalSeg % 60).padStart(2, '0');
            return `${m}:${s}`;
        }

        function iniciarCuentaRegresiva() {
            cuentaFin = Date.now() + CLOSE_MS;
            cuenta.textContent = formatearCuenta(CLOSE_MS);

            intervalDisplay = setInterval(() => {
                const restante = cuentaFin - Date.now();
                cuenta.textContent = formatearCuenta(restante);

                if (restante <= 0) {
                    detenerCuentaRegresiva();
                    detenerSonido();
                    formSalida.submit();
                }
            }, 1000);
        }

        function detenerCuentaRegresiva() {
            clearInterval(intervalDisplay);
            intervalDisplay = null;
        }

        // ── Timer principal ───────────────────────────────────────────────
        function reiniciarTimer() {
            clearTimeout(timerInactividad);
            timerInactividad = setTimeout(mostrarModal, WARN_MS);
        }

        // ── Eventos de actividad ──────────────────────────────────────────
        // mousemove tiene throttle de 5 s para no machacar el timer.
        let ultimoMousemove = 0;
        document.addEventListener('mousemove', () => {
            const ahora = Date.now();
            if (ahora - ultimoMousemove < 5_000) return;
            ultimoMousemove = ahora;
            reiniciarTimer();
        });

        ['keydown', 'mousedown', 'touchstart', 'scroll', 'click'].forEach(evt => {
            document.addEventListener(evt, reiniciarTimer, { passive: true });
        });

        // ── Botón "Sigo trabajando" ───────────────────────────────────────
        btnSigo.addEventListener('click', () => {
            ocultarModal();
            reiniciarTimer();
        });

        // ── Arrancar ──────────────────────────────────────────────────────
        reiniciarTimer();

    }); // fin DOMContentLoaded