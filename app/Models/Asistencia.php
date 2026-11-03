<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
     * Cierre masivo de jornadas abiertas con fecha <= $hastaFecha
     * (usado por el comando programado y el middleware de cierre).
     */
    public static function cerrarAbiertas(string $hastaFecha, string $horaSalida): int
    {
        $ids = static::whereNull('hora_salida')
            ->where('fecha', '<=', $hastaFecha)
            ->pluck('id');

        if ($ids->isEmpty()) {
            return 0;
        }

        Pausa::whereIn('asistencia_id', $ids)
            ->whereNull('fin_pausa')
            ->update(['fin_pausa' => $horaSalida]);

        return static::whereIn('id', $ids)->update(['hora_salida' => $horaSalida]);
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

    public function tiempoTrabajado()
    {
        if (!$this->hora_entrada) {
           return 0;
        }

        $entrada = Carbon::parse($this->fecha . ' ' . $this->hora_entrada);
        $salida  = $this->finReferencia();

        $totalBruto = max(0, (int) $entrada->diffInSeconds($salida, false));
        $pausas = $this->tiempoPausasSegundos();
        
        return max(0, $totalBruto - $pausas);
    }

    public function formatoTiempo($segundos)
    {
        return gmdate("H:i:s", $segundos);
    }

    public function tiempoPausasSegundos()
    {
        $segundos = 0;
        foreach ($this->pausas as $pausa) {
            if (!$pausa->inicio_pausa) {
                continue;
            }
            $inicio = Carbon::parse($this->fecha . ' ' . $pausa->inicio_pausa);
            $fin = $pausa->fin_pausa
                ? Carbon::parse($this->fecha . ' ' . $pausa->fin_pausa)
                : $this->finReferencia();
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
     * Filtra por número de mes (1-12) de `fecha`.
     */
    public function scopeFiltrarPorMes($query, $mes)
    {
        if (!$mes) {
            return $query;
        }

        return $query->whereMonth('fecha', $mes);
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

        return $query->whereHas('user', function ($q) use ($buscar) {
            $q->where('name', 'like', "%{$buscar}%")
              ->orWhere('email', 'like', "%{$buscar}%");
        });
    }
}