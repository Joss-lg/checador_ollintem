<?php

namespace App\Http\Middleware;

use App\Models\Asistencia;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cierra automáticamente las jornadas que se quedaron abiertas pasada la
 * hora de corte (18:00), sin depender de un cron real en el servidor.
 *
 * En vez de un comando programado que necesita `schedule:run` corriendo
 * cada minuto vía cron/Programador de tareas, este middleware aprovecha
 * el tráfico normal de la app: cada vez que alguien visita el sitio
 * después de las 18:00, revisa (como mucho una vez por minuto, usando
 * cache) si hay jornadas del día que sigan abiertas y las cierra.
 */
class CerrarJornadasVencidas
{
    private const HORA_FIN_JORNADA = '18:00:00';

    public function handle(Request $request, Closure $next): Response
    {
        $this->cerrarJornadasSiCorresponde();

        return $next($request);
    }

    private function cerrarJornadasSiCorresponde(): void
    {
        $ahora = now();
        $hoy = $ahora->toDateString();

        // Antes de las 18:00 no hay nada que cerrar todavía.
        if ($ahora->format('H:i:s') < self::HORA_FIN_JORNADA) {
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
            ->update(['hora_salida' => self::HORA_FIN_JORNADA]);
    }
}