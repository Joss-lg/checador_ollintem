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

    // Hora de corte de jornada (debe coincidir con HORA_FIN_JORNADA en AsistenciaController
    // y con la hora a la que cierra el comando asistencias:cerrar-automatico).
    private const HORA_FIN_JORNADA = '18:00:00';

    public function tiempoTrabajado()
    {
        if (!$this->hora_entrada) {
           return 0;
        }
        
        $entrada = Carbon::parse($this->fecha . ' ' . $this->hora_entrada);

        if ($this->hora_salida) {
            $salida = Carbon::parse($this->fecha . ' ' . $this->hora_salida);
        } else {
            // Jornada aún abierta: si ya pasamos la hora de corte del día de la
            // asistencia, no seguimos sumando con now() (eso infla el tiempo
            // mientras el cierre automático no haya corrido todavía). Topamos
            // en la hora de corte; si aún no llega, sí usamos la hora actual.
            $corte = Carbon::parse($this->fecha . ' ' . self::HORA_FIN_JORNADA);
            $ahora = now();
            $salida = $ahora->gt($corte) ? $corte : $ahora;
        }

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

    public function tienePausaActiva()
    {
        return $this->pausas->contains(function ($pausa) {
            return is_null($pausa->fin_pausa);
        });
    }
}