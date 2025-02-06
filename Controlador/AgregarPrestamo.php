<?php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = [];

try {
    include_once '../Modelo/Conexion.php';
    include_once '../Modelo/Datosprestamos.php';

    $Prestamos = new misPrestamos();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Obtener los datos enviados por el formulario
        $libprest = $_POST['libprest'] ?? null;
        $estadoprest = $_POST['estadoprest'] ?? null;
        $nombrep = $_POST['nombrep'] ?? null;
        $tipoperson = $_POST['tipoperson'] ?? null;
        $fecha = $_POST['fecha'] ?? null;
        $DEVOLUCION= null;

        // Validación de campos
        if (!$libprest || !$estadoprest || !$nombrep || !$tipoperson || !$fecha) {
            $response['success'] = false;
            $response['error'] = 'Faltan campos obligatorios';
        } else if ($Prestamos->agregarPrestamos($libprest, $estadoprest, $nombrep, $tipoperson, $fecha, $DEVOLUCION)) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['error'] = 'Error al agregar préstamo en la base de datos';
        }
    } else {
        $response['success'] = false;
        $response['error'] = 'Método no permitido';
    }
} catch (Exception $e) {
    $response['success'] = false;
    $response['error'] = 'Excepción capturada: ' . $e->getMessage();
}

// Retornar la respuesta en formato JSON
echo json_encode($response);
exit;
?>