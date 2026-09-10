<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Asistencia;
use App\Services\ReporteService;
use Illuminate\Http\Request;
use App\Services\Excel\HistorialBecarioExcel;
use App\Services\Excel\HistorialGeneralExcel;
use Carbon\Carbon;

class ExcelController extends Controller
{
    protected ReporteService $reporteService;

    public function __construct(ReporteService $reporteService)
    {
        $this->reporteService = $reporteService;
    }

    /**
     * Reporte individual del becario (Conecta con HistorialBecarioExcel)
     */
    public function reporteBecario(User $user, Request $request)
    {
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        // Obtener datos desde el Service
        $asistencias = $this->reporteService->obtenerReporteBecario(
            $user,
            $desde,
            $hasta
        );

        // Obtener resumen del reporte
        $resumen = $this->reporteService->obtenerResumen($asistencias);

        // Nombre y ruta temporal del archivo
        $nombreArchivo = 'Reporte_' .
            str_replace(' ', '_', $user->name) .
            '_' .
            now()->format('Ymd_His') .
            '.xlsx';

        $ruta = storage_path('app/temp/' . $nombreArchivo);

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0777, true);
        }

        // Generar Excel con diseño OllinCheck
        $excel = new HistorialBecarioExcel();
        $excel->guardarComo(
            $ruta,
            $user,
            $asistencias,
            $resumen,
            [
                'desde' => $desde,
                'hasta' => $hasta,
            ]
        );

        return response()->download($ruta)->deleteFileAfterSend(true);
    }

    /**
     * Reporte general de asistencias (Conecta con HistorialGeneralExcel)
     */
    public function historialGeneral(Request $request)
    {
        $buscar = $request->input('search');
        $mes = $request->input('mes');
        $semana = $request->input('semana');

        // Construcción directa de la consulta general para mantener independencia óptima
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

        // Obtener resumen general
        $resumen = $this->reporteService->obtenerResumen($asistencias);

        $nombreArchivo = 'Historial_General_' . now()->format('Ymd_His') . '.xlsx';
        $ruta = storage_path('app/temp/' . $nombreArchivo);

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0777, true);
        }

        // Generar Excel General con diseño OllinCheck
        $excel = new HistorialGeneralExcel();
        $excel->guardarComo(
            $ruta,
            $asistencias,
            $resumen,
            [
                'search' => $buscar,
                'mes'    => $mes,
                'semana' => $semana,
            ]
        );

        return response()->download($ruta)->deleteFileAfterSend(true);
    }
}