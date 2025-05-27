<?php
require_once '../Modelo/DatosLibros.php'; 
require_once '../Modelo/Datosindice.php'; 
require_once '../Modelo/Conexion.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

 
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido, no hay envío POST']);
    exit;
}

try {
    if (!isset($_POST['Titulo'], $_POST['autorID'], $_POST['Codigo'])) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios (Título, Código o Autor).']);
        exit;
    }

    $conexion = new Conexion(); 
    $datosLibros = new misLibros(); 
    $indiceModelo = new misIndices();

    // Recibir datos del formulario
    $IDLIB = $_POST['IDLIB'] ?? ""; 
    $Codigo = $_POST['Codigo']; 
    $Titulo = $_POST['Titulo']; 
    $Existe = $_POST['Existe'] ?? ""; 
    $autorID = $_POST['autorID'];
    $Edicion = $_POST['Edicion'] ?? ""; 
    $Costo = $_POST['Costo'] ?? ""; 
    $Fecha = $_POST['Fecha'] ?? ""; 
    $Estado = $_POST['Estado'] ?? ""; 
    $Origen = $_POST['Origen'] ?? ""; 
    $Editorial = $_POST['Editorial'] ?? ""; 
    $Area = $_POST['Area'] ?? ""; 
    $Medio = $_POST['Medio'] ?? ""; 
    $Clase = $_POST['Clase'] ?? ""; 
    $Observacion = $_POST['Observacion'] ?? ""; 
    $Seccion = $_POST['Seccion'] ?? ""; 
    $Temas = $_POST['Temas'] ?? ""; 
    $npag = $_POST['npag'] ?? ""; 
    $Fimpresion = $_POST['Fimpresion'] ?? ""; 
    $Temas2 = $_POST['Temas2'] ?? ""; 
    $Entrainv = $_POST['Entrainv'] ?? ""; 
    $actividad = "1";
    $autorID2 = !empty($_POST['autorID2']) ? $_POST['autorID2'] : null;
    $ISBN = $_POST['ISBN'] ?? "";
    $Ciudad = $_POST['Ciudad'] ?? "";
    $Colacion = $_POST['Colacion'] ?? "";
    $Serie = $_POST['Serie'] ?? "";
    $Dimensiones = $_POST['Dimensiones'] ?? "";
    $AsientosSecundarios = $_POST['AsientosSecundarios'] ?? "";
    $Nota = $_POST['Nota'] ?? "";
    $Copia= $_POST['copia']?? "1";
    $autorID3 = !empty($_POST['autorID3']) ? $_POST['autorID3'] : null;
    $autorID4 = !empty($_POST['autorID4']) ? $_POST['autorID4'] : null;
    $Resena= $_POST['Resena']?? "No digitada";
    $autorID5 = !empty($_POST['autorID4']) ? $_POST['autorID5'] : null;
    
    // Validar índice JSON
    $indiceArray = isset($_POST['indiceDatos']) ? json_decode($_POST['indiceDatos'], true) : [];

    if ($_POST['indiceDatos'] !== "" && json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'message' => 'Error en el formato del índice.']);
        exit;
    }

    // Subida de imagen de la carátula
    $directorioDestino = 'imagen/';
    if (!file_exists($directorioDestino)) {
        mkdir($directorioDestino, 0777, true);
    }
    
    $Caratula = "";
    if (isset($_FILES['Caratula']) && $_FILES['Caratula']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = time() . "_" . basename($_FILES['Caratula']['name']);
        $rutaCompleta = $directorioDestino . $nombreArchivo;
        $tipoArchivo = strtolower(pathinfo($rutaCompleta, PATHINFO_EXTENSION));
        $formatosPermitidos = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($tipoArchivo, $formatosPermitidos)) {
            if (move_uploaded_file($_FILES['Caratula']['tmp_name'], $rutaCompleta)) {
                $Caratula = $rutaCompleta;
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al subir la carátula.']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Formato de imagen de carátula no permitido.']);
            exit;
        }
    }

    $idLibro = $datosLibros->agregarLibros(
        $IDLIB, $Codigo, $Titulo, $Existe, $autorID,  
        $Edicion, $Costo, $Fecha, $Estado,  $Origen, 
        $Editorial,  $Area,  $Medio,  $Clase,  $Observacion, 
        $Seccion,  $Temas, $npag, $Fimpresion, $Temas2, 
        $Entrainv, $actividad, $autorID2,  $ISBN, 
        $Ciudad, $Colacion, $Serie, $Dimensiones, $AsientosSecundarios, 
        $Caratula, $Nota, $Copia, $autorID3, $autorID4, $Resena, 
        $autorID5
    );

    if (!$idLibro) {
        echo json_encode(['success' => false, 'message' => 'Error al agregar el libro.']);
        exit;
    }

    // Subida de imagen del índice

$imagenesRuta = []; // Array para almacenar las rutas de las imágenes

if (!empty($_FILES['imagenIndice']['name'][0])) {
    $carpeta_destino = "indice/"; // Carpeta donde se guardarán las imágenes

    foreach ($_FILES['imagenIndice']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['imagenIndice']['error'][$key] === UPLOAD_ERR_OK) {
            $nombre_archivo = time() . "_" . basename($_FILES['imagenIndice']['name'][$key]);
            $ruta_final = $carpeta_destino . $nombre_archivo;

            if (move_uploaded_file($tmp_name, $ruta_final)) {
                $imagenesRuta[] = $ruta_final; // Guardar la ruta en el array
            }
        }
    }
}

// Insertar secciones del índice (si hay datos escritos)
if (!empty($indiceArray)) {
    foreach ($indiceArray as $key => $indice) {
        $rutaImagen = isset($imagenesRuta[$key]) ? $imagenesRuta[$key] : null;
        $indiceModelo->agregarIndice($idLibro, $indice['seccionnum'], $indice['titulo'], $indice['pagina'], $rutaImagen);
    }
}

// Insertar imágenes como nuevos índices (sin necesidad de datos escritos)
foreach ($imagenesRuta as $rutaImagen) {
    $indiceModelo->agregarIndice($idLibro, null, null, null, $rutaImagen);
}

// Obtener lista de libros actualizada
$libros = $datosLibros->verLibroID();
echo json_encode(['success' => true, 'libros' => $libros]);

} catch (Exception $e) {
    error_log("Error en la inserción: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error interno al procesar la solicitud.']);
}
?>