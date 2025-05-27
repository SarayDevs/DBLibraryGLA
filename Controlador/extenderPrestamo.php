<?php
require_once '../Modelo/Conexion.php';
require_once '../Modelo/Datosprestamos.php';

header('Content-Type: application/json');

$response = ['success' => false, 'error' => ''];

if (isset($_POST['idPrestamo'], $_POST['diasExtra'])) {
    $ID = $_POST['idPrestamo'];
    $diasExtra = intval($_POST['diasExtra']); // Convertimos a número para evitar errores

    try {
        $conexion = new Conexion();

        // Obtener el tiempo actual del préstamo
        $consulta = "SELECT tiempo FROM externoprest WHERE idprestamo = :ID";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':ID', $ID, PDO::PARAM_INT);
        $stmt->execute();
        $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$prestamo) {
            $response['error'] = 'Préstamo no encontrado.';
            echo json_encode($response);
            exit;
        }

        $tiempoActual = intval($prestamo['tiempo']); // Días actuales del préstamo

        // ✅ Llamar a la función para actualizar el préstamo
        $misPrestamos = new misPrestamos();
        $actualizado = $misPrestamos->actualizarPrestamosExternos($ID, $tiempoActual, $diasExtra);

        if ($actualizado) {
            $response['success'] = true;
            $response['mensaje'] = "El préstamo fue extendido exitosamente.";
        } else {
            $response['error'] = 'No se pudo extender el préstamo.';
        }
    } catch (PDOException $e) {
        $response['error'] = 'Error en la base de datos: ' . $e->getMessage();
    }
} else {
    $response['error'] = 'ID de préstamo o días extra no proporcionados.';
}

echo json_encode($response);
exit;
?>
