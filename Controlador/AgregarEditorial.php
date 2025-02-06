<?php
require_once '../Modelo/Datoseditorial.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $TipoEditorial = $_POST['nombreEditorial'];

    $editorialModelo = new misEditoriales();
    $idEditorial = $editorialModelo->agregarEditorial($TipoEditorial); 

    echo json_encode(['id' => $Editorial, 'nombre' => $TipoEditorial]);
}
?>