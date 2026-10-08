<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pausa extends Model
{
    protected $fillable = [
        'user_id',
        'asistencia_id',
        'inicio_pausa',
        'fin_pausa',
        'motivo',
        'fecha',
    ];

    protected $appends = [
        'duracion_formato',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function duracion()
    {
        if (!$this->inicio_pausa) {
            return 0;
        }

        // Usar la fecha del registro para que pausas que cruzan medianoche
        // no devuelvan segundos negativos o incorrectos.
        $fecha  = $this->fecha ?? now()->toDateString();
        $inicio = Carbon::parse($fecha . ' ' . $this->inicio_pausa);

        if ($this->fin_pausa) {
            $fin = Carbon::parse($fecha . ' ' . $this->fin_pausa);
            // Si fin < inicio asumimos que cruzó medianoche
            if ($fin->lt($inicio)) {
                $fin->addDay();
            }
        } else {
            $fin = now();
        }

        return max(0, $inicio->diffInSeconds($fin));
    }

    public function getDuracionFormatoAttribute()
    {
        return gmdate('H:i:s', $this->duracion());
    }

    public function asistencia()
    {
        return $this->belongsTo(Asistencia::class, 'asistencia_id');
    }
}
