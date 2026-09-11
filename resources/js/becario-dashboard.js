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
                    // Tope a las 18:00 del día de la entrada: si ya pasó esa hora y el
                    // servidor todavía no cerró la jornada (cron pendiente), el contador
                    // no debe seguir subiendo indefinidamente.
                    const fechaEntrada = new Date(inicio);
                    const corte = new Date(fechaEntrada);
                    corte.setHours(18, 0, 0, 0);
                    const finEfectivo = ahora > corte.getTime() ? corte.getTime() : ahora;

                    segundosTrabajados = Math.floor((finEfectivo - inicio) / 1000) - segundosPausados;
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