<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Concerns\AuthorizesAdmin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Asistencia;
use App\Services\ReporteService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\Excel\HistorialBecarioExcel;
use App\Services\Excel\HistorialGeneralExcel;

class ExcelController extends Controller
{
    use AuthorizesAdmin;

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
        $this->authorizeAdmin();

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
        // Str::slug quita acentos, "/" y otros caracteres que romperían la ruta
        $nombreArchivo = 'Reporte_' .
            Str::slug($user->name, '_') .
            '_' .
            now()->format('Ymd_His') .
            '.xlsx';

        $ruta = $this->rutaTemporal($nombreArchivo);

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

        return response()->download($ruta, $nombreArchivo)->deleteFileAfterSend(true);
    }

    /**
     * Reporte general de asistencias (Conecta con HistorialGeneralExcel)
     */
    public function historialGeneral(Request $request)
    {
        $this->authorizeAdmin();

        $buscar = $request->input('search');
        $mes = $request->input('mes');
        $semana = $request->input('semana');

        // Mismos filtros y orden que la pantalla del historial
        $asistencias = Asistencia::query()
            ->with(['user', 'pausas'])
            ->filtrosHistorial($request->only(['search', 'semana', 'mes']))
            ->ordenar($request->input('order'))
            ->get();

        // Obtener resumen general
        $resumen = $this->reporteService->obtenerResumen($asistencias);

        $nombreArchivo = 'Historial_General_' . now()->format('Ymd_His') . '.xlsx';
        $ruta = $this->rutaTemporal($nombreArchivo);

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

        return response()->download($ruta, $nombreArchivo)->deleteFileAfterSend(true);
    }

    /**
     * Ruta en storage/app/temp; crea la carpeta si no existe, con permisos
     * 0755 (antes 0777 dejaba la carpeta escribible para cualquier usuario).
     * El archivo en disco lleva un sufijo aleatorio para que dos descargas en
     * el mismo segundo no compartan archivo; el nombre que ve el usuario es
     * $nombreArchivo (se pasa aparte a download()).
     */
    private function rutaTemporal(string $nombreArchivo): string
    {
        $carpeta = storage_path('app/temp');

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0755, true);
        }

        return $carpeta . DIRECTORY_SEPARATOR . Str::random(8) . '_' . $nombreArchivo;
    }
}