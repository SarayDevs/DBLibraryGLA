<?php
require_once '../Modelo/Datoslibros.php'; // Asegúrate de incluir la clase adecuada para actualizar

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Actividad = $_POST['TActividad'];
    $ID = $_POST['id'];  // Actividad seleccionada

    // Crear una instancia de la clase para manejar la actualización
    $misActividades = new misLibros();
    
    if ($misActividades->actualizarActividad($ID, $Actividad)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $misActividades->getLastError()]);
    }
}
?>