<?php

namespace App\Services\Excel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Reporte Excel — Historial General de Asistencias.
 * Muestra todos los becarios con sus jornadas, pausas y horas extra.
 */
class HistorialGeneralExcel extends ExcelReporteBase
{
    // Última columna del reporte (A-H: Becario, Fecha, Entrada, Salida,
    // Pausas, T.Pausa, T.Trabajado, Horas Extra)
    private const ULTIMA_COL   = 'G';
    private const FILA_TABLA   = 16;
    

    public function __construct()
    {
        parent::__construct();
        $this->sheet->setTitle('Historial General');
    }

    public function generar($asistencias, array $resumen, array $filtros = []): Spreadsheet
    {
        $this->crearEncabezado($filtros);
        $this->crearResumen($resumen);
        $this->crearTabla($asistencias);
        $this->aplicarEstilos();

        return $this->spreadsheet;
    }

    private function crearEncabezado(array $filtros): void
    {
        $col = self::ULTIMA_COL;

        $this->sheet->mergeCells("A1:{$col}1");
        $this->sheet->setCellValue('A1', 'OLLIN CHECK');

        $this->sheet->mergeCells("A2:{$col}2");
        $this->sheet->setCellValue('A2', 'Sistema de Control de Asistencia');

        $this->sheet->mergeCells("A3:{$col}3");
        $this->sheet->setCellValue('A3', 'Reporte General de Asistencias');

        // Metadatos
        $this->sheet->setCellValue('A5', 'Generado:');
        $this->sheet->setCellValue('B5', now()->format('d/m/Y H:i'));
        $this->sheet->setCellValue('A6', 'Búsqueda:');
        $this->sheet->setCellValue('B6', $filtros['search'] ?? 'Todos');
        $this->sheet->setCellValue('A7', 'Mes:');
        $this->sheet->setCellValue('B7', $filtros['mes'] ?? 'Todos');
        $this->sheet->setCellValue('A8', 'Semana:');
        $this->sheet->setCellValue('B8', $filtros['semana'] ?? 'Todas');
    }

    private function crearResumen(array $resumen): void
    {
        $col = self::ULTIMA_COL;

        $this->sheet->mergeCells("A10:{$col}10");
        $this->sheet->setCellValue('A10', 'RESUMEN DEL REPORTE');

        // Etiquetas (4 métricas en 4 columnas)
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
            'A' => 'Becario',
            'B' => 'Fecha',
            'C' => 'Entrada',
            'D' => 'Salida',
            'E' => 'Pausas',
            'F' => 'T. Pausa',
            'G' => 'T. Trabajado',
        ] as $col => $titulo) {
            $this->sheet->setCellValue("{$col}{$fila}", $titulo);
        }

        $fila++;

        foreach ($asistencias as $asistencia) {
            $this->sheet->setCellValue('A' . $fila, $asistencia->user->name);
            $this->sheet->setCellValue('B' . $fila, $asistencia->fecha);
            $this->sheet->setCellValue('C' . $fila, $asistencia->hora_entrada ?? '--');
            $this->sheet->setCellValue('D' . $fila, $asistencia->hora_salida ?? '--');
            $this->sheet->setCellValue('E' . $fila, $asistencia->pausas->count());
            $this->sheet->setCellValue('F' . $fila, $asistencia->tiempoPausas());
            $this->sheet->setCellValue('G' . $fila, $asistencia->formatoTiempo($asistencia->tiempoTrabajado()));
            $fila++;
        }
    }

    private function aplicarEstilos(): void
    {
        $col      = self::ULTIMA_COL;
        $filaTabla = self::FILA_TABLA;

        $this->estilizarEncabezado($col);
        $this->estilizarMetadatos(5, 8);
        $this->estilizarResumen(10, 12, 13, $col, 3);
        $this->estilizarEncabezadoTabla($filaTabla, $col);

        $ultimaFila = $this->sheet->getHighestRow();
        if ($ultimaFila > $filaTabla) {
            $this->estilizarFilasDatos($filaTabla + 1, $ultimaFila, $col);
        }

        $this->aplicarFinales($col, $filaTabla);
    }

    public function guardarComo(string $ruta, $asistencias, array $resumen, array $filtros = []): void
    {
        $this->guardar($this->generar($asistencias, $resumen, $filtros), $ruta);
    }
}
