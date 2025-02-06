<?php
require_once '../Modelo/Datoslibros.php';
require_once '../Modelo/Conexion.php';

$libroModelo = new misLibros();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID de libro no especificado.']);
        exit;
    }


    $camposRequeridos = ['id','IDLIB', 'Codigo', 'Titulo', 'Existe', 'autorID', 'Autor', 'Edicion', 'Costo', 'Fecha', 'Estado', 'TipoEstado', 'Origen', 'TipoOrigen', 'Editorial', 'TipoEditorial', 'Area', 'TipoArea', 'Medio', 'TipoMedio', 'Clase', 'TipoClase', 'Observacion', 'Seccion', 'TipoSeccion', 'Temas', 'codinv', 'npag', 'Fimpresion', 'Temas2', 'Entrainv'];
    

    foreach ($camposRequeridos as $campo) {
        if (!isset($_POST[$campo])) {
            echo json_encode(['success' => false, 'message' => "Falta el campo: $campo."]);
            exit;
        }
    }


    $id = $_POST['id'];
    $IDLIB = $_POST['IDLIB']; 
    $Codigo = $_POST['Codigo']; 
    $Titulo = $_POST['Titulo']; 
    $Existe = $_POST['Existe']; 
    $autorID = $_POST['autorID'];
    $Autor = $_POST['Autor'];  
    $Edicion = $_POST['Edicion']; 
    $Costo = $_POST['Costo']; 
    $Fecha = $_POST['Fecha']; 
    $Estado = $_POST['Estado']; 
    $TipoEstado = $_POST['TipoEstado']; 
    $Origen = $_POST['Origen']; 
    $TipoOrigen = $_POST['TipoOrigen']; 
    $Editorial = $_POST['Editorial']; 
    $TipoEditorial = $_POST['TipoEditorial']; 
    $Area = $_POST['Area']; 
    $TipoArea = $_POST['TipoArea'];  
    $Medio = $_POST['Medio']; 
    $TipoMedio = $_POST['TipoMedio']; 
    $Clase = $_POST['Clase']; 
    $TipoClase = $_POST['TipoClase']; 
    $Observacion = $_POST['Observacion']; 
    $Seccion = $_POST['Seccion']; 
    $TipoSeccion = $_POST['TipoSeccion']; 
    $Temas = $_POST['Temas']; 
    $codinv = $_POST['codinv']; 
    $npag = $_POST['npag']; 
    $Fimpresion = $_POST['Fimpresion']; 
    $Temas2 = $_POST['Temas2']; 
    $Entrainv = $_POST['Entrainv'];


    $resultado = $libroModelo->actualizarLibro($id, $IDLIB, $Codigo, $Titulo, $Existe, $autorID, $Autor, $Edicion, $Costo, $Fecha, $Estado, $TipoEstado, 
        $Origen, $TipoOrigen, $Editorial, $TipoEditorial, $Area, $TipoArea, $Medio, $TipoMedio, $Clase, $TipoClase, 
        $Observacion, $Seccion, $TipoSeccion, $Temas, $codinv, $npag, $Fimpresion, $Temas2, $Entrainv);

 
    if ($resultado) {
        echo json_encode(['success' => true, 'message' => 'Libro actualizado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el libro.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>