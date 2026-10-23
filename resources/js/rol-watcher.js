(function () {
    'use strict';

    const POLLING_ROL_MS = 30_000; // cada 30 s — el cambio de rol es infrecuente

    function verificarRol() {
        fetch('/api/rol-actual', {
            headers: { 'Accept': 'application/json' }
        })
            .then(res => {
                // Si el servidor redirige (por ejemplo, sesión inválida) o hay error, no explota nada.
                if (!res.ok) return null;
                return res.json();
            })
            .then(data => {
                if (!data || !data.role) return;

                if (window.ROL_ACTUAL && data.role !== window.ROL_ACTUAL) {
                    // El rol cambió respecto al que se renderizó en esta página: recargamos.
                    window.location.reload();
                }
            })
            .catch(() => {
                // Silencioso: si hay un fallo de red puntual, simplemente se reintenta en el siguiente ciclo.
            });
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (!window.ROL_ACTUAL) return; // si la vista no definió el rol actual, no hacemos nada
        setInterval(verificarRol, POLLING_ROL_MS);
    });
})();