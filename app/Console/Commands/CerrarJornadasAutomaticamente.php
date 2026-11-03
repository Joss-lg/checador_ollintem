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
        $hoy     = now()->toDateString();
        $horaFin = Asistencia::horaFinJornada();

        // Cierra las de hoy y cualquier jornada olvidada de días anteriores,
        // junto con sus pausas abiertas.
        $afectados = Asistencia::cerrarAbiertas($hoy, $horaFin);

        $this->info("Se cerraron automáticamente {$afectados} jornadas a las {$horaFin}.");
    }
}
