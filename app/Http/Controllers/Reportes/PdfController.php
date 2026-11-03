<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Concerns\AuthorizesAdmin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Asistencia;
use App\Services\ReporteService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PdfController extends Controller
{
    use AuthorizesAdmin;

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
        $this->authorizeAdmin();

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
        $this->authorizeAdmin();

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

        return $pdf->download('reporte-' . Str::slug($user->name) . '.pdf');
    }
}