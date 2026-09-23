<?php

namespace App\Services;

use App\Models\User;
use App\Models\Asistencia;

class ReporteService
{
    /**
     * Obtiene el reporte detallado de asistencias y pausas de un becario en un rango de fechas.
     */
    public function obtenerReporteBecario(
        User $user,
        $desde = null,
        $hasta = null
    ) {
        $query = Asistencia::query()
            ->with([
                'user',
                'pausas'
            ])
            ->where(
                'user_id',
                $user->id
            );

        if ($desde) {
            $query->whereDate('fecha', '>=', $desde);
        }

        if ($hasta) {
            $query->whereDate('fecha', '<=', $hasta);
        }

        return $query
            ->orderByDesc('fecha')
            ->orderByDesc('hora_entrada')
            ->get();
    }

    /**
     * Obtiene el resumen general del reporte (jornadas, tiempo trabajado y pausas).
     */
    public function obtenerResumen($asistencias)
    {
        $jornadaSegundos = 9 * 3600; // 9 horas = jornada normal

        $resumen = [
            'jornadas'            => $asistencias->count(),
            'segundos_trabajados' => 0,
            'segundos_pausa'      => 0,
            'segundos_extra'      => 0,
        ];

        foreach ($asistencias as $asistencia) {
            $trabajados = $asistencia->tiempoTrabajado();
            $resumen['segundos_trabajados'] += $trabajados;
            $resumen['segundos_pausa']      += $asistencia->tiempoPausasSegundos();
            if ($trabajados > $jornadaSegundos) {
                $resumen['segundos_extra'] += ($trabajados - $jornadaSegundos);
            }
        }

        $resumen['horas_trabajadas'] = $this->segundosAHoras(
            $resumen['segundos_trabajados']
        );

        $resumen['tiempo_pausa'] = $this->segundosAHoras(
            $resumen['segundos_pausa']
        );

        $resumen['horas_extra'] = $this->segundosAHoras(
            $resumen['segundos_extra']
        );

        return $resumen;
    }

    /**
     * Convierte segundos a formato HH:MM:SS (soporta más de 24 horas).
     */
    private function segundosAHoras(int $segundos): string
    {
        $horas = floor($segundos / 3600);
        $minutos = floor(($segundos % 3600) / 60);
        $segundos = $segundos % 60;

        return sprintf(
            '%02d:%02d:%02d',
            $horas,
            $minutos,
            $segundos
        );
    }
}