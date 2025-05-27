<?php
require '../Modelo/Datoslibros.php';  
require '../vendor/autoload.php';  

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

try {
    $libroModelo = new misLibros();  
    $libros = $libroModelo->verTodosLosLibros();  

    if (empty($libros)) {
        die("No hay datos para exportar.");
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Definir encabezados personalizados
    $encabezados = [
        'Item', 'Código', 'Signatura Topografica', 'Título', 'Autor', 'Edición', 'Costo', 'Fecha de ingreso', 
        'Estado', 'Via de ingreso', 'Editorial', 'Ubicación en biblioteca', 'Medio', 'Soporte', 'Observación', 
        'Colección', 'Descriptores', 'Actividad', 'ISBN', 'Ciudad', 'Colación', 'Serie', 
        'Dimensiones', 'Nota', 'Ejemplar Nº', 'Resena'
    ];
    
    // Insertar los encabezados en la primera fila
    $sheet->fromArray([$encabezados], NULL, 'A1');

    // Aplicar estilo a los encabezados
    $styleArray = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4CAF50']],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]]
    ];
    $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray($styleArray);

    // Agregar los datos de los libros desde la segunda fila
    $sheet->fromArray($libros, NULL, 'A2');

    // Crear el archivo Excel
    $writer = new Xlsx($spreadsheet);
    $nombreArchivo = "libros_" . date("Ymd_His") . ".xlsx";

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
