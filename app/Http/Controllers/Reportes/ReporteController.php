<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ReporteService;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    protected ReporteService $reporteService;

    public function __construct(ReporteService $reporteService)
    {
        $this->reporteService = $reporteService;
    }

    public function show(User $user)
    {
        // Obtener asistencias del becario a través del servicio de reportes
        $asistencias = $this->reporteService->obtenerReporteBecario($user);

        // Obtener el resumen general de la jornada y pausas
        $resumen = $this->reporteService->obtenerResumen($asistencias);

        return view(
            'admin.historial.reporte',
            [
                'user'        => $user,
                'asistencias' => $asistencias,
                'resumen'     => $resumen,
            ]
        );
    }
}