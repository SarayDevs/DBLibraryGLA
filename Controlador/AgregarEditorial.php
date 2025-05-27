<?php
require_once '../Modelo/Datoseditorial.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $TipoEditorial = isset($_POST['nombreEditorial']) ? trim($_POST['nombreEditorial']) : null;

    if (!$TipoEditorial) {
        echo json_encode(['success' => false, 'error' => 'El nombre de la editorial es obligatorio']);
        exit;
    }

    $editorialModelo = new misEditoriales();
    $idEditorial = $editorialModelo->agregarEditorial($TipoEditorial); 

    if ($idEditorial) {
        echo json_encode(['success' => true, 'id' => $idEditorial, 'nombre' => $TipoEditorial]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al agregar la editorial']);
    }
}

?>