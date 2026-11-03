<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class Asistencia extends Model
{
    protected $fillable = [
        'user_id',
        'hora_entrada',
        'hora_salida',
        'fecha',
        'motivo_correccion',
        'corregido_por',
        'corregido_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hora oficial de fin de jornada (config/asistencia.php). Es la hora que
     * se guarda como salida en cualquier cierre automático.
     */
    public static function horaFinJornada(): string
    {
        return config('asistencia.hora_fin_jornada', '18:00:00');
    }

    /**
     * Cierra la jornada: primero sus pausas abiertas y luego la salida,
     * ambas con la misma hora para que no queden pausas colgadas.
     */
    public function cerrar(string $horaSalida): void
    {
        $this->pausas()
            ->whereNull('fin_pausa')
            ->update(['fin_pausa' => $horaSalida]);

        $this->update(['hora_salida' => $horaSalida]);
    }

    /**
     * Guarda la última vez que el panel del becario se comunicó con el
     * servidor (cada consulta automática del panel). Se usa para que el
     * cierre automático no cuente como trabajadas las horas en las que el
     * becario ya había cerrado la pestaña o el navegador.
     */
    public static function registrarActividad(int $userId): void
    {
        Cache::put(
            self::llaveActividad($userId, now()->toDateString()),
            now()->format('H:i:s'),
            now()->addDays(2)
        );
    }

    private static function llaveActividad(int $userId, string $fecha): string
    {
        return "actividad:{$userId}:{$fecha}";
    }

    /**
     * Última hora ("H:i:s") en que el panel del becario avisó que seguía
     * abierto el día de esta jornada, o null si no hay registro.
     */
    public function ultimaActividad(): ?string
    {
        return Cache::get(self::llaveActividad($this->user_id, $this->fecha));
    }

    /**
     * Hora de salida para un cierre automático: la última actividad del
     * becario ese día si fue antes de la hora fin; si no hay registro, si es
     * posterior o si quedó dentro del margen final (config
     * asistencia.margen_cierre_minutos), la hora fin oficial.
     */
    public function horaCierreAutomatico(): string
    {
        $horaFin = self::horaFinJornada();
        $visto   = $this->ultimaActividad();

        if (!$visto || ($this->hora_entrada && $visto < $this->hora_entrada)) {
            return $horaFin;
        }

        // Con la pestaña oculta el panel avisa cada 5 min: un último aviso a
        // pocos minutos de la hora fin cuenta como que trabajó hasta el final.
        $margen      = (int) config('asistencia.margen_cierre_minutos', 10);
        $inicioMargen = Carbon::createFromTimeString($horaFin)->subMinutes($margen)->format('H:i:s');

        // Las horas "H:i:s" se pueden comparar como texto
        return $visto < $inicioMargen ? $visto : $horaFin;
    }

    /**
     * Cierra la jornada con la hora de cierre automático y la devuelve.
     */
    public function cerrarAutomaticamente(): string
    {
        $hora = $this->horaCierreAutomatico();
        $this->cerrar($hora);

        return $hora;
    }

    /**
     * Cierre masivo de jornadas abiertas con fecha <= $hastaFecha
     * (usado por el comando programado y el middleware de cierre). Cada
     * jornada se cierra con su propia hora (ver horaCierreAutomatico()).
     */
    public static function cerrarAbiertas(string $hastaFecha): int
    {
        $abiertas = static::whereNull('hora_salida')
            ->where('fecha', '<=', $hastaFecha)
            ->get();

        $abiertas->each->cerrarAutomaticamente();

        return $abiertas->count();
    }

    /**
     * Momento que se toma como "fin" de una jornada o pausa sin cerrar:
     * la salida registrada, o ahora si es de hoy, o la hora fin oficial si
     * es de un día anterior (para que los días viejos no crezcan sin límite).
     */
    private function finReferencia(): Carbon
    {
        if ($this->hora_salida) {
            return Carbon::parse($this->fecha . ' ' . $this->hora_salida);
        }

        return $this->fecha === now()->toDateString()
            ? now()
            : Carbon::parse($this->fecha . ' ' . self::horaFinJornada());
    }

    /**
     * @param Carbon|null $hasta  Fin a usar para una jornada abierta en vez de
     *                            "ahora" (p. ej. la última actividad del becario).
     */
    public function tiempoTrabajado(?Carbon $hasta = null)
    {
        if (!$this->hora_entrada) {
           return 0;
        }

        $entrada = Carbon::parse($this->fecha . ' ' . $this->hora_entrada);
        $salida  = $hasta ?? $this->finReferencia();

        $totalBruto = max(0, (int) $entrada->diffInSeconds($salida, false));
        $pausas = $this->tiempoPausasSegundos(hasta: $hasta);
        
        return max(0, $totalBruto - $pausas);
    }

    public function formatoTiempo($segundos)
    {
        return gmdate("H:i:s", $segundos);
    }

    /**
     * @param bool $soloCerradas  true = ignora la pausa en curso (el panel
     *                            del becario la cuenta en vivo con JS).
     * @param Carbon|null $hasta  Fin de jornada a usar en vez de finReferencia().
     */
    public function tiempoPausasSegundos(bool $soloCerradas = false, ?Carbon $hasta = null)
    {
        // Solo cuenta la parte de cada pausa que cae dentro de la jornada
        // [entrada, salida]. Así, si el admin corrige la salida a una hora
        // anterior a una pausa, esa pausa ya no se resta del tiempo trabajado.
        $inicioJornada = $this->hora_entrada
            ? Carbon::parse($this->fecha . ' ' . $this->hora_entrada)
            : null;
        $finJornada = $hasta ?? $this->finReferencia();

        $segundos = 0;
        foreach ($this->pausas as $pausa) {
            if (!$pausa->inicio_pausa || ($soloCerradas && !$pausa->fin_pausa)) {
                continue;
            }
            $inicio = Carbon::parse($this->fecha . ' ' . $pausa->inicio_pausa);
            $fin = $pausa->fin_pausa
                ? Carbon::parse($this->fecha . ' ' . $pausa->fin_pausa)
                : $finJornada;

            if ($inicioJornada && $inicio->lt($inicioJornada)) {
                $inicio = $inicioJornada->copy();
            }
            if ($fin->gt($finJornada)) {
                $fin = $finJornada->copy();
            }

            $segundos += max(0, (int) $inicio->diffInSeconds($fin, false));
        }
        return $segundos;
    }

    public function tiempoPausas()
    {
        return $this->formatoTiempo($this->tiempoPausasSegundos());
    }

    public function pausas()
    {
        return $this->hasMany(Pausa::class, 'asistencia_id');
    }

    public function correcciones()
    {
        return $this->hasMany(CorreccionAsistencia::class)->latest();
    }

        public function tienePausaActiva()
    {
        return $this->pausas->contains(function ($pausa) {
            return is_null($pausa->fin_pausa);
        });
    }

    /**
     * Filtra por "semana del mes" (1 a 5), usando el día del mes de `fecha`.
     * Único lugar donde vive esta regla; antes estaba copiada en
     * HistorialController, ExcelController y PdfController.
     */
    public function scopeFiltrarPorSemana($query, $semana)
    {
        if (!$semana) {
            return $query;
        }

        return match ((int) $semana) {
            1 => $query->whereDay('fecha', '>=', 1)->whereDay('fecha', '<=', 7),
            2 => $query->whereDay('fecha', '>=', 8)->whereDay('fecha', '<=', 14),
            3 => $query->whereDay('fecha', '>=', 15)->whereDay('fecha', '<=', 21),
            4 => $query->whereDay('fecha', '>=', 22)->whereDay('fecha', '<=', 28),
            5 => $query->whereDay('fecha', '>=', 29),
            default => $query,
        };
    }

    /**
     * Filtra por mes de `fecha`. Acepta "YYYY-MM" (mes de un año concreto,
     * lo que manda el selector) o solo el número de mes 1-12 (links viejos).
     */
    public function scopeFiltrarPorMes($query, $mes)
    {
        if (!$mes) {
            return $query;
        }

        if (preg_match('/^(\d{4})-(\d{2})$/', $mes, $m)) {
            return $query->whereYear('fecha', (int) $m[1])
                ->whereMonth('fecha', (int) $m[2]);
        }

        return $query->whereMonth('fecha', (int) $mes);
    }

    /**
     * Filtra asistencias cuyo becario coincide con el término de búsqueda
     * (nombre o correo). Único lugar donde vive esta regla; antes estaba
     * repetida como whereHas en HistorialController y como where directo
     * (tras el join) en ExcelController/PdfController.
     */
    public function scopeBuscarBecario($query, $buscar)
    {
        if (!$buscar) {
            return $query;
        }

        // % y _ son comodines de LIKE: se escapan para buscar el texto literal
        $buscar = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $buscar);

        return $query->whereHas('user', function ($q) use ($buscar) {
            $q->where('name', 'like', "%{$buscar}%")
              ->orWhere('email', 'like', "%{$buscar}%");
        });
    }

    /**
     * Filtros del historial (búsqueda, semana y mes) en un solo lugar, para
     * que la pantalla, el PDF y el Excel generales devuelvan lo mismo.
     */
    public function scopeFiltrosHistorial($query, array $filtros)
    {
        return $query
            ->buscarBecario($filtros['search'] ?? null)
            ->filtrarPorSemana($filtros['semana'] ?? null)
            ->filtrarPorMes($filtros['mes'] ?? null);
    }

    /**
     * Orden del historial: az | za | oldest | recent (por defecto).
     */
    public function scopeOrdenar($query, ?string $orden)
    {
        switch ($orden) {
            case 'az':
            case 'za':
                return $query->join('users', 'users.id', '=', 'asistencias.user_id')
                    ->select('asistencias.*')
                    ->orderBy('users.name', $orden === 'az' ? 'asc' : 'desc')
                    ->orderBy('asistencias.fecha', 'desc');
            case 'oldest':
                return $query->orderBy('asistencias.fecha', 'asc')
                    ->orderBy('asistencias.hora_entrada', 'asc');
            default:
                return $query->orderBy('asistencias.fecha', 'desc')
                    ->orderBy('asistencias.hora_entrada', 'desc');
        }
    }
}