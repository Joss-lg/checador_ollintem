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
        $buscar = $request->input('search');
        $mes    = $request->input('mes');
        $semana = $request->input('semana');

        // Construcción directa de la consulta general
        $asistencias = Asistencia::query()
            ->with(['user', 'pausas'])
            ->join('users', 'users.id', '=', 'asistencias.user_id')
            ->select('asistencias.*')
            ->orderBy('users.name')
            ->when($buscar, fn($q) => $q->where('users.name', 'like', "%{$buscar}%"))
            ->filtrarPorMes($mes)
            ->filtrarPorSemana($semana)
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