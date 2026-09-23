<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Facades\Auth;

/**
 * Segunda capa de seguridad a nivel de controlador, además del
 * middleware `role:admin` ya aplicado a las rutas de administración.
 * Antes este mismo método estaba copiado y pegado en AdminController,
 * HomeController y HistorialController; ahora vive en un solo lugar.
 */
trait AuthorizesAdmin
{
    protected function authorizeAdmin(): void
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Acceso no autorizado.');
        }
    }
}
