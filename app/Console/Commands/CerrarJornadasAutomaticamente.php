<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asistencia;

class CerrarJornadasAutomaticamente extends Command
{
    protected $signature   = 'asistencias:cerrar-automatico';
    protected $description = 'Marca la salida automáticamente para jornadas del día que no se hayan cerrado.';

    public function handle()
    {
        $hoy       = now()->toDateString();
        $horaCorte = config('asistencia.hora_corte_auto', '18:01:00');

        $afectados = Asistencia::where('fecha', $hoy)
            ->whereNull('hora_salida')
            ->update(['hora_salida' => $horaCorte]);

        $this->info("Se cerraron automáticamente {$afectados} jornadas a las {$horaCorte}.");
    }
}
