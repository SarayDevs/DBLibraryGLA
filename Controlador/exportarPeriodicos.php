<?php
require '../Modelo/Datosperiodico.php';  // Ajusta la ruta según tu estructura
require '../vendor/autoload.php';  // Incluye PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

try {
    $periodicoModelo = new misPeriodicos();  // Instancia de la clase donde está la función
    $periodicos = $periodicoModelo->verTodosLosPeriodicos();  // Obtener las revistas

    if (empty($periodicos)) {
        die("No hay datos para exportar.");
    }

    // Crear un nuevo archivo Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Encabezados
    $encabezados = array_keys($periodicos[0]);  // Obtener los nombres de las columnas
    $sheet->fromArray([$encabezados], NULL, 'A1');  // Agregar encabezados en la primera fila


// Establecer color de fondo y negrita en los encabezados
$styleArray = [
    'font' => [
        'bold' => true,  // Negrita
        'color' => ['rgb' => 'FFFFFF'],  // Color de texto blanco
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '4CAF50'],  // Color de fondo verde
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,  // Bordes finos
            'color' => ['rgb' => '000000'],  // Bordes negros
        ],
    ],
];

// Aplicar el estilo a la primera fila (encabezados)
$sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray($styleArray);

    // Agregar los datos de las revistas desde la segunda fila
    $sheet->fromArray($periodicos, NULL, 'A2');

    // Crear el archivo Excel
    $writer = new Xlsx($spreadsheet);
    $nombreArchivo = "periodicos_" . date("Ymd_His") . ".xlsx";

    // Configurar las cabeceras para la descarga
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;

} catch (Exception $e) {
    die("Error al generar el Excel: " . $e->getMessage());
}
?>
