<?php
require_once '../Modelo/DatosLibros.php';

header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: 0');

try {
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $libroModelo = new misLibros();
        $id = intval($_GET['id']); // Asegúrate de que el ID sea un número entero.

        $resultado = $libroModelo->eliminarLibro($id);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Libro eliminado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Eliminado']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de libro no proporcionado o no válido']);
    }
} catch (Exception $e) {
    // Gestión de errores del servidor.
    error_log('Error en eliminarLibro: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor. Intente más tarde.']);
}
?>
