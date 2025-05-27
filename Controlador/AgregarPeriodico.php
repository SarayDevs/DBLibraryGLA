<?php
require_once '../Modelo/periodico.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $TipoPeriodico = isset($_POST['nombrePeriodico']) ? trim($_POST['nombrePeriodico']) : null;

    if (!$TipoPeriodico) {
        echo json_encode(['success' => false, 'error' => 'El nombre del periodico es obligatorio']);
        exit;
    }

    $periodicoModelo = new miPeriodico();
    $idPeriodico = $periodicoModelo->agregarPeriodico($TipoPeriodico); 

    if ($idPeriodico) {
        echo json_encode(['success' => true, 'id' => $idPeriodico, 'nombre' => $TipoPeriodico]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al agregar el periodico']);
    }
}

?>