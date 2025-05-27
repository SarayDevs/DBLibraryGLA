<?php
require_once __DIR__ . '/../Modelo/Conexion.php';


try {
    $conexion = new Conexion();

    // Resta 1 día a todos los préstamos que aún tienen tiempo restante
    $consulta = "UPDATE externoprest 
                 SET tiempo = tiempo - 1 
                 WHERE tiempo > 0";

    $stmt = $conexion->prepare($consulta);
    if ($stmt->execute()) {
        echo "Actualización exitosa: Se han restado 1 día a los préstamos.";
    } else {
        echo "Error en la actualización.";
    }
} catch (PDOException $e) {
    echo "Error en la base de datos: " . $e->getMessage();
}
?>
