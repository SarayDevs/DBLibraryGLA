<?php
require_once '../Modelo/Datosrevista.php'; 
require_once '../Modelo/DatosRA.php'; 
require_once '../Modelo/Datosindice.php'; 
require_once '../Modelo/Conexion.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido, debe ser POST.']);
    exit;
}

try {
    $conexion = new Conexion();
    $revistaModel = new misRevistas();
    $datosArticulos = new misArticulosR();
    $indiceModelo = new misIndices();

    // 📌 Obtener datos de la revista
    $issn = $_POST['issn'] ?? '';
    $titulo = $_POST['titulo'] ?? '';
    $proveedor = $_POST['proveedor'] ?? '';
    $volumen = $_POST['volumen'] ?? '';
    $edicion = $_POST['edicion'] ?? '';
    $numero = $_POST['numero'] ?? '';
    $anio = $_POST['anio'] ?? '';
    $resumen = $_POST['resumen'] ?? '';
    $subject_index = $_POST['subject_index'] ?? '';

    $articuloArray = isset($_POST['articulosDatos']) ? json_decode($_POST['articulosDatos'], true) : [];

    if ($_POST['articulosDatos'] !== "" && json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'message' => 'Error en el formato del articulo.']);
        exit;
    }


    // 📌 Subir imagen de la revista
    $imagen = '';
    if (!empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $rutaDestino = '../imagenes_revistas/';
        if (!is_dir($rutaDestino)) {
            mkdir($rutaDestino, 0755, true);
        }

        $nombreImagen = uniqid() . '_' . $_FILES['imagen']['name'];
        $rutaCompleta = $rutaDestino . $nombreImagen;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta)) {
            $imagen = $rutaCompleta;
        }
    }

    // 📌 Insertar revista
    $revista_id = $revistaModel->agregarRevistas($issn, $titulo, $proveedor, $volumen, $edicion, 
    $numero, $anio, $resumen, $imagen, $subject_index);

    if (!$revista_id) {
        echo json_encode(['success' => false, 'message' => 'Error al agregar la revista.']);
        exit;
    }
    // 📌 Depuración: Verifica los datos que llegaron
    error_log("Array de artículos después de decodificar: " . print_r($articuloArray, true));
    
    $imagenesRuta = [];
    $imagenesUsadas = [];
   
    error_log(print_r($_FILES['imagenArticulo'], true));

    // 📌 Procesar imágenes y guardarlas en una lista
    if (!empty($_FILES['imagenArticulo']['name'][0])) {
         $carpeta_destino = "imagenes_articulos/";
        foreach ($_FILES['imagenArticulo']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['imagenArticulo']['error'][$key] === UPLOAD_ERR_OK) {
                $nombre_archivo = time() . "_" . basename($_FILES['imagenArticulo']['name'][$key]);
                $ruta_final = $carpeta_destino . $nombre_archivo;
    
                if (move_uploaded_file($tmp_name, $ruta_final)) {
                    $imagenesRuta[] = $ruta_final; // Guardar la ruta en el array
                }
            }
        }
    }
    
    // 📌 Insertar artículos con imágenes (si tienen título y autor)
    if (!empty($articuloArray)) {
        foreach ($articuloArray as $key => $articulo) {
            $rutaImagen = isset($imagenesRuta[$key]) ? $imagenesRuta[$key] : null;
            $datosArticulos->agregarArticuloR(
                $revista_id,
                $articulo['autor_articulo'],
                $articulo['titulo_articulo'],
                $rutaImagen
            );
            if ($rutaImagen) {
                $imagenesUsadas[] = $rutaImagen;
            }
        }
    }
    
    // 📌 Insertar imágenes sin datos de artículo (solo si NO fueron usadas en artículos con título/autor)
    foreach ($imagenesRuta as $rutaImagen) {
        if (!in_array($rutaImagen, $imagenesUsadas)) {
        $datosArticulos->agregarArticuloR($revista_id, null, null, $rutaImagen);
    }
}
    
    error_log("Artículos insertados en BD: " . print_r($articuloArray, true));
    
    $articulos = $datosArticulos->verArticulosRID($revista_id);
    
    ob_clean(); // Limpia cualquier salida previa
    echo json_encode([
        'success' => true,
        'revista_id' => $revista_id,
        'articulos' => $articulos
    ]);
    exit; // Finaliza el script para evitar más salida
    

} catch (Exception $e) {
    error_log("Error en la inserción: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error interno al procesar la solicitud.',
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>
