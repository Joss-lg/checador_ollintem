<?php

namespace App\Services\Excel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Clase base compartida para los reportes Excel del sistema.
 * Centraliza los estilos, colores y utilidades para que HistorialGeneralExcel
 * e HistorialBecarioExcel no repitan el mismo código.
 */
abstract class ExcelReporteBase
{
    // ── Paleta de colores ─────────────────────────────────────────────────────
    protected const AZUL_HEADER  = 'FF1E3A8A'; // Azul oscuro — encabezados
    protected const AZUL_BANDA   = 'FFE8F0FE'; // Azul muy claro — filas alternas
    protected const GRIS_LABEL   = 'FFF3F4F6'; // Gris claro — etiquetas de resumen
    protected const VERDE_EXTRA  = 'FF166534'; // Verde oscuro — horas extra
    protected const VERDE_FONDO  = 'FFDCFCE7'; // Verde claro — fondo horas extra
    protected const BLANCO       = 'FFFFFFFF';
    protected const TEXTO_OSCURO = 'FF111827';
    protected const TEXTO_GRIS   = 'FF6B7280';

    protected Spreadsheet $spreadsheet;
    protected Worksheet $sheet;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet       = $this->spreadsheet->getActiveSheet();
    }

    // ── Métodos de estilo compartidos ─────────────────────────────────────────

    protected function aplicarFondo(string $rango, string $color): void
    {
        $this->sheet->getStyle($rango)
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB($color);
    }

    protected function aplicarColorTexto(string $rango, string $color): void
    {
        $this->sheet->getStyle($rango)
            ->getFont()
            ->getColor()
            ->setARGB($color);
    }

    protected function aplicarBordes(string $rango, string $estilo = Border::BORDER_THIN): void
    {
        $this->sheet->getStyle($rango)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle($estilo);
    }

    protected function centrar(string $rango): void
    {
        $this->sheet->getStyle($rango)
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
    }

    protected function negrita(string $rango): void
    {
        $this->sheet->getStyle($rango)->getFont()->setBold(true);
    }

    /**
     * Aplica el estilo completo al bloque de encabezado azul (filas 1-3).
     */
    protected function estilizarEncabezado(string $ultimaCol): void
    {
        $rango = "A1:{$ultimaCol}3";

        $this->aplicarFondo($rango, self::AZUL_HEADER);
        $this->aplicarColorTexto($rango, self::BLANCO);
        $this->negrita($rango);
        $this->centrar($rango);

        $this->sheet->getStyle('A1')->getFont()->setSize(18);
        $this->sheet->getStyle('A2')->getFont()->setSize(12);
        $this->sheet->getStyle('A3')->getFont()->setSize(11);

        // Borde inferior del encabezado
        $this->sheet->getStyle($rango)
            ->getBorders()
            ->getBottom()
            ->setBorderStyle(Border::BORDER_MEDIUM);
    }

    /**
     * Estiliza el bloque de metadatos (filas 5-8): etiqueta en col A, valor en col B.
     */
    protected function estilizarMetadatos(int $filaDesde, int $filaHasta): void
    {
        for ($f = $filaDesde; $f <= $filaHasta; $f++) {
            $this->sheet->getStyle("A{$f}")->getFont()->setBold(true);
            $this->aplicarColorTexto("A{$f}", self::TEXTO_GRIS);
            $this->aplicarColorTexto("B{$f}", self::TEXTO_OSCURO);
        }
    }

    /**
     * Estiliza el bloque del resumen.
     * $filaTitle: fila del título "RESUMEN"
     * $filaHeader: fila de etiquetas
     * $filaData: fila de valores
     * $ultimaCol: última columna del reporte
     */
    protected function estilizarResumen(
        int    $filaTitle,
        int    $filaHeader,
        int    $filaData,
        string $ultimaCol,
        int    $numColumnas
    ): void {
        // Título del resumen
        $rangoTitle = "A{$filaTitle}:{$ultimaCol}{$filaTitle}";
        $this->aplicarFondo($rangoTitle, self::AZUL_HEADER);
        $this->aplicarColorTexto($rangoTitle, self::BLANCO);
        $this->negrita($rangoTitle);
        $this->centrar($rangoTitle);
        $this->sheet->getStyle($rangoTitle)->getFont()->setSize(11);

        // Calcular hasta qué columna ocupa el resumen (D para 4 cols)
        $letras  = range('A', 'Z');
        $colFin  = $letras[$numColumnas - 1]; // 0-indexed: 3 → D, 3 métricas + extra = 4

        $rangoHeader = "A{$filaHeader}:{$colFin}{$filaHeader}";
        $rangoData   = "A{$filaData}:{$colFin}{$filaData}";
        $rangoCombinado = "A{$filaHeader}:{$colFin}{$filaData}";

        $this->aplicarFondo($rangoHeader, self::GRIS_LABEL);
        $this->negrita($rangoHeader);
        $this->centrar($rangoCombinado);
        $this->aplicarBordes($rangoCombinado);

        // Borde exterior más grueso para el bloque de resumen
        $this->sheet->getStyle($rangoCombinado)
            ->getBorders()
            ->getOutline()
            ->setBorderStyle(Border::BORDER_MEDIUM);

        // Separador entre filas del resumen
        $this->sheet->getStyle($rangoHeader)
            ->getBorders()
            ->getBottom()
            ->setBorderStyle(Border::BORDER_THIN);
    }

    /**
     * Estiliza el encabezado de la tabla de datos.
     */
    protected function estilizarEncabezadoTabla(int $fila, string $ultimaCol): void
    {
        $rango = "A{$fila}:{$ultimaCol}{$fila}";
        $this->aplicarFondo($rango, self::AZUL_HEADER);
        $this->aplicarColorTexto($rango, self::BLANCO);
        $this->negrita($rango);
        $this->centrar($rango);

        $this->sheet->getStyle($rango)
            ->getAlignment()
            ->setWrapText(false);

        // Altura un poco mayor para el header de tabla
        $this->sheet->getRowDimension($fila)->setRowHeight(22);
    }

    /**
     * Aplica zebra striping (filas alternas) y centra el contenido.
     * También resalta en verde las filas con horas extra > 0.
     */
    protected function estilizarFilasDatos(
        int    $filaDesde,
        int    $filaHasta,
        string $ultimaCol,
        ?int   $columnaExtra = null  // índice 1-based de la col de horas extra
    ): void {
        for ($f = $filaDesde; $f <= $filaHasta; $f++) {
            $rango = "A{$f}:{$ultimaCol}{$f}";

            // Zebra striping
            if ($f % 2 === 0) {
                $this->aplicarFondo($rango, self::AZUL_BANDA);
            }

            $this->centrar($rango);
            $this->aplicarBordes($rango);

            // Altura de fila cómoda
            $this->sheet->getRowDimension($f)->setRowHeight(18);

            // Resaltar horas extra si las hay
            if ($columnaExtra !== null) {
                $letras = range('A', 'Z');
                $colLetra = $letras[$columnaExtra - 1];
                $valor = $this->sheet->getCell("{$colLetra}{$f}")->getValue();
                if ($valor && $valor !== '00:00:00' && $valor !== '--') {
                    $this->aplicarFondo($rango, self::VERDE_FONDO);
                    $this->aplicarColorTexto("{$colLetra}{$f}", self::VERDE_EXTRA);
                    $this->negrita("{$colLetra}{$f}");
                }
            }
        }

        // Borde exterior de toda la tabla más grueso
        $this->sheet->getStyle("A{$filaDesde}:{$ultimaCol}{$filaHasta}")
            ->getBorders()
            ->getOutline()
            ->setBorderStyle(Border::BORDER_MEDIUM);
    }

    /**
     * Autoajusta columnas y aplica freeze + autofiltro en la fila del header.
     */
    protected function aplicarFinales(string $ultimaCol, int $filaHeader): void
    {
        $letraFin = $ultimaCol;
        $letraInicio = 'A';
        $ord = ord($letraInicio);
        while (chr($ord) <= $letraFin) {
            $this->sheet->getColumnDimension(chr($ord))->setAutoSize(true);
            $ord++;
        }

        $this->sheet->freezePane('A' . ($filaHeader + 1));

        $ultimaFila = $this->sheet->getHighestRow();
        $this->sheet->setAutoFilter("A{$filaHeader}:{$ultimaCol}{$ultimaFila}");
    }

    protected function guardar(Spreadsheet $spreadsheet, string $ruta): void
    {
        (new Xlsx($spreadsheet))->save($ruta);
    }
}
