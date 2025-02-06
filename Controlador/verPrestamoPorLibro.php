<?php
require_once '../Modelo/Datosprestamos.php';
require_once '../Modelo/Conexion.php'; 

header('Content-Type: application/json'); 

$response = ['success' => false, 'prestamo' => null, 'error' => ''];

if (isset($_GET['id'])) {
    $idLibro = $_GET['id'];

    try {
        $conexion = new Conexion(); 

        $consulta = "SELECT * FROM prestamos WHERE libprest = :libprest AND estadoprest = 1";
        $modulos = $conexion->prepare($consulta);
        
        $modulos->bindParam(':libprest', $idLibro, PDO::PARAM_INT);
        $modulos->execute();
        
        $prestamo = $modulos->fetch(PDO::FETCH_ASSOC);

        if ($prestamo) {
            $response = ['success' => true, 'prestamo' => $prestamo];
        } else {
            $response['error'] = 'No se encontraron detalles de préstamo para este libro.';
        }
    } catch (PDOException $e) {
        $response['error'] = 'Error en la consulta: ' . $e->getMessage();
    }
} else {
    $response['error'] = 'ID de libro no proporcionado.';
}

echo json_encode($response);
exit;
?>