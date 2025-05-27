<?php

require_once '../Modelo/Conexion.php';
require_once '../Modelo/Datosperiodico.php';  
require_once '../Modelo/Datosprest.php'; 
require_once '../Modelo/periodico.php'; 

session_start();


$periodicoModelo = new misPeriodicos(); 
$prestModelo=new misPrestados();
$periodicModelo= new miPeriodico();

$limite = 20;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $limite;

$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';

// Ahora sí, llamamos las funciones con los rangos correctos
$periodicos = $periodicoModelo->verPeriodicos1($limite, $offset, $busqueda);
$totalperiodicos = $periodicoModelo->contarPeriodicos1($busqueda);
$totalPaginas = ceil($totalperiodicos / $limite);
$periodi = $periodicModelo->verPeriodico(); 



?>

<?php if (isset($_GET['mensaje'])): ?>
<p><?php echo $_GET['mensaje']; ?></p>
<?php endif; ?>

<!DOCTYPE html>
<lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Listado de Revistas</title>

        <?php include '../Libreria/libreriacss.php'; ?>
        <?php include '../Libreria/libreriajs.php'; ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="../Libreria/Modal.css">
        <link rel="stylesheet" href="../Libreria/ver.css">
        <script src="../Controlador/Controprest.js"></script>
        <script src="../Controlador/ControeliminarPeriodico.js"></script>
        
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
            <li><a href="../Vista/VistaLibros.php" >Libros</a></li>
            <li><a href="../Vista/VistaPeriodicos.php"class="active">Periodicos</a></li>
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
            <a href="VistaLibros.php" class="back-link">Libros</a> 
            <a href="VistaRevistas.php" class="back-link">Revistas</a> 
                <h1>Listado de Periodicos</h1>
                <a href="../Controlador/exportarPeriodicos.php" class="btn btn-success">📥 Exportar Periodicos a Excel</a>
                <div class="search_bar">
                    <form method="GET" action="VistaPeriodicos.php">
                        <input type="text" name="busqueda"
                            placeholder="Puedes buscar por ID, códigos, título, autor y editorial. "
                            value="<?php echo isset($_GET['busqueda']) ? htmlspecialchars($_GET['busqueda']) : ''; ?>">

                        <input type="hidden" name="pagina" value="1">

                        <button type="submit" class="btn buscar-btn">Buscar</button>
                    </form>
                </div>

                <button type="button" class="btn agregar-btn" onclick="window.location.href='Insertperiodico.php?pagina=<?php echo $paginaActual; ?>';">Agregar Nuevo Periodico</button>

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

function crearEnlace($pagina, $paginaActual, $busqueda = '',  $texto = null) {
    if (isset($_GET['busqueda']) && empty($busqueda)) {
        $busqueda = $_GET['busqueda'];
    }

    $queryParams = http_build_query([
        'pagina' => $pagina,
        'busqueda' => $busqueda,

    ]);

    $estilo = $pagina == $paginaActual ? 'class="pagina-actual"' : '';
    $texto = $texto ?? $pagina; 

    return "<a href='?{$queryParams}' {$estilo}>{$texto}</a>";
}
?>
                    <?php if ($paginaActual > 1): ?>
                    <?= crearEnlace(1, $paginaActual, $busqueda,  'Primero') ?>
                    <?= crearEnlace($paginaActual - 1, $paginaActual, $busqueda, 'Anterior') ?>
                    <?php endif; ?>

                    <?php for ($i = $rangoInicio; $i <= $rangoFin; $i++): ?>
                    <?= crearEnlace($i, $paginaActual, $busqueda,  null) ?>
                    <?php endfor; ?>

                    <?php if ($paginaActual < $totalPaginas): ?>
                    <?= crearEnlace($paginaActual + 1, $paginaActual, $busqueda,  'Siguiente' ) ?>
                    <?= crearEnlace($totalPaginas, $paginaActual, $busqueda,  'Último' ) ?>
                    <?php endif; ?>
                </div>

                <?php $PeriodicosUnicos = [];
foreach ($periodicos as $periodico) {
    if (!isset($PeriodicosUnicos[$periodico['id']])) {
        $PeriodicosUnicos[$periodico['id']] = $periodico;
    }
}
if (count($periodicos) > 0): ?>
                <div class="table-container">
                    <table class="table">
                        <thead>					
                            <tr>
                            <th style="min-width: 100px; max-width: 100px;"><strong>prestamos</strong></th>
                                <th style="min-width: 100px; max-width: 100px;"><strong>id</strong></th>
                                <th style="min-width: 50px;  max-width: 50px"><strong>nombre</strong></th>
                                <th style="min-width: 130px;  max-width: 130px"><strong>proveedor</strong></th>
                                <th style="min-width: 150px; max-width:150px" ><strong>fecha</strong></th>
                                <th style="min-width: 40px; max-width:40px" ><strong>Acciones</strong></th>
                            </tr>
                        </thead>
                        <tbody>
<?php 
               foreach ($PeriodicosUnicos as $periodico): 
                $period = $periodicModelo->verPeriodicoID($periodico['nombre']);
                $NombrePeriodico = !empty($period) ? $period[0]['periodicos'] : 'Desconocido';

                $prest=$prestModelo->verPrestadoID($periodico['SiPrest']);
                $nombrePrest = !empty($prest) ? $prest[0]['TipoP'] : 'Desconocido';

                $prest = $prestModelo->verPrestadoID($periodico['SiPrest']);
                $siPrest = $periodico['SiPrest'];
                $color = ($siPrest == 0) ? '#2ecf74' : 'red';
?>
                            <tr>
                            <td style="min-width: 100px; max-width: 100px; text-align: center; vertical-align: middle;">
                            <div onclick="openModal('prestamoModal', 
    '<?php echo htmlspecialchars($periodico['id'], ENT_QUOTES, 'UTF-8'); ?>', 
    '<?php echo htmlspecialchars($periodico['nombre'], ENT_QUOTES, 'UTF-8'); ?>',
    '<?php echo htmlspecialchars($periodico['SiPrest'], ENT_QUOTES, 'UTF-8'); ?>', 
    'periodico')"
    id="cuadri"
    style="width: 28px; height: 28px; display: inline-flex; justify-content: center; align-items: center; background-color: <?php echo $color; ?>; cursor: pointer;"
    title="<?php echo htmlspecialchars($nombrePrest, ENT_QUOTES, 'UTF-8'); ?>">
</div>
                                    <input type="hidden" id="tipoMaterial" name="tipoMaterial" value="periodico"> <!-- libro, revista, periodico -->

                                </td>
                                <td style="min-width: 75px; max-width: 75px; font-size:15px; background-color: <?php echo isset($periodico['coincidencia_periodico']) && $periodico['coincidencia_periodico'] ? 'yellow' : 'transparent'; ?>">
                                <strong><?php echo htmlspecialchars($periodico['id']); ?></strong>
</td>

                                <td style="min-width: 100px; max-width: 100px;">
                                    <a href="VistaDetallePeriodico.php?id=<?php echo $periodico['id']; ?>">
                                        <?php echo $NombrePeriodico; ?>
                                </td>
                                <td style="min-width: 100px; max-width: 150px;"><?php echo $periodico['proveedor']; ?></td>
                                <td style="min-width: 100px; max-width: 100px;"><?php echo $periodico['fecha']; ?></td>
    
                                
                                <td style="min-width: 85px; max-width: 85px; background-color: <?php echo isset($periodico['coincidencia_indice']) && $periodico['coincidencia_indice'] ? 'yellow' : 'transparent'; ?>">
                                    <a href="ActualizarPeriodicos.php?id=<?php echo $periodico['id']; ?>">Editar</a> |
                                    <a href="EliminarPeriodicos.php?id=<?php echo $periodico['id']; ?>" class="eliminar-libro"
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
                    <?= crearEnlace(1, $paginaActual, $busqueda,  'Primero') ?>
                    <?= crearEnlace($paginaActual - 1, $paginaActual, $busqueda,  'Anterior') ?>
                    <?php endif; ?>

                    <?php for ($i = $rangoInicio; $i <= $rangoFin; $i++): ?>
                    <?= crearEnlace($i, $paginaActual, $busqueda) ?>
                    <?php endfor; ?>

                    <?php if ($paginaActual < $totalPaginas): ?>
                    <?= crearEnlace($paginaActual + 1, $paginaActual, $busqueda, 'Siguiente') ?>
                    <?= crearEnlace($totalPaginas, $paginaActual, $busqueda, 'Último') ?>
                    <?php endif; ?>
                </div>

                <center>
                    <div id="prestamoModal" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeModal('prestamoModal')">&times;</span>
                            <h2 id="modalTitulo">Préstamo de revista</h2>
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

                </div>
            </div>

            <?php else: ?>
            <p>No hay periodicos disponibles.</p>
            <div class="table-container">
                <center>
                    <table class="table">
                        <thead>
                            <tr>
                                
                            <th style="min-width: 100px; max-width: 100px;"><strong>prestamos</strong></th>
                                <th style="min-width: 100px; max-width: 100px;"><strong>id</strong></th>
                                <th style="min-width: 50px;  max-width: 50px"><strong>nombre</strong></th>
                                <th style="min-width: 130px;  max-width: 130px"><strong>proveedor</strong></th>
                                <th style="min-width: 150px; max-width:150px" ><strong>fecha</strong></th>
                                <th style="min-width: 40px; max-width:40px" ><strong>Acciones</strong></th>
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