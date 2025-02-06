<?php
require_once "../Modelo/Datoslibros.php";
require_once "../Modelo/Conexion.php";

header('Content-Type: application/json');

$response = ['success' => false, 'error' => ''];

if (isset($_POST['id']) && isset($_POST['SiPrest'])) {
    $id = $_POST['id'];
    $SiPrest = $_POST['SiPrest'];

    require_once '../Modelo/Datoslibros.php';
    $datosLibros = new misLibros();

    if ($datosLibros->actualizarLibroPrestamo($id, $SiPrest)) {
        $response['success'] = true;
    } else {
        $response['error'] = 'Error al actualizar el estado del libro: ' ;
    }
} else {
    $response['error'] = 'Datos insuficientes para la actualización.';
}

echo json_encode($response);
exit;
?>