<?php
require_once '../Modelo/Datosautor.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Autor = $_POST['nombreAutor'];


    $autorModelo = new misAutores();
    $idAutor = $autorModelo->agregarAutor($Autor);
    echo json_encode(['id' => $autorID, 'nombre' => $Autor]);
}
?>