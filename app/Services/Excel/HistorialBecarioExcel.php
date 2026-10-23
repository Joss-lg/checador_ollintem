<?php

namespace App\Services\Excel;

use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Reporte Excel — Historial Individual de un Becario.
 * Muestra las jornadas, pausas y horas extra de un solo becario.
 */
class HistorialBecarioExcel extends ExcelReporteBase
{
    // Última columna (A-G: Fecha, Entrada, Salida, Pausas, T.Pausa, T.Trabajado, Horas Extra)
    private const ULTIMA_COL  = 'F';
    private const FILA_TABLA  = 16;
    

    public function __construct()
    {
        parent::__construct();
        $this->sheet->setTitle('Reporte Becario');
    }

    public function generar(User $user, $asistencias, array $resumen, array $filtros = []): Spreadsheet
    {
        $this->crearEncabezado($user, $filtros);
        $this->crearResumen($resumen);
        $this->crearTabla($asistencias);
        $this->aplicarEstilos();

        return $this->spreadsheet;
    }

    private function crearEncabezado(User $user, array $filtros): void
    {
        $col = self::ULTIMA_COL;

        $this->sheet->mergeCells("A1:{$col}1");
        $this->sheet->setCellValue('A1', 'OLLIN CHECK');

        $this->sheet->mergeCells("A2:{$col}2");
        $this->sheet->setCellValue('A2', 'Sistema de Control de Asistencia');

        $this->sheet->mergeCells("A3:{$col}3");
        $this->sheet->setCellValue('A3', 'Reporte Individual de Asistencia');

        // Metadatos
        $this->sheet->setCellValue('A5', 'Becario:');
        $this->sheet->setCellValue('B5', $user->name);
        $this->sheet->setCellValue('A6', 'Correo:');
        $this->sheet->setCellValue('B6', $user->email);
        $this->sheet->setCellValue('A7', 'Desde:');
        $this->sheet->setCellValue('B7', $filtros['desde'] ?? 'Sin límite');
        $this->sheet->setCellValue('A8', 'Hasta:');
        $this->sheet->setCellValue('B8', $filtros['hasta'] ?? 'Sin límite');
        $this->sheet->setCellValue('A9', 'Generado:');
        $this->sheet->setCellValue('B9', now()->format('d/m/Y H:i'));
    }

    private function crearResumen(array $resumen): void
    {
        $col = self::ULTIMA_COL;

        $this->sheet->mergeCells("A10:{$col}10");
        $this->sheet->setCellValue('A10', 'RESUMEN DEL REPORTE');

        // Etiquetas (4 métricas)
        $this->sheet->setCellValue('A12', 'Jornadas');
        $this->sheet->setCellValue('B12', 'Horas trabajadas');
        $this->sheet->setCellValue('C12', 'Tiempo en pausa');
        // Valores
        $this->sheet->setCellValue('A13', $resumen['jornadas']);
        $this->sheet->setCellValue('B13', $resumen['horas_trabajadas']);
        $this->sheet->setCellValue('C13', $resumen['tiempo_pausa']);
    }

    private function crearTabla($asistencias): void
    {
        $fila = self::FILA_TABLA;

        foreach ([
            'A' => 'Fecha',
            'B' => 'Entrada',
            'C' => 'Salida',
            'D' => 'Pausas',
            'E' => 'T. Pausa',
            'F' => 'T. Trabajado',
        ] as $col => $titulo) {
            $this->sheet->setCellValue("{$col}{$fila}", $titulo);
        }

        $fila++;

        foreach ($asistencias as $asistencia) {
            $this->sheet->setCellValue('A' . $fila, $asistencia->fecha);
            $this->sheet->setCellValue('B' . $fila, $asistencia->hora_entrada ?? '--');
            $this->sheet->setCellValue('C' . $fila, $asistencia->hora_salida ?? '--');
            $this->sheet->setCellValue('D' . $fila, $asistencia->pausas->count());
            $this->sheet->setCellValue('E' . $fila, $asistencia->tiempoPausas());
            $this->sheet->setCellValue('F' . $fila, $asistencia->formatoTiempo($asistencia->tiempoTrabajado()));
            $fila++;
        }
    }

    private function aplicarEstilos(): void
    {
        $col       = self::ULTIMA_COL;
        $filaTabla = self::FILA_TABLA;

        $this->estilizarEncabezado($col);
        $this->estilizarMetadatos(5, 9);
        $this->estilizarResumen(10, 12, 13, $col, 3);
        $this->estilizarEncabezadoTabla($filaTabla, $col);

        $ultimaFila = $this->sheet->getHighestRow();
        if ($ultimaFila > $filaTabla) {
            $this->estilizarFilasDatos($filaTabla + 1, $ultimaFila, $col);
        }

        $this->aplicarFinales($col, $filaTabla);
    }

    public function guardarComo(string $ruta, User $user, $asistencias, array $resumen, array $filtros = []): void
    {
        $this->guardar($this->generar($user, $asistencias, $resumen, $filtros), $ruta);
    }
}
