
<?php
require_once '../Modelo/Datosperiodico.php';

header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: 0');


try {
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $libroPeriodico = new misPeriodicos();
        $id = intval($_GET['id']); // Convertir ID a número entero
        $resultado = $libroPeriodico->eliminarPeriodico($id);
        if ($resultado) {
            // Reorganizar los IDs solo si la eliminación fue exitosa
            $libroPeriodico->reorganizarIDsPeriodicos();
            echo json_encode(['success' => true, 'message' => 'Periodico eliminada correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: No se pudo eliminar el periodico.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de periodico no válido.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor.', 'error' => $e->getMessage()]);
}
?>
