<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        // [BLOQUEO DE SEGURIDAD]: Validación estricta de autenticación
        // Si no hay usuario, abortar el acceso inmediatamente.
        if (!$request->user()) {
            return redirect('/login');
        }

        // [BLOQUEO DE SEGURIDAD]: Validación estricta de rol
        // Si el usuario existe pero su rol no coincide, en vez de mostrar un error 403,
        // lo redirigimos a SU propio dashboard según su rol actual. Así, si un becario
        // escribe manualmente una ruta de admin (o viceversa), es enviado de vuelta
        // a la vista que le corresponde en lugar de quedar atascado en una pantalla de error.
        if ($request->user()->role !== $role) {
            return redirect()->route(
                $request->user()->role === 'admin' ? 'admin.dashboard' : 'dashboard'
            );
        }

        return $next($request);
    }
}