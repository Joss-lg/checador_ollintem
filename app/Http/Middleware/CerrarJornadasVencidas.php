<?php

namespace App\Http\Middleware;

use App\Models\Asistencia;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cierra automáticamente las jornadas que se quedaron abiertas pasada la
 * hora de corte, sin depender de un cron real en el servidor.
 *
 * En vez de un comando programado que necesita `schedule:run` corriendo
 * cada minuto vía cron/Programador de tareas, este middleware aprovecha
 * el tráfico normal de la app: cada vez que un usuario autenticado visita
 * el sitio después del corte, revisa (como mucho una vez por minuto, usando
 * cache) si hay jornadas del día que sigan abiertas y las cierra.
 *
 * La hora de corte se lee de config/asistencia.php para que haya un único
 * lugar donde cambiarla (antes estaba hardcodeada aquí, en AsistenciaController
 * y en el comando CerrarJornadasAutomaticamente).
 */
class CerrarJornadasVencidas
{
    public function handle(Request $request, Closure $next): Response
    {
        // Solo ejecutar si hay una sesión autenticada: evita lanzar el UPDATE
        // en requests públicos (login, assets, etc.) después de las 18:00.
        if (Auth::check()) {
            $this->cerrarJornadasSiCorresponde();
        }

        return $next($request);
    }

    private function cerrarJornadasSiCorresponde(): void
    {
        $ahora    = now();
        $hoy      = $ahora->toDateString();
        $horaCorte = config('asistencia.hora_fin_jornada', '18:00:00');
        $corte    = Carbon::today()->setTimeFromTimeString($horaCorte);

        // Comparación real de instancias Carbon, no de strings.
        if ($ahora->lt($corte)) {
            return;
        }

        // Throttle: como mucho una vez por minuto y por día, para no lanzar
        // el UPDATE en cada request. Si en ese minuto no hay nada que
        // cerrar, el UPDATE simplemente afecta 0 filas y no pasa nada.
        $llave = 'cierre-jornadas:' . $hoy . ':' . $ahora->format('H:i');

        if (Cache::has($llave)) {
            return;
        }

        Cache::put($llave, true, now()->addMinutes(2));

        Asistencia::where('fecha', $hoy)
            ->whereNull('hora_salida')
            ->update(['hora_salida' => $horaCorte]);
    }
}