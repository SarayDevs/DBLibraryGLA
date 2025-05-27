<?php
require_once '../Modelo/Datoslibros.php';
require_once '../Modelo/Datosindice.php';
require_once '../Modelo/Conexion.php';

$libroModelo = new misLibros();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID de libro no especificado.']);
        exit;
    }


    $camposRequeridos = ['id','IDLIB', 'Codigo', 'Titulo', 'Existe', 'autorID',  'Edicion', 'Costo', 
    'Fecha', 'Estado',  'Origen',  'Editorial',  'Area', 
    'Medio',  'Clase',  'Observacion', 'Seccion',  'Temas',  'npag', 
    'Fimpresion', 'Temas2', 'Entrainv','autorID2','ISBN', 'Ciudad', 'Colacion', 'Serie', 'Dimensiones', 
    'AsientosSecundarios', 'Nota'];
    

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
    $Edicion = $_POST['Edicion']; 
    $Costo = $_POST['Costo']; 
    $Fecha = $_POST['Fecha']; 
    $Estado = $_POST['Estado']; 
    $Origen = $_POST['Origen']; 
    $Editorial = $_POST['Editorial']; 
    $Area = $_POST['Area']; 
    $Medio = $_POST['Medio']; 
    $Clase = $_POST['Clase']; 
    $Observacion = $_POST['Observacion']; 
    $Seccion = $_POST['Seccion']; 
    $Temas = $_POST['Temas'];  
    $npag = $_POST['npag']; 
    $Fimpresion = $_POST['Fimpresion']; 
    $Temas2 = $_POST['Temas2']; 
    $Entrainv = $_POST['Entrainv'];
    $autorID2= $_POST['autorID2'] ?? null;; 
	$ISBN= $_POST['ISBN']; 
    $Ciudad= $_POST['Ciudad'];
    $Colacion= $_POST['Colacion'];
    $Serie= $_POST['Serie'];
    $Dimensiones= $_POST['Dimensiones'];
    $AsientosSecundarios= $_POST['AsientosSecundarios'];
    $Nota= $_POST['Nota'];
    $copia= $_POST['copia'];
    $autorID3= $_POST['autorID3'] ?? null;; 
    $autorID4= $_POST['autorID4'] ?? null;; 
    $Resena= $_POST['Resena'];
    $autorID5= $_POST['autorID5'] ?? null;; 

    // Obtener la carátula existente antes de actualizar
$caratulaActual = $libroModelo-> obtenerCaratulaActual($id); // Esta función debe recuperar la carátula actual desde la BD

// Si no se sube un nuevo archivo, mantener la carátula actual
$Caratula = $caratulaActual; 

// Si se sube una nueva imagen, actualizar la ruta
if (isset($_FILES['Caratula']) && $_FILES['Caratula']['error'] === 0) {
    $carpetaDestino = "imagen/"; 
    $nombreArchivo = basename($_FILES['Caratula']['name']);
    $rutaArchivo = $carpetaDestino . $nombreArchivo;

    if (move_uploaded_file($_FILES['Caratula']['tmp_name'], $rutaArchivo)) {
        $Caratula = $rutaArchivo; // Se actualiza con la nueva imagen
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al subir la imagen.']);
        exit;
    }
}

    

    $resultado = $libroModelo->actualizarLibro($id, $IDLIB, $Codigo, $Titulo, $Existe, $autorID, 
         $Edicion, $Costo, $Fecha, $Estado,  
        $Origen,  $Editorial,  $Area,  $Medio,  $Clase, 
         $Observacion, $Seccion,  $Temas, $npag, $Fimpresion, 
        $Temas2, $Entrainv, $autorID2, 
        $ISBN, $Ciudad, $Colacion, $Serie, $Dimensiones, 
        $AsientosSecundarios, $Caratula, $Nota,$copia, 
        $autorID3, $autorID4,
    $Resena, $autorID5);

 
        if (!$resultado) {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el libro.']);
            exit;
        }
    

    // Verificar si se han enviado índices
if (!isset($_POST['idindice']) || !is_array($_POST['idindice']) || empty($_POST['idindice'])) {
    echo json_encode(['success' => true, 'message' => 'No se actualizaron índices porque no se recibieron.']);
    exit;
}

$idlibro = $_POST['id'] ?? null;
if (!$idlibro) {
    echo json_encode(['success' => false, 'message' => 'ID del libro no especificado.']);
    exit;
}

$indiceModelo = new misIndices();
$conexion = new Conexion();
$carpeta_destino = "indice/"; // Carpeta donde se guardarán las imágenes

foreach ($_POST['idindice'] as $i => $idindice) {
    $seccionnum = !empty($_POST['seccionnum'][$i]) ? $_POST['seccionnum'][$i] : null;
    $titulo = !empty($_POST['titulo'][$i]) ? $_POST['titulo'][$i] : null;
    $pagina = !empty($_POST['pagina'][$i]) ? $_POST['pagina'][$i] : null;
    $rutaImagen = null;

    if (!empty($_POST['eliminar_imagen'][$idindice])) {
        // Se eliminó la imagen
        $rutaImagen = null;
    } elseif (!empty($_FILES['imagenIndice']['name'][$i])) {
        // Se subió una nueva imagen
        $nombre_archivo = time() . "_" . basename($_FILES['imagenIndice']['name'][$i]);
        $ruta_final = $carpeta_destino . $nombre_archivo;

        if (move_uploaded_file($_FILES['imagenIndice']['tmp_name'][$i], $ruta_final)) {
            $rutaImagen = $ruta_final;
        }
    } else {
        // Consultar si hay imagen previa solo si existe ID válido
        $consulta = $conexion->prepare("SELECT imagen FROM indice WHERE idindice = ?");
        $consulta->execute([$idindice]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $rutaImagen = $resultado ? $resultado['imagen'] : null;
    }

    if (!empty($idindice)) {
        $resultadoIndice = $indiceModelo->actualizarIndice($idindice, $seccionnum, $titulo, $pagina, $rutaImagen);
        if (!$resultadoIndice) {
            error_log("❌ Error en actualizarIndice para ID: $idindice");
        } else {
            error_log("✅ Éxito en actualizarIndice para ID: $idindice");
        }
    }
 else {
        if ($seccionnum !== null || $titulo !== null || $pagina !== null || !empty($rutaImagen)) {
        $resultadoIndice = $indiceModelo->agregarIndice($idlibro, $seccionnum, $titulo, $pagina, $rutaImagen);
        if (!$resultadoIndice) {
            error_log("❌ Error en agregarIndice para ID libro: $idlibro");
        } else {
            error_log("✅ Éxito en agregarIndice para ID libro: $idlibro");
        }
    }
}

    if (!$resultadoIndice) {
        echo json_encode([
            'success' => false, 
            'message' => "Error al procesar el índice ID: $idindice",
            'debug' => [
                'idindice' => $idindice,
                'seccionnum' => $seccionnum,
                'titulo' => $titulo,
                'pagina' => $pagina,
                'imagen' => $rutaImagen
            ]
        ]);
        exit;
    }
}

echo json_encode(['success' => true, 'message' => 'Libro e índices actualizados correctamente.']);

} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>