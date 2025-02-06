<?php
require_once '../Modelo/DatosLibros.php'; 
require_once '../Modelo/Conexion.php';
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);


$paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1; 

$response = array('success' => false, 'message' => 'Error desconocido');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Método no permitido, no hay envio post");
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       
        if (isset($_POST['Titulo']) && isset($_POST['autorID']) && isset($_POST['Codigo'])) {
       
            $conexion = new Conexion(); 
            $datosLibros = new misLibros(); 
     
            $IDLIB = $_POST['IDLIB'] ?? ""; 
            $Codigo = $_POST['Codigo'] ?? ""; 
            $Titulo = $_POST['Titulo'] ?? ""; 
            $Existe = $_POST['Existe'] ?? ""; 
            $autorID = $_POST['autorID'] ?? "";
            $Autor = $_POST['autor'] ?? "";
            $Edicion = $_POST['Edicion'] ?? ""; 
            $Costo = $_POST['Costo'] ?? ""; 
            $Fecha = $_POST['Fecha'] ?? ""; 
            $Estado = $_POST['Estado'] ?? ""; 
            $TipoEstado = $_POST['TipoEstado'] ?? ""; 
            $Origen = $_POST['Origen'] ?? ""; 
            $TipoOrigen = $_POST['TipoOrigen'] ?? ""; 
            $Editorial = $_POST['Editorial'] ?? ""; 
            $TipoEditorial = $_POST['TipoEditorial'] ?? ""; 
            $Area = $_POST['Area'] ?? ""; 
            $TipoArea = $_POST['TipoArea'] ?? ""; 
            $Medio = $_POST['Medio'] ?? ""; 
            $TipoMedio = $_POST['TipoMedio'] ?? ""; 
            $Clase = $_POST['Clase'] ?? ""; 
            $TipoClase = $_POST['TipoClase'] ?? ""; 
            $Observacion = $_POST['Observacion'] ?? ""; 
            $Seccion = $_POST['Seccion'] ?? ""; 
            $TipoSeccion = $_POST['TipoSeccion'] ?? ""; 
            $Temas = $_POST['Temas'] ?? ""; 
            $codinv = $_POST['codinv'] ?? ""; 
            $npag = $_POST['npag'] ?? ""; 
            $Fimpresion = $_POST['Fimpresion'] ?? ""; 
            $Temas2 = $_POST['Temas2'] ?? ""; 
            $Entrainv = $_POST['Entrainv'] ?? ""; 
            $actividad="1";
            

           
            if ($datosLibros->agregarLibros($IDLIB, $Codigo, $Titulo, $Existe, $autorID, $Autor, $Edicion, $Costo, $Fecha, $Estado, $TipoEstado, $Origen, $TipoOrigen, $Editorial, $TipoEditorial, $Area, $TipoArea, $Medio, $TipoMedio, $Clase, $TipoClase, $Observacion, $Seccion, $TipoSeccion, $Temas, $codinv, $npag, $Fimpresion, $Temas2, $Entrainv, $actividad)) {

                $libros = $datosLibros->verLibroID(); 
                echo json_encode(['success' => true, 'libros' => $libros, 'paginaActual' => $paginaActual]); 
               
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al agregar el libro.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Datos faltantes para insertar el libro.']);
        }
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no válido']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>