<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Asistencia;
use App\Services\ReporteService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    protected ReporteService $reporteService;

    public function __construct(ReporteService $reporteService)
    {
        $this->reporteService = $reporteService;
    }

    /*
    |--------------------------------------------------------------------------
    | PDF GENERAL
    |--------------------------------------------------------------------------
    */
    public function general(Request $request)
    {
        // Mismos filtros y orden que la pantalla del historial
        $asistencias = Asistencia::query()
            ->with(['user', 'pausas'])
            ->filtrosHistorial($request->only(['search', 'semana', 'mes']))
            ->ordenar($request->input('order'))
            ->get();

        $resumen = $this->reporteService->obtenerResumen($asistencias);

        $pdf = Pdf::loadView(
            'admin.reportes.pdf.general',
            compact('asistencias', 'resumen')
        );

        return $pdf->download('reporte-general.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | PDF INDIVIDUAL BECARIO
    |--------------------------------------------------------------------------
    */
    public function becario(Request $request, User $user)
    {
        $asistencias = $this->reporteService->obtenerReporteBecario(
            $user,
            $request->input('desde'),
            $request->input('hasta')
        );

        $resumen = $this->reporteService->obtenerResumen($asistencias);

        $pdf = Pdf::loadView(
            'admin.reportes.pdf.individual',
            compact('user', 'asistencias', 'resumen')
        );

        return $pdf->download('reporte-' . str_replace(' ', '_', $user->name) . '.pdf');
    }
}