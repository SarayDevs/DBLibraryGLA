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
include('../App/Inde.php'); 
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

$autor3 = $autorModelo->verAutorID($detalleLibro['autorID3']);
$nombreAutor3 = !empty($autor3) ? $autor3[0]['autores'] : 'Desconocido';

$autor4 = $autorModelo->verAutorID($detalleLibro['autorID4']);
$nombreAutor4 = !empty($autor4) ? $autor4[0]['autores'] : 'Desconocido';
$autor5 = $autorModelo->verAutorID($detalleLibro['autorID5']);
$nombreAutor5 = !empty($autor4) ? $autor5[0]['autores'] : 'Desconocido';

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
    <title>Detalle del Libro</title>
    <?php include '../Libreria/libreriacss.php'; ?>
    <?php include '../Libreria/libreriajs.php'; ?>
    <link rel="stylesheet" href="../Libreria/Modal.css">
    <link rel="stylesheet" href="../Libreria/detalles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script>
        (function () {
            const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            if (isCollapsed) {
                document.documentElement.classList.add('sidebar-initial-collapsed');
            }
        })();
    </script>
</head>
<body>
<div class="container">

    
<div class="left-section">

    <h1><?php echo $detalleLibro['Titulo']; ?></h1>

    <div class="details">
    <p><strong>Item: </strong> <?php echo $detalleLibro['ID']; ?></p>
    <p><strong>Código: </strong> <?php echo $detalleLibro['IDLIB']; ?></p>
    <p><strong>Signatura: </strong> <?php echo $detalleLibro['Codigo']; ?></p>
    <p><strong>Autor: </strong> <?php echo $nombreAutor1; ?></p>
    <p><strong>Autor Corporativo: </strong> <?php echo $nombreAutor; ?></p>
    <p><strong>Otros Autores: </strong> <?php echo $nombreAutor3," ; ", $nombreAutor4," ; ", $nombreAutor5,"."; ?></p>
    <p><strong>Título: </strong> <?php echo $detalleLibro['Titulo']; ?></p>
    <p><strong>Mención de responsabilidad: </strong> <?php echo $detalleLibro['Temas2']; ?></p>
    <p><strong>ISBN: </strong><?php echo $detalleLibro['ISBN']; ?></p>
    <p><strong>Existencia física: </strong> <?php echo $detalleLibro['Existe']; ?></p>
    <p><strong>En inventario: </strong> <?php echo $detalleLibro['Entrainv']; ?></p>
    <p><strong>Ejemplar(es): </strong> <?php echo $detalleLibro['copia']; ?></p>
    <p><strong>Edición: </strong> <?php echo $detalleLibro['Edicion']; ?></p>
    <p><strong>Pie de imprenta: </strong><?php echo $detalleLibro['Ciudad']," : ", $nombreEditorial,", ",$detalleLibro['Fimpresion'],"." ; ?></p>
    <p><strong>Descripción: </strong><?php echo $detalleLibro['Colacion']," ; ",$detalleLibro['npag']," ; ",$detalleLibro['Dimensiones'],"."; ?></p>
    <p><strong>Serie: </strong><?php echo $detalleLibro['Serie']; ?></p>
    <p><strong>Notas: </strong><?php echo $detalleLibro['Nota']; ?></p>
    <p><strong>Estado: </strong> <?php echo $nombreEstado; ?></p>
    <p><strong>Fecha de Adquisición: </strong> <?php echo $detalleLibro['Fecha']; ?></p>
    <p><strong>Costo: </strong> <?php echo $detalleLibro['Costo']; ?></p>
    <p><strong>VÍa de ingreso: </strong> <?php echo $nombreOrigen; ?></p>
    <p><strong>Ubicación local: </strong> <?php echo $nombreArea; ?></p>
    <p><strong>Medio: </strong> <?php echo $nombreMedio; ?></p>
    <p><strong>Soporte: </strong> <?php echo $nombreClase; ?></p>
    <p><strong>Tipo de Colección: </strong> <?php echo $nombreSeccion; ?></p>
    <p><strong>Descriptores: </strong> <?php echo $detalleLibro['Temas']; ?></p>
    <p><strong>Asientos secundarios: </strong><?php echo $detalleLibro['AsientosSecundarios']; ?></p>
    <p><strong>Observaciónes: </strong> <?php echo $detalleLibro['Observacion']; ?></p>
    <p><strong>Reseña: </strong> <?php echo $detalleLibro['Resena']; ?></p>
    </div><a href="javascript:history.back()" class="back-link">Volver a la página anterior</a>
    <a href="Actualizar.php?id=<?php echo $detalleLibro['ID']; ?>" class="back-link">Editar</a> 
    </div>

    <div class="right-section">
        <h1>Carátula</h1>
        <img src="http://localhost/MVC/Controlador/<?php echo htmlspecialchars($detalleLibro['Caratula']); ?>" 
         alt="<?php echo htmlspecialchars($detalleLibro['Titulo']); ?>" 
         width="200" height="500" 
         onerror="this.onerror=null; this.src='http://localhost/MVC/Controlador/imagen/NOIMAGE.jpg';">
<br><br><br>
        
<br>
<?php if (!empty($indices)): ?>
    <h2>Índice</h2>
    <br>
    <table>
        <tr>
            <th>Sección</th>
            <th style="width: 280px;">Título</th>
            <th>Página</th>
        </tr>
        <?php foreach ($indices as $indice): ?>
        <tr>
            <td style="width: 60px;"><?= htmlspecialchars($indice['seccionnum'] ?? ''); ?></td>
            <td style="width: 280px;"><?= htmlspecialchars($indice['titulo'] ?? ''); ?></td>
            <td style="width: 60px;"><?= htmlspecialchars($indice['pagina'] ?? ''); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Ahora mostramos todas las imágenes debajo de la tabla -->
    <div>
        <?php foreach ($indices as $indice): ?>
            <?php if (!empty($indice['imagen'])): ?>
                <img src="<?php echo 'http://localhost/MVC/Controlador/' . $indice['imagen']; ?>" 
                     alt="Índice en imagen" width="150" style="display: block; margin: 10px 0;">
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

</div>
    
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.querySelector('.toggle-btn');

    // Configura el estado inicial del menú desde localStorage
    const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
    if (isCollapsed) {
        sidebar.classList.add('collapsed');
    }

    // Elimina la clase inicial una vez que el documento está cargado
    document.documentElement.classList.remove('sidebar-initial-collapsed');

    // Alternar el menú al hacer clic en el botón
    toggleBtn.addEventListener('click', function () {
        sidebar.classList.toggle('collapsed');
        const isNowCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebar-collapsed', isNowCollapsed);
    });
});
</script>
</body>
</html>