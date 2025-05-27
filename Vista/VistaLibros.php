<?php
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


session_start();
if (isset($_GET['filtros'])) {
    $_SESSION['filtros'] = $_GET['filtros'];
}

$libroModelo = new misLibros(); 
$autorModelo = new misAutores(); 
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

$limite = 20;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $limite;

$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
$filtroSeleccionado = isset($_GET['filtros']) && $_GET['filtros'] !== '' ? htmlspecialchars($_GET['filtros'], ENT_QUOTES, 'UTF-8') : null;
$filtro_area = isset($_GET['filtro_area']) ? $_GET['filtro_area'] : '';

// Valores por defecto
$filtro_area = isset($_GET['filtro_area']) ? $_GET['filtro_area'] : '';
if ($filtro_area == 'bilingue') {
    // Activa filtro bilingüe
    $filtro_bilingue = true;
    $signatura_min = null; // Desactiva rango numérico
    $signatura_max = null;
} elseif  (!empty($filtro_area) && $filtro_area !== '') {
    // Extraer los valores mínimos y máximos del rango
    $rangos = explode('-', $filtro_area);
    $signatura_min = isset($rangos[0]) ? intval($rangos[0]) : 0;
    $signatura_max = isset($rangos[1]) ? intval($rangos[1]) : 999;
    $filtro_bilingue = false;
} else {
    // Si es "todas las áreas" (o vacío), se asigna el rango completo
    $signatura_min = 0;
    $signatura_max = 999;
    $filtro_bilingue = false;
}

// Ahora sí, llamamos las funciones con los rangos correctos
$libros = $libroModelo->verLibros2($limite, $offset, $busqueda,  $signatura_min, $signatura_max, $filtroSeleccionado, $filtro_bilingue);
$totalLibros = $libroModelo->contarLibros2($busqueda, $signatura_min, $signatura_max, $filtroSeleccionado, $filtro_bilingue);
$totalPaginas = ceil($totalLibros / $limite);




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

?>

<?php if (isset($_GET['mensaje'])): ?>
<p><?php echo $_GET['mensaje']; ?></p>
<?php endif; ?>

<!DOCTYPE html>
<lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Listado de Libros</title>

        <?php include '../Libreria/libreriacss.php'; ?>
        <?php include '../Libreria/libreriajs.php'; ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="../Libreria/Modal.css">
        <link rel="stylesheet" href="../Libreria/ver.css">
        <script src="../Controlador/Controprest.js"></script>
        <script src="../Controlador/Controeliminar.js"></script>

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
        <div class="sidebar">
        
            <h2>Panel de Control</h2>

            <ul>
            <li><a href="../Vista/VistaLibros.php" class="active">Libros</a></li>
            <li><a href="../Vista/VistaPeriodicos.php">Periodicos</a></li>
            <li><a href="../Vista/VistaRevistas.php">Revistas</a></li>
                <li><a href="../Vista/VistaPrestamos.php">Prestamos</a></li>
                <li><a href="../Vista/Insertar.php" >Insertar Libro</a></li>
                <li><a href="../Vista/Insertrevista.php" >Insertar Revista</a></li>
                <li><a href="../Vista/Insertperiodico.php" >Insertar Periodico</a></li>
            <br>
            <img src="../imagen/logo-blanco.png" alt="Logo de la Biblioteca" class="logo">
            </ul>
        </div>

        <div class="content">
            <button class="toggle-btn" >☰</button>
           
            <div class="container">
            <a href="VistaRevistas.php" class="back-link">Revistas</a> 
            <a href="VistaPeriodicos.php" class="back-link">Periodicos</a>
                <h1><strong>Listado de Libros</strong></h1>
                <a href="../Controlador/exportarLibros.php" class="btn btn-success">📥 Exportar Libros a Excel</a>
          
                <div class="search_bar">
                    <form method="GET" action="VistaLibros.php">
                        <input type="text" name="busqueda"
                            placeholder="Puedes buscar por ID, códigos, título, autor y editorial. "
                            value="<?php echo isset($_GET['busqueda']) ? htmlspecialchars($_GET['busqueda']) : ''; ?>">

                        <input type="hidden" name="filtros"
                            value="<?php echo isset($_GET['filtros']) ? htmlspecialchars($_GET['filtros']) : ''; ?>">
                        <input type="hidden" name="pagina" value="1">
<div class="filtro-area">
                        <select name="filtro_area" class="filtro-area">
        <option value="">Todas las áreas</option>
        <option value="bilingue" <?php if(isset($_GET['filtro_area']) && $_GET['filtro_area'] == 'bilingue') echo 'selected'; ?>>Literatura Bilingüe (LB/BL)</option>
        <option value="00-99" <?php if(isset($_GET['filtro_area']) && $_GET['filtro_area'] == '00-99') echo 'selected'; ?>>00-99 Generalidades</option>
        <option value="100-199" <?php if(isset($_GET['filtro_area']) && $_GET['filtro_area'] == '100-199') echo 'selected'; ?>>100-199 Filosofía y Psicología</option>
        <option value="200-299" <?php if(isset($_GET['filtro_area']) && $_GET['filtro_area'] == '200-299') echo 'selected'; ?>>200-299 Religión</option>
        <option value="300-399" <?php if(isset($_GET['filtro_area']) && $_GET['filtro_area'] == '300-399') echo 'selected'; ?>>300-399 Ciencias Sociales</option>
        <option value="400-499" <?php if(isset($_GET['filtro_area']) && $_GET['filtro_area'] == '400-499') echo 'selected'; ?>>400-499 Lenguas</option>
        <option value="500-599" <?php if(isset($_GET['filtro_area']) && $_GET['filtro_area'] == '500-599') echo 'selected'; ?>>500-599 Ciencias Naturales</option>
        <option value="600-699" <?php if(isset($_GET['filtro_area']) && $_GET['filtro_area'] == '600-699') echo 'selected'; ?>>600-699 Tecnología</option>
        <option value="700-799" <?php if(isset($_GET['filtro_area']) && $_GET['filtro_area'] == '700-799') echo 'selected'; ?>>700-799 Arte</option>
        <option value="800-899" <?php if(isset($_GET['filtro_area']) && $_GET['filtro_area'] == '800-899') echo 'selected'; ?>>800-899 Literatura</option>
        <option value="900-999" <?php if(isset($_GET['filtro_area']) && $_GET['filtro_area'] == '900-999') echo 'selected'; ?>>900-999 Historia y Geografía</option>
    </select></div>
                        <input type="hidden" name="pagina" value="1">

                        <button type="submit" class="btn buscar-btn">Buscar</button>
                    </form>
                </div>
            
                <div class="filtros">
                    <form name="filtros" method="GET" action="VistaLibros.php">
                        <label for="filtros">Actividad:</label>
                        <select id="filtros" name="filtros" onchange="this.form.submit()">
                            <option value="">Filtrar por actividad</option>
                            <?php foreach ($actividades as $actividad) : ?>
                            <option
                                value="<?php echo htmlspecialchars($actividad['IDposibles'], ENT_QUOTES, 'UTF-8'); ?>"
                                <?php echo isset($_GET['filtros']) && $_GET['filtros'] == $actividad['IDposibles'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($actividad['disponibilidad'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>

                        <input type="hidden" name="busqueda"
                            value="<?php echo isset($_GET['busqueda']) ? htmlspecialchars($_GET['busqueda']) : ''; ?>">
                        <input type="hidden" name="pagina" value="1">

                    </form>
                </div>

                <button type="button" class="btn agregar-btn" onclick="window.location.href='Insertar.php?pagina=<?php echo $paginaActual; ?>';">Agregar Nuevo Libro</button>

<div class="pagination">
                    <?php

$rangoInicio = max(1, $paginaActual - 2); 
$rangoFin = min($totalPaginas, $paginaActual + 2); 


if ($rangoFin - $rangoInicio < 4) {
    if ($rangoInicio > 1) {
        $rangoInicio = max(1, $rangoInicio - (4 - ($rangoFin - $rangoInicio)));
    } else {
        $rangoFin = min($totalPaginas, $rangoFin + (4 - ($rangoFin - $rangoInicio)));
    }
}

function crearEnlace($pagina, $paginaActual, $busqueda = '', $filtroSeleccionado = '', $texto = null, $filtro_area = '') {
    if (isset($_GET['busqueda']) && empty($busqueda)) {
        $busqueda = $_GET['busqueda'];
    }
    if (isset($_GET['filtros']) && empty($filtroSeleccionado)) {
        $filtroSeleccionado = $_GET['filtros'];
    }
    if (empty($filtro_area) && isset($_GET['filtro_area'])) {
        $filtro_area = $_GET['filtro_area'];
    }

    $queryParams = http_build_query([
        'pagina' => $pagina,
        'busqueda' => $busqueda,
        'filtros' => $filtroSeleccionado,
        'filtro_area' => $filtro_area
    ]);


    $estilo = $pagina == $paginaActual ? 'class="pagina-actual"' : '';
    $texto = $texto ?? $pagina; 

    return "<a href='?{$queryParams}' {$estilo}>{$texto}</a>";
}
?>
                    <?php if ($paginaActual > 1): ?>
                    <?= crearEnlace(1, $paginaActual, $busqueda, $filtroSeleccionado, 'Primero', $filtro_area) ?>
                    <?= crearEnlace($paginaActual - 1, $paginaActual, $busqueda, $filtroSeleccionado, 'Anterior', $filtro_area) ?>
                    <?php endif; ?>

                    <?php for ($i = $rangoInicio; $i <= $rangoFin; $i++): ?>
                    <?= crearEnlace($i, $paginaActual, $busqueda, $filtroSeleccionado, null, $filtro_area) ?>
                    <?php endfor; ?>

                    <?php if ($paginaActual < $totalPaginas): ?>
                    <?= crearEnlace($paginaActual + 1, $paginaActual, $busqueda, $filtroSeleccionado, 'Siguiente', $filtro_area) ?>
                    <?= crearEnlace($totalPaginas, $paginaActual, $busqueda, $filtroSeleccionado, 'Último', $filtro_area) ?>
                    <?php endif; ?>
                </div>

                <?php $librosUnicos = [];
foreach ($libros as $libro) {
    if (!isset($librosUnicos[$libro['ID']])) {
        $librosUnicos[$libro['ID']] = $libro;
    }
}
if (count($libros) > 0): ?>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="min-width: 100px; max-width: 100px;"><strong>Préstamos</strong></th>
                                <th style="min-width: 50px;  max-width: 50px"><strong>Item</strong></th>
                                <th style="min-width: 115px;  max-width:115px"><strong>Código</strong></th>
                                <th style="min-width: 130px;  max-width: 130px"><strong>Signatura</strong></th>
                                <th style="min-width: 115px;  max-width:115px"><strong>Actividad</strong></th>
                                <th style="min-width: 250px;  max-width:250px"><strong>Título</strong></th>
                                <th style="min-width: 150px;  max-width:150px"><strong>Autor</strong></th>
                                <th style="min-width: 150px; max-width:150px" ><strong>Responsabilidad</strong></th>
                                <th style="min-width: 160px; max-width:160px"><strong>ISBN</strong></th>
                                <th style="min-width: 70px; max-width:70px"><strong>Editorial</strong></th>
                                <th style="min-width: 40px; max-width:40px" ><strong> Estado</strong></th>
                                <th style="min-width: 100px; max-width:100px"><strong>Colección</strong></th>
                                <th style="min-width: 70px; max-width:70px"><strong>Ubicación</strong></th>
                                <th style="min-width: 80px; max-width:80px"><strong>Soporte</strong></th>
                                <th style="min-width: 300px; max-width:350px"><strong>Descriptores</strong></th>
                                <th style="min-width: 50px ; max-width:50px" ><strong>Acciones</strong></th>
                            </tr>
                        </thead>
                        <tbody>
<?php 
               foreach ($librosUnicos as $libro): 

                $actividad = $actividadesModelo->verActividadID($libro['Actividad']);
                $nombreActividad=!empty($actividad) ? $actividad [0]['disponibilidad']:'Desconocido';

                $autor = $autorModelo->verAutorID($libro['autorID']);
                $nombreAutor = !empty($autor) ? $autor[0]['autores'] : 'Desconocido';
                
                $estado = $estadoModelo->verEstadoID($libro['Estado']);
                $nombreEstado = !empty($estado) ? $estado[0]['Etados'] : 'Desconocido';

                $origen = $origenModelo->verOrigenID($libro['Origen']);
                $nombreOrigen = !empty($origen) ? $origen[0]['origenes'] : 'Desconocido';

                $editorial = $editorialModelo->verEditorialID($libro['Editorial']);
                $nombreEditorial = !empty($editorial) ? $editorial[0]['editoriales'] : 'Desconocido';

                $clase = $claseModelo->verClaseID($libro['Clase']);
                $nombreClase = !empty($clase) ? $clase[0]['clases'] : 'Desconocido';

                $medio = $medioModelo->verMedioID($libro['Medio']);
                $nombreMedio = !empty($medio) ? $medio[0]['tipo'] : 'Desconocido';

                $seccion = $seccionModelo->verSeccionID($libro['Seccion']);
                $nombreSeccion = !empty($seccion) ? $seccion[0]['secciones'] : 'Desconocido';

                $area = $areaModelo->verAreaID($libro['Area']);
                $nombreArea = !empty($area) ? $area[0]['areas'] : 'Desconocido';

                $prest=$prestModelo->verPrestadoID($libro['SiPrest']);
                $nombrePrest = !empty($prest) ? $prest[0]['TipoP'] : 'Desconocido';

                $prest = $prestModelo->verPrestadoID($libro['SiPrest']);
                $siPrest = $libro['SiPrest'];
                $color = ($siPrest == 0) ? '#2ecf74' : 'red';
?>
                            <tr>
                                <td style="min-width: 100px; max-width: 100px; text-align: center; vertical-align: middle;">
                                    <div onclick="openModal(
            'prestamoModal', 
            <?php echo htmlspecialchars($libro['ID'], ENT_QUOTES, 'UTF-8'); ?>, 
            '<?php echo htmlspecialchars($libro['Titulo'], ENT_QUOTES, 'UTF-8'); ?>',
            <?php echo $libro['SiPrest']; ?>)" id="cuadri"
                                        style="width: 28px; height: 28px; display: inline-flex; justify-content: center; align-items: center; background-color:  <?php echo $color; ?>; cursor: pointer;"
                                        title="<?php echo htmlspecialchars($nombrePrest, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <input type="hidden" id="tipoMaterial" name="tipoMaterial" value="libro"> <!-- libro, revista, periodico -->

                                </td>
                                <td style="min-width: 75px; max-width: 75px; font-size:15px; background-color: <?php echo isset($libro['coincidencia_indice']) && $libro['coincidencia_indice'] ? 'yellow' : 'transparent'; ?>">
                                <strong><?php echo htmlspecialchars($libro['ID']); ?></strong>
</td>
                                <td style="min-width: 115px; max-width: 115px;"><?php echo $libro['IDLIB']; ?></td>
                                <td style="min-width: 130px; max-width: 130px;"><?php echo $libro['Codigo']; ?></td>

                                <td style="min-width: 115px; max-width: 115px;">

                                    <button type="button" class="btn btn-info btn-small"
                                        onclick="openModal('ActividadModal', <?php echo htmlspecialchars($libro['ID'], ENT_QUOTES, 'UTF-8'); ?>)">
                                        <?php echo $nombreActividad; ?>
                                    </button>
                                </td>
                                <td style="min-width: 100px; max-width: 100px;">
                                    <a href="VistaDetalleLibro.php?id=<?php echo $libro['ID']; ?>">
                                        <?php echo $libro['Titulo']; ?>
                                </td>
                                <td style="min-width: 100px; max-width: 150px;"><?php echo $nombreAutor; ?></td>
                                <td style="min-width: 100px; max-width: 150px;"><?php echo $libro['Temas2']; ?></td>
                                <td style="min-width: 160px; max-width: 160px;"><?php echo $libro['ISBN']; ?></td>
                                <td style="min-width: 140px; max-width: 140px;"><?php echo $nombreEditorial; ?></td>
                                <td style="min-width: 100px; max-width: 100px;"><?php echo $nombreEstado; ?></td>
                                <td style="min-width: 120px; max-width: 120px;"><?php echo $nombreSeccion; ?></td>
                                <td style="min-width: 130px; max-width: 130px;"><?php echo $nombreArea; ?></td>
                                <td style="min-width: 120px; max-width: 120px;"><?php echo $nombreClase; ?></td>
                                <td style="min-width: 100px; max-width:400px"><?php echo $libro['Temas']; ?></td>
                                <td style="min-width: 85px; max-width: 85px; background-color: <?php echo isset($libro['coincidencia_indice']) && $libro['coincidencia_indice'] ? 'yellow' : 'transparent'; ?>">
                                    <a href="Actualizar.php?id=<?php echo $libro['ID']; ?>">Editar</a> |
                                    <a href="Eliminar.php?id=<?php echo $libro['ID']; ?>" class="eliminar-libro"
                                        onclick="">Eliminar</a>

                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
<?php

    $rangoInicio = max(1, $paginaActual - 2); 
    $rangoFin = min($totalPaginas, $paginaActual + 2); 

    if ($rangoFin - $rangoInicio < 4) {
      if ($rangoInicio > 1) {
            $rangoInicio = max(1, $rangoInicio - (4 - ($rangoFin - $rangoInicio)));
     } else {
            $rangoFin = min($totalPaginas, $rangoFin + (4 - ($rangoFin - $rangoInicio)));
     }
    }

?>

                    <?php if ($paginaActual > 1): ?>
                    <?= crearEnlace(1, $paginaActual, $busqueda, $filtroSeleccionado, 'Primero') ?>
                    <?= crearEnlace($paginaActual - 1, $paginaActual, $busqueda, $filtroSeleccionado, 'Anterior') ?>
                    <?php endif; ?>

                    <?php for ($i = $rangoInicio; $i <= $rangoFin; $i++): ?>
                    <?= crearEnlace($i, $paginaActual, $busqueda, $filtroSeleccionado) ?>
                    <?php endfor; ?>

                    <?php if ($paginaActual < $totalPaginas): ?>
                    <?= crearEnlace($paginaActual + 1, $paginaActual, $busqueda, $filtroSeleccionado, 'Siguiente') ?>
                    <?= crearEnlace($totalPaginas, $paginaActual, $busqueda, $filtroSeleccionado, 'Último') ?>
                    <?php endif; ?>
                </div>

                <center>
                    <div id="prestamoModal" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeModal('prestamoModal')">&times;</span>
                            <h2 id="modalTitulo">Préstamo del Libro</h2>
                            <p></p>

                            <div id="agregarPrestamoCampos">
                                <form id="form-prestamo">

                                    <table class="table-containerr">
                                        <input type="hidden" id="libprest" name="libprest" readonly>
                                        <tr>
                                            <th><label for="idLibro">ID del Libro:</label></th>
                                            <td><input type="text" id="idLibro" name="idLibro" readonly></td>
                                        </tr>

                                        <tr>
                                            <th><label for="tituloLibro">Título del Libro:</label></th>
                                            <td><input type="text" id="tituloLibro" name="tituloLibro" value="" required
                                                    readonly></td>
                                        </tr>
                                        <tr>
                                            <th><label for="nombrep">Nombre:</label></th>
                                            <td><input type="text" id="nombrep" name="nombrep" required></td>
                                        </tr>
                                        <tr>
                                            <th><label for="tipoperson">Tipo de Persona:</label></th>
                                            <td>
                                                <select id="tipoperson" name="tipoperson" required>
                                                    <option value="">Seleccionar persona</option>
                                                    <?php foreach ($personas as $persona) : ?>
                                                    <option
                                                        value="<?php echo htmlspecialchars($persona['idpersons'], ENT_QUOTES, 'UTF-8'); ?>">
                                                        <?php echo htmlspecialchars($persona['TipoPersons'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                        <th><label for="tipoPrestamo">Tipo de Préstamo:</label></th>
                        <td>
                            <select id="tipoPrestamo" name="tipoPrestamo" onchange="mostrarCamposExternos()" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="0">Interno</option>
                                <option value="1">Externo</option>
                            </select>
                        </td>
                    </tr>
                    <tr id="filaContacto" style="display: none;">
                        <th><label for="contacto">Contacto:</label></th>
                        <td><input type="text" id="contacto" name="contacto"></td>
                    </tr>
                    <tr id="filaTiempo" style="display: none;">
                        <th><label for="tiempo">Días de préstamo:</label></th>
                        <td><input type="number" id="tiempo" name="tiempo" min="1"></td>
                    </tr>
                                        <tr>
                                            <th><label for="fecha">Fecha:</label></th>
                                            <td><input type="date" id="fecha" name="fecha" required></td>
                                        </tr>
                                    </table>
                                    <button type="button" class="buttonmodal" id="botonAgregarPrestamo"
                                        >Agregar Préstamo</button>

                            </div>
                            <div id="detallesPrestamo" style="display: none;">
                                <h4>Detalles del Préstamo</h4>
                                <input type="hidden" id="idPrestamo" value="">

                                <table class="table-containerr">
                                    <thead>

                                        <tr>
                                            <th>Préstamo Nº</th>

                                            <th>ID Libro</th>

                                            <th>Título</th>

                                            <th>Estado</th>

                                            <th>Nombre</th>

                                            <th>Tipo de Persona</th>

                                            <th>Fecha de Préstamo</th>

                                            <th>Contacto</th>

                                            <th>Días restantes</th>

                                        </tr>
                                    </thead>
                                    <tbody id="contenidoPrestamo">
                                    </tbody>
                                </table>
                                <button type="button" class="buttonmodal" id="botonDevolverPrestamo" style="display: none;" onclick="EliminarPrestamos(0)">Marcar como Devuelto</button>
                                <button type="button" class="buttonmodal" id="botonExtenderPrestamo" style="display: none;" onclick="extenderPrestamo()" >Extender Tiempo</button>

                            </div>
                        </div>
                    </div>
                </center>
                <div id="ActividadModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal('ActividadModal')">&times;</span>
                        <h2>Cambiar Actividad</h2>
                        <form id="form-autor">
                            <label for="idescondido">Id: </label>
                            <input type="text" id="idescondido" name="idescondido" value="" required readonly>


                            <label for="nuevaActividad">Seleccionar actividad:</label>

                            <select id="tipoactividad" name="tipoactividad" required>
                                <option value="">Seleccionar Actividad</option>
                                <?php foreach ($actividades as $actividad) : ?>
                                <option
                                    value="<?php echo htmlspecialchars($actividad['IDposibles'], ENT_QUOTES, 'UTF-8'); ?>"
                                    <?php echo ($libro['Actividad'] == $actividad['IDposibles']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($actividad['disponibilidad'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>

                            <button type="button" class="buttonmodal" id="botonactividad"
                                onclick="actualizarActividad()">Cambiar Actividad</button>
                        </form>
                    </div>
                </div>
            </div>

            <?php else: ?>
            <p>No hay libros disponibles.</p>
            <div class="table-container">
                <center>
                    <table class="table">
                        <thead>
                            <tr>
                                <center>
                                <th style="min-width: 100px; max-width: 100px;"><strong>Préstamos</strong></th>
                                <th style="min-width: 40px;  max-width: 40px"><strong>Item</strong></th>
                                <th style="min-width: 60px;  max-width:60px"><strong>Código</strong></th>
                                <th style="min-width: 90px;  max-width: 90px"><strong>Signatura</strong></th>
                                <th style="min-width: 115px;  max-width:115px"><strong>Actividad</strong></th>
                                <th style="min-width: 250px;  max-width:250px"><strong>Título</strong></th>
                                <th style="min-width: 150px;  max-width:150px"><strong>Autor</strong></th>
                                <th style="min-width: 150px; max-width:150px" ><strong>Responsabilidad</strong></th>
                                <th style="min-width: 40px; max-width:40px" ><strong> Estado</strong></th>
                                <th style="min-width: 70px; max-width:70px"><strong>Editorial</strong></th>
                                <th style="min-width: 70px; max-width:70px"><strong>Ubicación</strong></th>
                                <th style="min-width: 40px; max-width:40px"><strong>Medio</strong></th>
                                <th style="min-width: 80px; max-width:80px"><strong>Soporte</strong></th>
                                <th style="min-width: 100px; max-width:100px"><strong>Colección</strong></th>
                                <th style="min-width: 300px; max-width:300px"><strong>Descriptores</strong></th>
                                <th style="min-width: 50px ; max-width:50px" ><strong>Acciones</strong></th>
                            </tr>
                </center>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo " No existe ---- "; ?></td>
                        <td><?php echo " No existe -- "; ?></td>
                        <td><?php echo " No existe --- "; ?></td>
                        <td><?php echo " No existe --- "; ?></td>
                        <td><?php echo " No existe -- "; ?></td>
                        <td><?php echo " No existe -- "; ?></td>
                        <td><?php echo " No existe --- "; ?></td>
                        <td><?php echo " No existe --- "; ?></td>
                        <td><?php echo " No existe --- "; ?></td>
                        <td><?php echo " No existe --- "; ?></td>
                        <td><?php echo " No existe --- "; ?></td>
                        <td><?php echo " No existe --- "; ?></td>
                        <td><?php echo " No existe --- "; ?></td>
                        <td><?php echo " No existe --- "; ?></td>
                        <td><?php echo " No existe --- "; ?></td>
                        <td><?php echo " No existe --- "; ?></td>
                    </tr>
                </tbody>
                </table>
                </center>
                <?php endif; ?>
            </div>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

            <script>
            $(document).ready(function() {
                $('select').select2();




                document.querySelector('.search_bar form').addEventListener('submit', function(event) {
                    const busqueda = document.querySelector('[name="busqueda"]').value;
                    actualizarParametros(event, 'busqueda', busqueda);
                });

                document.querySelector('.filtros form').addEventListener('submit', function(event) {
                    event.preventDefault();

                    const filtros = document.querySelector('[name="filtros"]').value;
                    const busqueda = document.querySelector('[name="busqueda"]').value;

                    const url = new URL(window.location.href);
                    url.searchParams.set('filtros', filtros);
                    url.searchParams.set('busqueda', busqueda);
                    url.searchParams.set('pagina', 1);

                    window.location.href = url.toString();
                });
            });

            function abrirVentana(siPrest) {
                const url = 'detallesPrestamo.php?SiPrest=' + siPrest;
                window.open(url, '_blank', 'width=400,height=400');
            };

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
<script>
    function mostrarCamposExternos() {
    const tipoPrestamo = document.getElementById('tipoPrestamo').value;
    const filaContacto = document.getElementById('filaContacto');
    const filaTiempo = document.getElementById('filaTiempo');

    if (tipoPrestamo === "1") { // Externo
        filaContacto.style.display = "table-row";
        filaTiempo.style.display = "table-row";
    } else { // Interno
        filaContacto.style.display = "none";
        filaTiempo.style.display = "none";
    }
}

function extenderPrestamo() {
    let idPrestamo = document.getElementById('idPrestamo').value;
    let diasExtra = prompt("Ingrese la cantidad de días adicionales:");

    if (diasExtra && !isNaN(diasExtra) && diasExtra > 0) {
        $.ajax({
            url: '../Controlador/extenderPrestamo.php',
            method: 'POST',
            data: { idPrestamo: idPrestamo, diasExtra: diasExtra }, // ✅ Variable corregida
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert("El préstamo ha sido extendido exitosamente.");
                    location.reload(); // Recargar la página para actualizar la información
                } else {
                    alert("Error: " + response.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error al extender el préstamo:", textStatus, errorThrown);
            }
        });
    } else {
        alert("Ingrese un número válido de días.");
    }
}


</script>

            

    </body>

    </html>