<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../Modelo/Datosprestamos.php';

$response = ['success' => false, 'error' => ''];

if (isset($_POST['IDPREST'], $_POST['estadoprest'])) {
    $IDPREST = $_POST['IDPREST'];
    $estadoprest = $_POST['estadoprest'];

    $datosPrestamos = new misPrestamos();
    if ($datosPrestamos->actualizarPrestamos($IDPREST, $estadoprest)) {
        $response['success'] = true;
    } else {
        $response['error'] = 'No se pudo actualizar el estado del préstamo.';
    }
} else {
    $response['error'] = 'Datos insuficientes para la actualización.';
}

echo json_encode($response);
exit;
