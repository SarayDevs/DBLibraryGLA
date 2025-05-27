<?php
require_once '../Modelo/revista.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $TipoRevista = isset($_POST['nombreRevista']) ? trim($_POST['nombreRevista']) : null;

    if (!$TipoRevista) {
        echo json_encode(['success' => false, 'error' => 'El nombre de la revista es obligatorio']);
        exit;
    }

    $revistaModelo = new miRevista();
    $idRevista = $revistaModelo->agregarRevista($TipoRevista); 

    if ($idRevista) {
        echo json_encode(['success' => true, 'id' => $idRevista, 'nombre' => $TipoRevista]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al agregar la editorial']);
    }
}

?>