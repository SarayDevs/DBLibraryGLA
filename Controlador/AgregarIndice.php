<?php
require_once '../Modelo/DatosIndice.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idlibro = $_POST['idlibro'];
    $seccionnum = $_POST['seccionnum'];
    $titulo = $_POST['titulo'];
    $pagina = $_POST['pagina'];

    $indiceModelo = new MisIndices();
    $idindice = $indiceModelo->agregarIndice($idlibro, $seccionnum, $titulo, $pagina);

    if ($idindice) {
        echo json_encode(['success' => true, 'idindice' => $idindice]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al insertar índice']);
    }
}
?>
