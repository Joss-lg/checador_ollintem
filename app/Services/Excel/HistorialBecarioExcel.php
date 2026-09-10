<?php

namespace App\Services\Excel;

use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class HistorialBecarioExcel
{
    protected Spreadsheet $spreadsheet;
    protected Worksheet $sheet;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
        $this->sheet->setTitle('Reporte Becario');
    }

    /**
     * Generar el archivo Excel
     */
    public function generar(
        User $user,
        $asistencias,
        array $resumen,
        array $filtros = []
    ): Spreadsheet {
        $this->crearEncabezado($user, $filtros);
        $this->crearResumen($resumen);
        $this->crearTabla($asistencias);
        $this->aplicarEstilos();

        return $this->spreadsheet;
    }

    /**
     * Encabezado
     */
    private function crearEncabezado(User $user, array $filtros): void
    {
        $this->sheet->mergeCells('A1:F1');
        $this->sheet->setCellValue('A1', 'OLLIN CHECK');

        $this->sheet->mergeCells('A2:F2');
        $this->sheet->setCellValue('A2', 'Sistema de Control de Asistencia');

        $this->sheet->mergeCells('A3:F3');
        $this->sheet->setCellValue('A3', 'Reporte Individual de Asistencia');

        $this->sheet->setCellValue('A5', 'Becario:');
        $this->sheet->setCellValue('B5', $user->name);

        $this->sheet->setCellValue('A6', 'Generado:');
        $this->sheet->setCellValue('B6', now()->format('d/m/Y H:i'));

        $this->sheet->setCellValue('A7', 'Desde');
        $this->sheet->setCellValue('B7', $filtros['desde'] ?? 'Sin límite');

        $this->sheet->setCellValue('A8', 'Hasta');
        $this->sheet->setCellValue('B8', $filtros['hasta'] ?? 'Sin límite');
    }

    /**
     * Resumen
     */
    private function crearResumen(array $resumen): void
    {
        $this->sheet->mergeCells('A10:F10');
        $this->sheet->setCellValue('A10', 'RESUMEN DEL REPORTE');

        // Encabezados del resumen (Ajustado a 3 columnas principales)
        $this->sheet->setCellValue('A12', 'Jornadas');
        $this->sheet->setCellValue('B12', 'Horas trabajadas');
        $this->sheet->setCellValue('C12', 'Tiempo en pausa');

        // Valores del resumen
        $this->sheet->setCellValue('A13', $resumen['jornadas']);
        $this->sheet->setCellValue('B13', $resumen['horas_trabajadas']);
        $this->sheet->setCellValue('C13', $resumen['tiempo_pausa']);
    }

    /**
     * Tabla
     */
    private function crearTabla($asistencias): void
    {
        $fila = 16;

        $encabezados = [
            'A' => 'Fecha',
            'B' => 'Entrada',
            'C' => 'Salida',
            'D' => 'Pausas',
            'E' => 'Tiempo pausa',
            'F' => 'Tiempo trabajado',
        ];

        foreach ($encabezados as $columna => $titulo) {
            $this->sheet->setCellValue($columna . $fila, $titulo);
        }

        $fila++;

        foreach ($asistencias as $asistencia) {
            $this->sheet->setCellValue('A' . $fila, $asistencia->fecha);
            $this->sheet->setCellValue('B' . $fila, $asistencia->hora_entrada ?? '--');
            $this->sheet->setCellValue('C' . $fila, $asistencia->hora_salida ?? '--');
            $this->sheet->setCellValue('D' . $fila, $asistencia->pausas->count());
            $this->sheet->setCellValue('E' . $fila, $asistencia->tiempoPausas());
            $this->sheet->setCellValue(
                'F' . $fila,
                $asistencia->formatoTiempo($asistencia->tiempoTrabajado())
            );

            $fila++;
        }
    }

    /**
     * Estilos finales
     */
    private function aplicarEstilos(): void
    {
        $azul = '1E3A8A';
        $gris = 'F3F4F6';
        $blanco = 'FFFFFF';

        // Títulos principales
        foreach (['A1', 'A2', 'A3'] as $celda) {
            $this->sheet->getStyle($celda)->getFont()->setBold(true);
            $this->sheet->getStyle($celda)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $this->sheet->getStyle('A1')->getFont()->setSize(20);
        $this->sheet->getStyle('A2')->getFont()->setSize(14);
        $this->sheet->getStyle('A3')->getFont()->setSize(12);

        $this->sheet->getStyle('A1:F3')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB($azul);

        $this->sheet->getStyle('A1:F3')
            ->getFont()
            ->getColor()
            ->setARGB($blanco);

        // Resumen
        $this->sheet->getStyle('A10:F10')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB($azul);

        $this->sheet->getStyle('A10:F10')
            ->getFont()
            ->setBold(true)
            ->getColor()
            ->setARGB($blanco);

        $this->sheet->getStyle('A12:C12')
            ->getFont()
            ->setBold(true);

        $this->sheet->getStyle('A12:C13')
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $this->sheet->getStyle('A12:C12')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB($gris);

        // Encabezado de tabla
        $this->sheet->getStyle('A16:F16')
            ->getFont()
            ->setBold(true);

        $this->sheet->getStyle('A16:F16')
            ->getFont()
            ->getColor()
            ->setARGB($blanco);

        $this->sheet->getStyle('A16:F16')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB($azul);

        // Bordes de la tabla
        $ultimaFila = $this->sheet->getHighestRow();

        $this->sheet->getStyle("A16:F{$ultimaFila}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Centrar contenido
        $this->sheet->getStyle("A12:F{$ultimaFila}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Autoajuste de columnas (A hasta F)
        foreach (range('A', 'F') as $columna) {
            $this->sheet->getColumnDimension($columna)->setAutoSize(true);
        }

        // Congelar encabezado de la tabla
        $this->sheet->freezePane('A17');

        // Autofiltro
        $this->sheet->setAutoFilter("A16:F{$ultimaFila}");
    }

    /**
     * Guardar el archivo Excel
     */
    public function guardarComo(
        string $ruta,
        User $user,
        $asistencias,
        array $resumen,
        array $filtros = []
    ): void {
        $spreadsheet = $this->generar(
            $user,
            $asistencias,
            $resumen,
            $filtros
        );

        $writer = new Xlsx($spreadsheet);
        $writer->save($ruta);
    }
}