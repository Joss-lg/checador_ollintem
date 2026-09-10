<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asistencia;

class CerrarJornadasAutomaticamente extends Command
{
    protected $signature = 'asistencias:cerrar-automatico';
    protected $description = 'Marca automáticamente la salida a las 18:01 para cualquier jornada activa del día que no se haya cerrado.';

    public function handle()
    {
        $hoy = now()->toDateString();
        
        // Actualiza todos los registros del día actual que siguen sin hora de salida
        $afectados = Asistencia::where('fecha', $hoy)
            ->whereNull('hora_salida')
            ->update(['hora_salida' => '18:01:00']);

        $this->info("Se cerraron automáticamente {$afectados} jornadas a las 18:01:00.");
    }
}