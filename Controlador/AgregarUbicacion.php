<?php
require_once '../Modelo/Datosarea.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $TipoArea = isset($_POST['nombreUbicacion']) ? trim($_POST['nombreUbicacion']) : null;

    if (!$TipoArea) {
        echo json_encode(['success' => false, 'error' => 'El nombre de la ubicación es obligatorio']);
        exit;
    }

    $areaModelo = new misAreas();
    $idArea = $areaModelo->agregarArea($TipoArea); 

    if ($idArea) {
        echo json_encode(['success' => true, 'id' => $idArea, 'nombre' => $TipoArea]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se pudo agregar la ubicación']);
    }
}
?>
