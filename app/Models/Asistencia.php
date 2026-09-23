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
        'fecha'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tiempoTrabajado()
    {
        if (!$this->hora_entrada) {
           return 0;
        }
        
        $entrada = Carbon::parse($this->fecha . ' ' . $this->hora_entrada);
        
        $salida = $this->hora_salida
            ? Carbon::parse($this->fecha . ' ' . $this->hora_salida)
            : now();
            
        $totalBruto = $entrada->diffInSeconds($salida);
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
                : now();
            $segundos += $inicio->diffInSeconds($fin);
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

    /**
     * Devuelve las horas extra de esta jornada en formato HH:MM:SS.
     * Se considera jornada normal = 9 horas. Todo lo que pase de eso es extra.
     * Devuelve "00:00:00" si no hubo horas extra.
     */
    public function horasExtrasTotalFormato(): string
    {
        $jornadaNormal = 9 * 3600;
        $trabajado     = $this->tiempoTrabajado();
        $extra         = max(0, $trabajado - $jornadaNormal);

        $h = floor($extra / 3600);
        $m = floor(($extra % 3600) / 60);
        $s = $extra % 60;

        return sprintf('%02d:%02d:%02d', $h, $m, $s);
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