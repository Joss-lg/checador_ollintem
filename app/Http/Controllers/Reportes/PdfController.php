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
        $query = Asistencia::query()
            ->with(['user', 'pausas'])
            ->join('users', 'users.id', '=', 'asistencias.user_id')
            ->select('asistencias.*')
            ->orderBy('users.name');

        if ($buscar) {
            $query->where('users.name', 'like', "%{$buscar}%");
        }

        if ($mes) {
            $query->whereMonth('fecha', $mes);
        }

        if ($semana) {
            switch ($semana) {
                case 1:
                    $query->whereDay('fecha', '>=', 1)->whereDay('fecha', '<=', 7);
                    break;
                case 2:
                    $query->whereDay('fecha', '>=', 8)->whereDay('fecha', '<=', 14);
                    break;
                case 3:
                    $query->whereDay('fecha', '>=', 15)->whereDay('fecha', '<=', 21);
                    break;
                case 4:
                    $query->whereDay('fecha', '>=', 22)->whereDay('fecha', '<=', 28);
                    break;
                case 5:
                    $query->whereDay('fecha', '>=', 29);
                    break;
            }
        }

        $asistencias = $query->get();

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