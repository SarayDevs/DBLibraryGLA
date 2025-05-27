<?php
require_once '../Modelo/Datoslibros.php';
require_once '../Modelo/Datosindice.php';
require_once '../Modelo/Datoslibros.php'; 
require_once '../Modelo/Conexion.php'; 
require_once '../Modelo/Datosarea.php';
require_once '../Modelo/Datosautor.php';
require_once '../Modelo/Datosclase.php';
require_once '../Modelo/Datoseditorial.php';
require_once '../Modelo/Datosestado.php';
require_once '../Modelo/Datosmedio.php';
require_once '../Modelo/Datosorigen.php';
require_once '../Modelo/Datosseccion.php';
require_once '../Modelo/Datospersonas.php';
require_once '../Modelo/Datosprest.php';
require_once '../Modelo/Datosprestamos.php';
require_once '../Modelo/Datosactividad.php';

if (!isset($_GET['id'])) {
    echo "No se proporcionó un ID de libro.";
    exit;
}

$idLibro = $_GET['id'];
$libros = new misLibros();
$detalleLibro = $libros->verLibroID($idLibro);

if (empty($detalleLibro)) {
    echo "El libro no existe o no se encontró.";
    exit;
}


$autorModelo = new misAutores();
$libroModelo = new misLibros(); 
$seccionModelo = new misSecciones(); 
$origenModelo = new misOrigenes(); 
$medioModelo = new misMedios(); 
$estadoModelo = new misEstados(); 
$editorialModelo = new misEditoriales(); 
$claseModelo = new misClases(); 
$areaModelo= new misAreas();
$personaModelo= new misPersonas();
$prestModelo= new misPrestados();
$prestamosModelo= new misPrestamos();
$actividadesModelo= new misActividades();


$actividades = $actividadesModelo->verActividades();
$autores = $autorModelo->verAutores(); 
$secciones = $seccionModelo->verSecciones(); 
$origenes = $origenModelo->verOrigenes(); 
$medios = $medioModelo->verMedios(); 
$estados = $estadoModelo->verEstados(); 
$editoriales = $editorialModelo->verEditoriales(); 
$clases = $claseModelo->verClases(); 
$areas = $areaModelo->verAreas();
$personas= $personaModelo->verPersonas();
$prest= $prestModelo->verPrestado();
$prestamos=$prestamosModelo->verPrestamos();
$libross = $libroModelo->verLibroID(); 


$detalleLibro = $detalleLibro[0]; // Aseguramos que $detalleLibro contiene los datos del libro

// Obtener el nombre del segundo autor
$autor = $autorModelo->verAutorID($detalleLibro['autorID2']);
$nombreAutor = !empty($autor) ? $autor[0]['autores'] : 'Desconocido';

$autor1 = $autorModelo->verAutorID($detalleLibro['autorID']);
$nombreAutor1 = !empty($autor1) ? $autor1[0]['autores'] : 'Desconocido';

$estado = $estadoModelo->verEstadoID($detalleLibro['Estado']);
$nombreEstado = !empty($estado) ? $estado[0]['Etados'] : 'Desconocido';

$origen = $origenModelo->verOrigenID($detalleLibro['Origen']);
$nombreOrigen = !empty($origen) ? $origen[0]['origenes'] : 'Desconocido';

$editorial = $editorialModelo->verEditorialID($detalleLibro['Editorial']);
$nombreEditorial = !empty($editorial) ? $editorial[0]['editoriales'] : 'Desconocido';

$clase = $claseModelo->verClaseID($detalleLibro['Clase']);
$nombreClase = !empty($clase) ? $clase[0]['clases'] : 'Desconocido';

$medio = $medioModelo->verMedioID($detalleLibro['Medio']);
$nombreMedio = !empty($medio) ? $medio[0]['tipo'] : 'Desconocido';

$seccion = $seccionModelo->verSeccionID($detalleLibro['Seccion']);
$nombreSeccion = !empty($seccion) ? $seccion[0]['secciones'] : 'Desconocido';

$area = $areaModelo->verAreaID($detalleLibro['Area']);
$nombreArea = !empty($area) ? $area[0]['areas'] : 'Desconocido';

$prest=$prestModelo->verPrestadoID($detalleLibro['SiPrest']);
$nombrePrest = !empty($prest) ? $prest[0]['TipoP'] : 'Desconocido';


$indiceModelo = new misIndices();
$indices = $indiceModelo->verIndiceID($idLibro);



?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $detalleLibro['Titulo']; ?></title>
    <link rel="stylesheet" href="../Libreria/libreriacss.php">
    <link rel="stylesheet" href="../Libreria/libreriajs.php">
    <link rel="stylesheet" href="../Libreria/Modal.css">
    <link rel="stylesheet" href="../Libreria/detalleexterno.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

</head>
<body>
<div class="container">
    <div class="left-section">
        <h1><?php echo htmlspecialchars($detalleLibro['Titulo']); ?></h1>
        <div class="details">
            <p><strong>Autor:</strong> <?php echo htmlspecialchars($nombreAutor1); ?></p>
            <p><strong>Lugar:</strong> <?php echo htmlspecialchars($detalleLibro['Ciudad']); ?></p>
            <p><strong>Editorial:</strong> <?php echo htmlspecialchars($nombreEditorial); ?></p>
            <p><strong>Año de Publicación:</strong> <?php echo htmlspecialchars($detalleLibro['Fimpresion']); ?></p>
            <p><strong>Código:</strong> <?php echo htmlspecialchars($detalleLibro['Codigo']); ?></p>
            <p><strong>ISBN:</strong> <?php echo htmlspecialchars($detalleLibro['ISBN']); ?></p>
            <p><strong>Número de Páginas:</strong> <?php echo htmlspecialchars($detalleLibro['npag']); ?></p>
            <p><strong>Estado:</strong> <?php echo htmlspecialchars($nombreEstado); ?></p>
            <p><strong>Colección:</strong> <?php echo htmlspecialchars($nombreSeccion); ?></p>
            <p><strong>Temas:</strong> <?php echo htmlspecialchars($detalleLibro['Temas']); ?></p>
            <?php if (!empty($detalleLibro['Resena'])): ?>
    <p style="width: 500px;"><strong><?= htmlspecialchars($detalleLibro['Resena']); ?></strong></p>
<?php else: ?>
    <p style="width: 500px;"><strong>No digitada</strong></p>
<?php endif; ?></p>
        </div>
      
        <a href="javascript:history.back()" class="back-link">Volver</a>
    </div>

    <div class="right-section">
        <h1>Carátula</h1>
        <img src="http://localhost/MVC/Controlador/<?php echo htmlspecialchars($detalleLibro['Caratula']); ?>" 
         alt="<?php echo htmlspecialchars($detalleLibro['Titulo']); ?>" 
         width="200" height="375" 
         onerror="this.onerror=null; this.src='http://localhost/MVC/Controlador/imagen/NOIMAGE.jpg';">


</div>

</div>
<div class="indice-container">
    <?php if (!empty($indices)): ?>
        <h2>Índice</h2>
        <table class="indice-table">
            <thead>
                <tr>
                    <th>Sección</th>
                    <th>Título</th>
                    <th>Página</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($indices as $indice): ?>
                <tr>
                    <td><?= htmlspecialchars($indice['seccionnum'] ?? 'NA'); ?></td>
                    <td><?= htmlspecialchars($indice['titulo'] ?? 'NA'); ?></td>
                    <td><?= htmlspecialchars($indice['pagina'] ?? 'NA'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay información disponible.</p>
    <?php endif; ?>
</div>

<?php if (!empty($indices)): ?>
    <div class="indice-imagenes">
       
        <?php foreach ($indices as $indice): ?>
            <?php if (!empty($indice['imagen'])): ?>
                <img src="<?php echo 'http://localhost/MVC/Controlador/' . $indice['imagen']; ?>" 
                     alt="Índice en imagen" 
                     onerror="this.onerror=null; this.src='http://localhost/MVC/Controlador/imagen/NOIMAGE.jpg';">
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

</div>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper('.swiper-container', {
        loop: true,
        spaceBetween: 20,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });
</script>

</body>
</html>