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
$libros = $libroModelo->verLibros1($limite, $offset, $busqueda, $filtroSeleccionado);
$totalLibros = $libroModelo->contarLibros1($busqueda, $filtroSeleccionado);
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
$libross = $libroModelo->verLibroID(); 
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
        <script src="../Controlador/Controprest.js"></script>
        <script src="../Controlador/Controeliminar.js"></script>
        <style>
        body {
    font-family: Arial, sans-serif;
    font-size: 14px;
    background-color: #f7f8fa;
    color: #333;
    margin: 0;
    padding: 1px;
    background-color: #e5e5f7;
    background-image:  linear-gradient(#6fdeab 3px, transparent 3px), linear-gradient(90deg, #6fdeab 3px, transparent 3px), linear-gradient(#6fdeab 1.5px, transparent 1.5px), linear-gradient(90deg, #6fdeab 1.5px, #e5e5f7 1.5px); /*color blanco un poquito gris: #f6f6f6. Color un poco azulado: #e5e5f7 */ 
    background-size: 75px 75px, 75px 75px, 15px 15px, 15px 15px;
    background-position: -3px -3px, -3px -3px, -1.5px -1.5px, -1.5px -1.5px;
}
html.sidebar-initial-collapsed .sidebar {
    width: 0 !important;
    overflow: hidden;
    transition: none !important;
}
html.sidebar-initial-collapsed .content {
    margin-left: 0 !important;
    transition: none !important;
}
.sidebar {
    width: 250px;
    background-color: #51d9ce;
    color: white;
    position: fixed;
    height: 100%;
    top: 0;
    left: 0;
    padding-top: 68px;
    overflow: hidden;
    z-index: 1;
    transition: width 0.1s ease; 
}
.sidebar.collapsed {                                 
    width: 0;
    overflow: hidden;
}
.sidebar.collapsed + .content {
    margin-left: 0;
}
.content {
    transition: margin-left 0.1s ease;
    margin-left: 250px;
    padding: 20px;
    flex-grow: 1;
    min-height: 50vh;
    min-width: 2455px;
}
.content.no-transition {
    transition: none;
}
.toggle-btn {
    position: fixed;
    top: 20px;
    left: 20px;
    background-color: #5fe9de;
    color: white;
    border: none;
    padding: 10px;
    font-size: 20px;
    cursor: pointer;
    z-index: 10; 
}
.sidebar h2 {
    text-align: center;
    color: #fff;
    padding: 20px 0;
    margin: 0;
}
.logo {
            width: 160px;
            height: auto;
            margin-bottom: 20px;
            padding-top: 9px;
            margin: 35px;
        }
.sidebar ul {
    list-style-type: none;
    padding: 0;
}
.sidebar ul li {
    padding: 15px;
    text-align: center;
}
.sidebar ul li a.active {
    background-color: #3db2a9; 
    color: #ffffff; 
    font-weight: bold; 
}
.sidebar ul li a {
    color: white;
    text-decoration: none;
    display: block;
    font-size: 18px;
}
.sidebar ul li a:hover {
    background-color: #49c4ba;
}
.content h1 {
    font-size: 24px;
    color: #333;
}
.sidebar.collapsed ul {
    display: none;
}
.sidebar.collapsed+.content {
    margin-left: 0;
}
.container {
    max-width: 2340px;
    min-width: 2340px;
    margin: auto;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-top: 30px;
}
h1 {
    font-size: 2em;
    color: #444;
    text-align: center;
    margin-bottom: 1em;
}
p {
    font-size: 1.1em;
    margin: 8px 0;
    padding: 10px;
    border-bottom: 1px solid #e1e1e1;
}
p strong {
    color: #555;
    font-weight: bold;
}
.back-link {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 15px;
    background-color: #007bff;
    color: #fff;
    border-radius: 5px;
    text-align: center;
    text-decoration: none;
    font-size: 1em;
    transition: background-color 0.3s ease;
}
.back-link:hover {
    background-color: #0056b3;
}
.table-container {
    font-family: sans-serif;  
    margin: 20px 0;
}
.table {
    width: 100%;
    border-collapse: collapse;
    min-width: 2000px;
    max-width: 2000px;
    font-size: 0.9em;
    text-align: center;
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.table td,.table th {
    padding: 12px;
    text-align: center;
}
.table td input,.table td select {
    width: 100%;
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #ddd;
    margin-top: 5px;
}
.table th,.table td {
    padding: 12px 15px;
    border: 1px solid #ddd;
}
.table thead th {
    background-color: #14cf65;
    color: #fff;
    font-weight: bold;
}       
.table tbody tr:hover {
    background-color: #f1f1f1;
}
        .search-bar,
        .pagination {
            display: flex;
            justify-content: center;
            margin: 10px 0;
        }
        .search-bar input[type="text"] {
            width: 200px;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            background-color: #51d9ce;
        }
        .search-bar button,
        .pagination a {
            padding: 8px 12px;
            margin-left: 5px;
            background-color: #51d9ce;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .pagination a:hover,
        .search-bar button:hover {
            background-color: #0056b3;
        }
        .pagination a.pagina-actual {
    background-color: #3db2a9;
    font-weight: bold;
    cursor: default;
    pointer-events: none; 
}
        .acciones a {
            padding: 5px 10px;
            color: white;
            text-decoration: none;
            margin-right: 5px;
            border-radius: 4px;
        }

        .acciones a.editar {
            background-color: #28a745;
        }

        .acciones a.eliminar {
            background-color: #dc3545;
        }

       
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

       
        .buscar-btn {
            background-color: #007bff;
            size: smaller;
        }

        .buscar-btn:hover {
            background-color: #0056b3;
        }
      
        .search_bar input[type="text"] {
            min-width: 1000px;
            padding: 8px 12px;
            width: 70%;
            border: 1px solid #ddd;
            border-radius: 5px 0 0 5px;
            outline: none;
            transition: box-shadow 0.3s ease;
            font-size: 16px;
        }

        .search_bar input[type="text"]:focus {
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            border-color: #007bff;
        }

       
        .search_bar {
            display: flex;
            gap: 5px;
        }

        .search_bar form {
            display: flex;
            align-items: center;
            gap: 0;
        }

        .search_bar button {
            border-radius: 0 5px 5px 0;
        }

        .form-control {
            margin-top: 5px;
            border-radius: 4px;
        }

        .buttonmodal {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            width: 100%;
        }

        button[type="submit"]:hover,
        button[type="button"]:hover {
            background-color: #0056b3;
        }

        .modal-content {
            padding: 20px;
            border-radius: 8px;
            background-color: #fff;
            max-width: 800px;
            /* Ancho máximo del modal */
            width: 100%;
            /* El modal puede ocupar todo el ancho disponible, pero sin exceder el máximo */
            position: relative;
            /* Necesario para posicionar el botón de cierre */
        }

        .modal-content .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            right: 10px;
            top: 5px;
        }

        .modal-content .close:hover,
        .modal-content .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal {
            display: flex;
            justify-content: center;
            
            align-items: center;
           
            display: none;
           
            position: fixed;
           
            z-index: 1;
           
            left: 0;
            top: 0;
            width: 100%;
           
            height: 100%;
        
            overflow: auto;
       
            background-color: rgba(0, 0, 0, 0.4);
      
        }

        .container h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .table-containerr {
            text-align: center;
            width: 100%;
            margin: 20px auto;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }


        .table-containerr th {
            background-color: #70d199;
            color: white;
            text-align: center;
            padding: 12px;
            font-weight: bold;
        }

       
        .table-containerr td {
            background-color: #f9f9f9;
       
            padding: 10px;
            border-bottom: 1px solid #ddd;
      
        }

    
        .table-containerr tr:hover {
            background-color: #f1f1f1;
            
        }

        
        .table-containerr td:first-child {
            font-weight: bold;
            color: #333;
         
        }


        .table-containerr td:last-child {
            text-align: center;
        }

        .btn-small {
            font-size: 14px;
 
            padding: 4px 8px;
    
            height: 30px;
      
            border-radius: 4px;
   
            min-width: 85px;
        }
            
    .agregar-btn {
    padding: 13px 20px;
    font-size: 16px;
    color: #fff;
    background-color: #14cf65;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    align-self: center; /* Asegura la alineación vertical */
}

.agregar-btn:hover {
    background-color: #0056b3;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.filtros {
    display: inline-flex;
    justify-content: left;
    align-items: center;
    vertical-align: middle;
    gap: 15px;
    margin: 20px auto;
    padding: 14px 20px;
    background-color: #f9f9f9; /* Fondo claro */
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sombra suave */
}

.filtros label {
    font-size: 18px;
    color: #555;
    font-weight: bold;
}

.filtros select {
    padding: 10px 15px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #fff;
    color: #333;
    transition: all 0.3s ease;
    cursor: pointer;
}


.filtros select:focus {
    border-color: #007bff;
    outline: none; /* Sin borde adicional */
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
}

/* Responsive: Ajusta el diseño en pantallas pequeñas */
@media (max-width: 768px) {
    .filtros {
        flex-direction: column;
        gap: 10px;
    }
}


        </style>
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
                <li><a href="../Vista/VistaLibros.php" class="active">Lista de Libros</a></li>
                <li><a href="../Vista/Insertar.php">Insertar Libro</a></li>
                <li><a href="../Vista/VistaPrestamos.php">Libros Prestados</a></li>
                <br>
            <br>
            <br>
            <img src="../imagen/logo-blanco.png" alt="Logo de la Biblioteca" class="logo">
            </ul>
        </div>

        <div class="content">
            <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

            <div class="container">
                <h1>Listado de Libros</h1>
                <div class="search_bar">
                    <form method="GET" action="VistaLibros.php">
                        <input type="text" name="busqueda"
                            placeholder="Puedes buscar por ID, códigos, título, autor y editorial. "
                            value="<?php echo isset($_GET['busqueda']) ? htmlspecialchars($_GET['busqueda']) : ''; ?>">

                        <input type="hidden" name="filtros"
                            value="<?php echo isset($_GET['filtros']) ? htmlspecialchars($_GET['filtros']) : ''; ?>">
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

                <button type="button" class="btn agregar-btn" onclick="window.location.href='Insertar.php?pagina=<?php echo $paginaActual; ?>';">Agregar NuevoLibro</button>

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

function crearEnlace($pagina, $paginaActual, $busqueda = '', $filtroSeleccionado = '', $texto = null) {
    if (isset($_GET['busqueda']) && empty($busqueda)) {
        $busqueda = $_GET['busqueda'];
    }
    if (isset($_GET['filtros']) && empty($filtroSeleccionado)) {
        $filtroSeleccionado = $_GET['filtros'];
    }

    $queryParams = http_build_query([
        'pagina' => $pagina,
        'busqueda' => $busqueda,
        'filtros' => $filtroSeleccionado,
    ]);

    $estilo = $pagina == $paginaActual ? 'class="pagina-actual"' : '';
    $texto = $texto ?? $pagina; 

    return "<a href='?{$queryParams}' {$estilo}>{$texto}</a>";
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

                <?php if (count($libros) > 0): ?>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="min-width: 100px; max-width: 100px;"><strong>Préstamos</strong></th>
                                <th style="min-width: 40px;  max-width: 40px"><strong>ID</strong></th>
                                <th style="min-width: 60px;  max-width:60px"><strong>IDextra</strong></th>
                                <th style="min-width: 90px;  max-width: 90px"><strong>Código</strong></th>
                                <th style="min-width: 115px;  max-width:115px"><strong>Actividad</strong></th>
                                <th style="min-width: 250px;  max-width:250px"><strong>Título</strong></th>
                                <th style="min-width: 150px;  max-width:150px"><strong>Autor</strong></th>
                                <th style="min-width: 40px; max-width:40px" ><strong> Estado</strong></th>
                                <th style="min-width: 70px; max-width:70px"><strong>Editorial</strong></th>
                                <th style="min-width: 70px; max-width:70px"><strong>Área</strong></th>
                                <th style="min-width: 40px; max-width:40px"><strong>Medio</strong></th>
                                <th style="min-width: 80px; max-width:80px"><strong>Clase</strong></th>
                                <th style="min-width: 80px; max-width:80px"><strong>Sección</strong></th>
                                <th style="min-width: 300px; max-width:300px"><strong>Temas</strong></th>
                                <th style="min-width: 300px; max-width:300px" ><strong>Temas2</strong></th>
                                <th style="min-width: 50px ; max-width:50px" ><strong>Acciones</strong></th>
                            </tr>
                        </thead>
                        <tbody>
<?php 
               foreach ($libros as $libro): 

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
                                </td>
                                <td style="min-width: 60px; max-width: 60px; font-size:15px;"><strong><?php echo $libro['ID']; ?></strong</td>
                                <td style="min-width: 75px; max-width: 75px;"><?php echo $libro['IDLIB']; ?></td>
                                <td style="min-width: 90px; max-width: 90px;"><?php echo $libro['Codigo']; ?></td>

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
                                <td style="min-width: 100px; max-width: 100px;"><?php echo $nombreAutor; ?></td>
                                <td style="min-width: 100px; max-width: 100px;"><?php echo $nombreEstado; ?></td>
                                <td style="min-width: 140px; max-width: 140px;"><?php echo $nombreEditorial; ?></td>
                                <td style="min-width: 130px; max-width: 130px;"><?php echo $nombreArea; ?></td>
                                <td style="min-width: 100px; max-width: 100px;"><?php echo $nombreMedio; ?></td>
                                <td style="min-width: 110px; max-width: 110px;"><?php echo $nombreClase; ?></td>
                                <td style="min-width: 100px; max-width: 100px;"><?php echo $nombreSeccion; ?></td>
                                <td style="min-width: 400px; max-width:400px"><?php echo $libro['Temas']; ?></td>
                                <td style="min-width: 100px; max-width: 100px;"><?php echo $libro['Temas2']; ?></td>
                                <td style="min-width: 85px; max-width: 85px;">
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
                                            <th><label for="fecha">Fecha:</label></th>
                                            <td><input type="date" id="fecha" name="fecha" required></td>
                                        </tr>
                                    </table>
                                    <button type="button" class="buttonmodal" id="botonAgregarPrestamo"
                                        onclick="agregarPrestamos(1)">Agregar Préstamo</button>

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

                                        </tr>
                                    </thead>
                                    <tbody id="contenidoPrestamo">
                                    </tbody>
                                </table>
                                <button type="button" class="buttonmodal" id="botonDevolverPrestamo"
                                    style="display: none;" onclick="EliminarPrestamos(0)">Marcar como Devuelto</button>

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
                                    <th>Prestamos</th>
                                    <th>ID</th>
                                    <th>IDextra</th>
                                    <th>Código</th>
                                    <th>Actividad</th>
                                    <th>Título</th>
                                    <th>Autor</th>
                                    <th>Estado</th>
                                    <th>Editorial</th>
                                    <th>Area</th>
                                    <th>Medio</th>
                                    <th>Clase</th>
                                    <th>Sección</th>
                                    <th>Temas</th>
                                    <th>Temas2</th>
                                    <th>Acciones</th>
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

            

    </body>

    </html>