<?php
require_once '../Modelo/Datosrevista.php';
require_once '../Modelo/DatosRA.php';
require_once '../Modelo/Conexion.php';

$revistaModelo = new misRevistas();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID de revista no especificado.']);
        exit;
    }

    $camposRequeridos = ['id', 'issn', 'titulo', 'proveedor', 'volumen', 'edicion', 'numero', 'anio', 'resumen', 'subject_index'];

    foreach ($camposRequeridos as $campo) {
        if (!isset($_POST[$campo])) {
            echo json_encode(['success' => false, 'message' => "Falta el campo: $campo."]);
            exit;
        }
    }

    $id = $_POST['id'];
    $issn = $_POST['issn'];
    $titulo = $_POST['titulo'];
    $proveedor = $_POST['proveedor'];
    $volumen = $_POST['volumen'];
    $edicion = $_POST['edicion'];
    $numero = $_POST['numero'];
    $anio = $_POST['anio'];
    $resumen = $_POST['resumen'];
    $subject_index = $_POST['subject_index'];

    // Obtener la carátula existente antes de actualizar
    $imagenActual = $revistaModelo->obtenerCaratulaActual($id);
    $imagen = $imagenActual;

    // Procesar nueva imagen si se sube
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $carpetaDestino = "../imagenes_revistas/";
        $nombreArchivo = time() . "_" . basename($_FILES['imagen']['name']);
        $rutaArchivo = $carpetaDestino . $nombreArchivo;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaArchivo)) {
            $imagen = $rutaArchivo;
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al subir la imagen.']);
            exit;
        }
    }
    error_log("📝 POST completo: " . print_r($_POST, true));

    // Actualizar revista
    $resultado = $revistaModelo->actualizarRevistas($id, $issn, $titulo, $proveedor, $volumen, $edicion, 
    $numero, $anio, $resumen, $imagen, $subject_index);

    if (!$resultado) {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la revista.']);
        exit;
    }

    // Verificar si se han enviado artículos
    if (empty($_POST['idarticulo']) || !is_array($_POST['idarticulo'])) {

        echo json_encode(['success' => true, 'message' => 'No se actualizaron artículos porque no se recibieron.']);
        exit;
    }

    $articuloModelo = new misArticulosR();
    $conexion = new Conexion();
    $carpeta_destino = "imagenes_articulos/";

    foreach ($_POST['idarticulo'] as $i => $idarticulo) {
        $autor = !empty($_POST['autor'][$i]) ? $_POST['autor'][$i] : null;
        $titulo_articulo = !empty($_POST['titulo_articulo'][$i]) ? $_POST['titulo_articulo'][$i] : null;


        // Obtener la imagen actual del artículo
    $rutaImagenActual = $articuloModelo->ObtenerImagenActual($idarticulo);

    // Si no se ha subido una nueva imagen y no se ha marcado para eliminar, mantener la imagen actual
    if (!isset($_FILES['imagenArticulo']['name'][$i]) || empty($_FILES['imagenArticulo']['name'][$i])) {
        // Si no se marca para eliminar la imagen
        if (empty($_POST['eliminar_imagen'][$idarticulo])) {
            $rutaImagen = $rutaImagenActual;  // Mantener la imagen actual
        } else {
            // Si se marca para eliminar la imagen
            $rutaImagen = null;  // Eliminar imagen
        }
    } else {
        // Si se ha subido una nueva imagen
        $nombre_archivo = time() . "_" . basename($_FILES['imagenArticulo']['name'][$i]);
        $ruta_final = $carpeta_destino . $nombre_archivo;

        if (move_uploaded_file($_FILES['imagenArticulo']['tmp_name'][$i], $ruta_final)) {
            $rutaImagen = $ruta_final;  // Asignar la nueva imagen
        }
    }

        if (!empty($idarticulo)) {
            // Actualizar artículo si ya existe
            $resultadoArticulo = $articuloModelo->actualizarArticuloR($idarticulo, $autor, $titulo_articulo, $rutaImagen);
            if (!$resultadoArticulo) {
                error_log("❌ Error en actualizarArticuloR para ID: $idarticulo");
            } else {
                error_log("✅ Éxito en actualizarArticuloR para ID: $idarticulo");
            }
        } else {
            // Insertar nuevo artículo si tiene datos
            if (!empty($autor) || !empty($titulo_articulo) || !empty($rutaImagen)) {
                $resultadoArticulo = $articuloModelo->agregarArticuloR($id, $autor, $titulo_articulo, $rutaImagen);
                if (!$resultadoArticulo) {
                    error_log("❌ Error en agregarArticuloR para ID revista: $id");
                } else {
                    error_log("✅ Éxito en agregarArticuloR para ID revista: $id");
                }
            }
        }
        
    if (!$resultadoArticulo) {
        echo json_encode([
            'success' => false, 
            'message' => "Error al procesar el articulo ID: $idarticulo",
            'debug' => [
                'id articulo' => $idarticulo,
                'autor' => $autor,
                'titulo_articulo' => $titulo_articulo,
                'imagen' => $rutaImagen
            ]
        ]);
        exit;
    }
    }

    echo json_encode(['success' => true, 'message' => 'Revista y artículos actualizados correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}


?>