/**
 * Sistema de Control de Jornada (Checador - Ollintem)
 * Módulo Unificado de Interfaz, Reloj, Control Fijo de Inactividad (Web Worker) y Salida al Cerrar Pestaña
 */

// Variable global para evitar que sendBeacon se dispare en salidas manuales
window.esSalidaManual = false;

// ============================================================
// 1. HELPERS GLOBALES DE INTERFAZ (MODALES & MENÚS)
// ============================================================
window.openModal = function (modalId) {
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

window.closeModal = function (modalId) {
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

window.togglePausaMenu = function () {
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

// ============================================================
// 2. CICLO DE VIDA Y EVENTOS DE INTERFAZ
// ============================================================
document.addEventListener('DOMContentLoaded', () => {
    const cfg = window.checadorConfig || {};
    const estadoActual = String(cfg.estado || '').toLowerCase();

    // Marcar salida manual al enviar formularios de salida
    const formSalida = document.getElementById('formSalida');
    if (formSalida) {
        formSalida.addEventListener('submit', () => {
            window.esSalidaManual = true;
        });
    }

    const formSalidaInactividad = document.getElementById('formSalidaInactividad');
    if (formSalidaInactividad) {
        formSalidaInactividad.addEventListener('submit', () => {
            window.esSalidaManual = true;
        });
    }

    // --- Animación escalonada de tarjetas ---
    document.querySelectorAll('.entrada').forEach((el, i) => {
        if (!el.style.animationDelay) {
            el.style.animationDelay = `${i * 0.08}s`;
        }
    });

    // --- Notificaciones Toast (auto-cierre a los 3s) ---
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

    // --- Submisión de formularios en Modales ---
    const btnConfirmarPausa = document.getElementById('confirmarFinalizarPausa');
    if (btnConfirmarPausa) {
        btnConfirmarPausa.addEventListener('click', () => {
            document.getElementById('formFinalizarPausa')?.submit();
        });
    }

    const btnConfirmarSalida = document.getElementById('confirmarSalida');
    if (btnConfirmarSalida) {
        btnConfirmarSalida.addEventListener('click', () => {
            window.esSalidaManual = true;
            document.getElementById('formSalida')?.submit();
        });
    }

    // --- Cerrar modales con clic o tecla ESC ---
    document.querySelectorAll('.btn-close-modal').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            const modal = e.target.closest('[role="dialog"]');
            if (modal) window.closeModal(modal);
        });
    });

    document.querySelectorAll('[role="dialog"]').forEach((modal) => {
        modal.addEventListener('mousedown', (e) => {
            if (e.target === modal) window.closeModal(modal);
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('[role="dialog"]:not(.opacity-0)').forEach((modal) => {
                window.closeModal(modal);
            });
        }
    });

    // ============================================================
    // 3. RELOJ Y TIEMPOS EN VIVO (Trabajo y Pausa)
    // ============================================================
    function actualizarReloj() {
        const ahora = new Date();
        const horas24 = ahora.getHours();
        const minutos = ahora.getMinutes();
        const segundos = ahora.getSeconds();

        const manecillaHora = document.getElementById('reloj-hora');
        const manecillaMin = document.getElementById('reloj-min');
        const manecillaSeg = document.getElementById('reloj-seg');

        if (manecillaHora) manecillaHora.style.transform = `rotate(${(horas24 % 12) * 30 + minutos * 0.5}deg)`;
        if (manecillaMin) manecillaMin.style.transform = `rotate(${minutos * 6}deg)`;
        if (manecillaSeg) manecillaSeg.style.transform = `rotate(${segundos * 6}deg)`;

        const digital = document.getElementById('reloj-digital');
        if (digital) {
            let horas12 = horas24 % 12 || 12;
            const meridiano = horas24 >= 12 ? 'PM' : 'AM';
            digital.innerHTML = `${String(horas12).padStart(2, '0')}:${String(minutos).padStart(2, '0')}:${String(segundos).padStart(2, '0')} <span class="text-lg sm:text-xl text-blue-500">${meridiano}</span>`;
        }

        const fecha = document.getElementById('reloj-fecha');
        if (fecha) {
            fecha.textContent = ahora.toLocaleDateString('es-ES', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        }
    }

    function formatearDuracion(totalSegundos) {
        const h = String(Math.floor(totalSegundos / 3600)).padStart(2, '0');
        const m = String(Math.floor((totalSegundos % 3600) / 60)).padStart(2, '0');
        const s = String(Math.floor(totalSegundos % 60)).padStart(2, '0');
        return `${h}:${m}:${s}`;
    }

    function calcularTiempos() {
        const ahora = Date.now();
        let segundosTrabajados = 0;
        let segundosPausados = Number(cfg.segundosPausaAcumulados) || 0;
        const inicio = cfg.horaEntrada ? new Date(cfg.horaEntrada).getTime() : null;
        const est = String(cfg.estado || '').toLowerCase();

        if (inicio && !isNaN(inicio)) {
            if (est === 'pausado' && cfg.pausaInicio) {
                const pausaInicio = new Date(cfg.pausaInicio).getTime();
                if (!isNaN(pausaInicio)) {
                    segundosTrabajados = Math.floor((pausaInicio - inicio) / 1000) - segundosPausados;
                    segundosPausados += Math.floor((ahora - pausaInicio) / 1000);
                }
            } else if (est === 'terminado') {
                const finRaw = cfg.horaSalida ? new Date(cfg.horaSalida).getTime() : ahora;
                const fin = isNaN(finRaw) ? ahora : finRaw;
                segundosTrabajados = Math.floor((fin - inicio) / 1000) - segundosPausados;
            } else if (est === 'trabajando') {
                segundosTrabajados = Math.floor((ahora - inicio) / 1000) - segundosPausados;
            }
        }

        const elTrabajado = document.getElementById('tiempoTrabajado');
        const elPausa = document.getElementById('tiempoPausa');
        if (elTrabajado) elTrabajado.textContent = formatearDuracion(Math.max(0, segundosTrabajados));
        if (elPausa) elPausa.textContent = formatearDuracion(Math.max(0, segundosPausados));
    }

    // ============================================================
    // 4. CONTROL DE INACTIVIDAD FIJA (Web Worker + Timestamps)
    // ============================================================
    if (estadoActual === 'trabajando') {
        const PING_MS = 60 * 60 * 1000; // 60 Minutos
        const CLOSE_MS = 5 * 60 * 1000; // 5 Minutos de gracia

        const modal = document.getElementById('modalInactividad');
        const cuenta = document.getElementById('cuentaRegresivaInactividad');
        const btnSigo = document.getElementById('btnSigoTrabajando');
        const formSalida = document.getElementById('formSalidaInactividad');

        if (modal && cuenta && btnSigo && formSalida) {
            let intervalDisplay = null;
            let cuentaFin = null;
            let intervalSonido = null;
            let audioCtx = null;
            let proximoPingTimestamp = Date.now() + PING_MS;
            let modalAbierto = false;

            const workerBlob = new Blob([`
                let timer = null;
                self.onmessage = function(e) {
                    if (e.data.action === 'start') {
                        if (timer) clearInterval(timer);
                        timer = setInterval(() => self.postMessage({ action: 'tick' }), e.data.intervalo || 1000);
                    } else if (e.data.action === 'stop') {
                        if (timer) clearInterval(timer);
                    }
                };
            `], { type: 'application/javascript' });

            const worker = new Worker(URL.createObjectURL(workerBlob));

            function obtenerAudioCtx() {
                if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                if (audioCtx.state === 'suspended') audioCtx.resume();
                return audioCtx;
            }

            const desbloquearAudio = () => obtenerAudioCtx();
            document.addEventListener('touchstart', desbloquearAudio, { once: true, passive: true });
            document.addEventListener('mousedown', desbloquearAudio, { once: true });

            function reproducirAlerta() {
                try {
                    const ctx = obtenerAudioCtx();
                    [0, 0.25].forEach((offset) => {
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.connect(gain);
                        gain.connect(ctx.destination);
                        osc.type = 'sine';
                        osc.frequency.value = 880;
                        const t = ctx.currentTime + offset;
                        gain.gain.setValueAtTime(0, t);
                        gain.gain.linearRampToValueAtTime(0.35, t + 0.01);
                        gain.gain.linearRampToValueAtTime(0, t + 0.18);
                        osc.start(t);
                        osc.stop(t + 0.2);
                    });
                } catch (e) {
                    console.warn('[Checador] AudioContext no disponible:', e);
                }
            }

            function iniciarSonidoRepetido() {
                reproducirAlerta();
                intervalSonido = setInterval(reproducirAlerta, 30000);
            }

            function detenerSonido() {
                clearInterval(intervalSonido);
                intervalSonido = null;
            }

            function enviarNotificacion() {
                if ('Notification' in window && Notification.permission === 'granted') {
                    const notif = new Notification('Checador — Ollintem', {
                        body: 'Han pasado 60 minutos. Tienes 5 min para confirmar que sigues trabajando.',
                        icon: '/favicon.ico',
                        tag: 'checador-ping',
                        requireInteraction: true,
                    });

                    notif.onclick = function () {
                        window.focus();
                        notif.close();
                    };
                }
            }

            function formatearCuenta(ms) {
                const s = Math.max(0, Math.ceil(ms / 1000));
                return `${String(Math.floor(s / 60)).padStart(2, '0')}:${String(s % 60).padStart(2, '0')}`;
            }

            function iniciarCuentaRegresiva() {
                cuentaFin = Date.now() + CLOSE_MS;
                cuenta.textContent = formatearCuenta(CLOSE_MS);
                clearInterval(intervalDisplay);
                intervalDisplay = setInterval(() => {
                    const restante = cuentaFin - Date.now();
                    cuenta.textContent = formatearCuenta(restante);
                    if (restante <= 0) {
                        clearInterval(intervalDisplay);
                        detenerSonido();
                        window.esSalidaManual = true;
                        formSalida.submit();
                    }
                }, 1000);
            }

            function mostrarModal() {
                if (modalAbierto) return;
                modalAbierto = true;
                window.openModal('modalInactividad');
                iniciarCuentaRegresiva();
                iniciarSonidoRepetido();
                enviarNotificacion();
            }

            function ocultarModal() {
                modalAbierto = false;
                window.closeModal('modalInactividad');
                clearInterval(intervalDisplay);
                detenerSonido();
            }

            function programarPing() {
                proximoPingTimestamp = Date.now() + PING_MS;
            }

            function verificarPing() {
                if (!modalAbierto && Date.now() >= proximoPingTimestamp) {
                    mostrarModal();
                }
            }

            worker.onmessage = function (e) {
                if (e.data.action === 'tick') {
                    actualizarReloj();
                    calcularTiempos();
                    verificarPing();
                }
            };

            worker.postMessage({ action: 'start', intervalo: 1000 });

            btnSigo.addEventListener('click', () => {
                ocultarModal();
                programarPing();
            });

            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) {
                    actualizarReloj();
                    calcularTiempos();
                    verificarPing();
                }
            });

            programarPing();
        }
    } else {
        actualizarReloj();
        calcularTiempos();
        setInterval(() => {
            actualizarReloj();
            calcularTiempos();
        }, 1000);
    }

    if ((estadoActual === 'trabajando' || estadoActual === 'pausado') && 'Notification' in window) {
        if (Notification.permission === 'default') {
            setTimeout(() => Notification.requestPermission(), 2000);
        }
    }
});

// ============================================================
// 5. EVENTOS NATIVOS, POLLING Y SALIDA AL CERRAR PESTAÑA
// ============================================================

window.addEventListener('beforeunload', (e) => {
    const cfg = window.checadorConfig || {};
    const est = String(cfg.estado || '').toLowerCase();
    
    if (!window.esSalidaManual && (est === 'trabajando' || est === 'pausado')) {
        e.preventDefault();
        e.returnValue = 'Tienes una jornada activa. ¿Seguro que quieres salir?';
        return e.returnValue;
    }
});

function enviarSalidaInvoluntaria() {
    // Si la salida fue iniciada por el usuario dando clic en botón/formulario, abortamos
    if (window.esSalidaManual) return;

    const cfg = window.checadorConfig || {};
    const est = String(cfg.estado || '').toLowerCase();

    if (est === 'trabajando' || est === 'pausado') {
        const url = '/salida/involuntaria';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        const params = new URLSearchParams();
        if (csrfToken) {
            params.append('_token', csrfToken);
        }
        if (cfg.userId) {
            params.append('user_id', cfg.userId);
        }

        const blob = new Blob([params.toString()], {
            type: 'application/x-www-form-urlencoded; charset=UTF-8'
        });

        if (navigator.sendBeacon) {
            navigator.sendBeacon(url, blob);
        }
    }
}

// Se ejecuta SOLO cuando se destruye/cierra la pestaña o navegador
window.addEventListener('pagehide', () => {
    enviarSalidaInvoluntaria();
});

// Polling periódico para verificar si la jornada finalizó externamente
(function iniciarPollingJornada() {
    const INTERVALO_POLLING_MS = 30000;

    const intervalId = setInterval(async () => {
        const cfg = window.checadorConfig || {};
        const est = String(cfg.estado || '').toLowerCase();
        
        if (est !== 'trabajando' && est !== 'pausado') {
            return;
        }

        try {
            const res = await fetch('/api/estado-jornada', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!res.ok) return;

            const data = await res.json();

            if (data.terminado === true) {
                clearInterval(intervalId);
                window.location.reload();
            }
        } catch (e) {
            // Ignorar errores temporales de red
        }
    }, INTERVALO_POLLING_MS);
})();