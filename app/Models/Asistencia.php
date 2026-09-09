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

    public function tienePausaActiva()
    {
        return $this->pausas->contains(function ($pausa) {
            return is_null($pausa->fin_pausa);
        });
    }
}