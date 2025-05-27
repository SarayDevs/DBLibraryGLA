<?php
require_once '../Modelo/Datosrevista.php';

header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: 0');

try {
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $revistaModelo = new misRevistas();
        $id = intval($_GET['id']); // Convertir ID a número entero

        $resultado = $revistaModelo->eliminarRevista($id);

        if ($resultado) {
            // Reorganizar los IDs solo si la eliminación fue exitosa
            $revistaModelo->reorganizarIDsRevistas();
            echo json_encode(['success' => true, 'message' => 'Revista eliminada correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: No se pudo eliminar la revista.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de revista no válido.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor.', 'error' => $e->getMessage()]);
}
?>
