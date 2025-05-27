<?php
require_once '../Modelo/Datosautor2.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Autor = isset($_POST['nombreAutor']) ? trim($_POST['nombreAutor']) : null;

    if (!$Autor) {
        echo json_encode(['success' => false, 'error' => 'El nombre del autor es obligatorio']);
        exit;
    }

    $autorModelo = new misAutores();
    $idAutor = $autorModelo->agregarAutor($Autor);

    if ($idAutor) {
        echo json_encode(['success' => true, 'id' => $idAutor, 'nombre' => $Autor]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se pudo agregar el autor']);
    }
}
?>
