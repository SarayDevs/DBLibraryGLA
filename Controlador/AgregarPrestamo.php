<?php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = [];
error_log(print_r($_POST, true));
try {
    include_once '../Modelo/Conexion.php';
    include_once '../Modelo/Datosprestamos.php';

    $Prestamos = new misPrestamos();
    $conexion = new Conexion();
    $pdo = $conexion;


    if (isset($_POST['libprest']) && isset($_POST['nombrep']) && isset($_POST['fecha']) && isset($_POST['tipoPrestamo'])) {
        $libprest = $_POST['libprest'];
        $estadoprest = $_POST['estadoprest'];
        $tipoprest = $_POST['tipoPrestamo'];
        $nombrep = $_POST['nombrep'];
        $tipoperson = $_POST['tipoperson'];
        $fecha = $_POST['fecha'];
       
        $DEVOLUCION = null;

        $pdo->beginTransaction(); // Iniciar transacción

        // Insertar en la tabla préstamos
        $idPrestamo = $Prestamos->agregarPrestamos($libprest, $estadoprest, $tipoprest, $nombrep,
         $tipoperson,  $fecha, $DEVOLUCION);

        if (!$idPrestamo) {
            throw new Exception('Error al agregar el préstamo en la base de datos');
        }

        // Si el préstamo es externo, agregarlo en externoprest
        if ($tipoprest == "1") {
            $contacto = $_POST['contacto'] ?? null;
            $tiempo = $_POST['tiempo'] ?? null;

            if (!$contacto || !$tiempo) {
                throw new Exception('Faltan datos obligatorios para préstamo externo');
            }

            $sql = "INSERT INTO externoprest (idprestamo, tipoprest, idlibro, nombre, persona, contacto, tiempo, fecha_inicio )
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$idPrestamo, $tipoprest, $libprest, $nombrep, $tipoperson, $contacto, $tiempo, $fecha]);

            if ($stmt->rowCount() == 0) {
                throw new Exception('Error al agregar préstamo externo');
            }
        }

        $pdo->commit(); // Confirmar transacción
        $response['success'] = true;

    } else {
        $response['success'] = false;
        $response['error'] = 'Faltan campos obligatorios';
    }
} catch (Exception $e) {
    $pdo->rollBack(); // Revertir cambios si hay error
    $response['success'] = false;
    $response['error'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>
