<?php
require_once '../Modelo/Datosprestamos.php';  
require_once '../Modelo/Datoslibros.php';  

$prestamos = new misPrestamos();
$libros = new misLibros();

$limite = 15; 
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $limite;

$listaPrestamosE = $prestamos->verExternPrestamoss($limite, $offset);
$listaPrestamos = $prestamos->verPrestamoss($limite, $offset);

$totalPrestamosE = $prestamos->contarExternPrestamos();
$totalPaginas = ceil($totalPrestamosE / $limite);



?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Prestamos Externos</title>
    <?php include '../Libreria/libreriacss.php'; ?>
    <?php include '../Libreria/libreriajs.php'; ?>
    <link rel="stylesheet" href="../Libreria/Modal.css">
    <link rel="stylesheet" href="../Libreria/prestamos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


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
        <br>
        <br>
        
        <h2>Panel de Control</h2>
        <ul>
        <li><a href="../Vista/VistaLibros.php" >Libros</a></li>
            <li><a href="../Vista/VistaPeriodicos.php">Periodicos</a></li>
            <li><a href="../Vista/VistaRevistas.php">Revistas</a></li>
            <li><a href="../Vista/VistaPrestamos.php" class="active">Prestamos</a></li>
            <li><a href="../Vista/Insertar.php" >Insertar Libro</a></li>
            <li><a href="../Vista/Insertrevista.php" >Insertar Revista</a></li>
            <li><a href="../Vista/Insertperiodico.php" >Insertar Periodico</a></li>

            <br>
            <img src="../imagen/logo-blanco.png" alt="Logo de la Biblioteca" class="logo">
        </ul>
    </div>
    <button class="toggle-btn" >☰</button>
<div class="container">
    <h1> Prestamos Externos</h1>
    <div class="pagination">

    <?php if ($paginaActual > 1): ?>
        <a href="?pagina=1">Primero</a>
        <a href="?pagina=<?php echo $paginaActual - 1; ?>">Anterior</a>
    <?php endif; ?>

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

    for ($i = $rangoInicio; $i <= $rangoFin; $i++): ?>
        <a href="?pagina=<?php echo $i; ?>" <?php if ($i == $paginaActual) echo 'class="pagina-actual"'; ?>>
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>

    
    <?php if ($paginaActual < $totalPaginas): ?>
        <a href="?pagina=<?php echo $paginaActual + 1; ?>">Siguiente</a>
        <a href="?pagina=<?php echo $totalPaginas; ?>">Último</a>
    <?php endif; ?>
</div>
<div style="text-align: left; margin-top: 20px; padding: 20px;">
    <a href= "javascript:history.back()" class="button" style="display: inline-block; padding: 12px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">Volver a la página anterior</a>
</div>

    <table class="table table-bordered">
        <tr>
            <th>Número de Préstamo</th>
            <th>ID Libro </th>
            <th>Libro</th>
            <th>Estado</th>
            <th>Nombre de la Persona</th>
            <th>Tipo de usuario</th>
            <th>Fecha de Préstamo</th>
            <th>Contacto</th>
            <th>Dias de préstamo</th>
            <th>Fecha de devolución</th>
        </tr>
        <?php foreach ($listaPrestamosE as $prestamoE): ?>
            <tr>
            <td style="min-width: 75px; max-width: 75px; background-color: <?php echo ($prestamo['estadoprest']==1) ? 'yellow' : 'transparent'; ?>">
            <?php echo $prestamoE['idprestamo']; ?></td>
                
                <td><?php echo $prestamoE['idlibro']?></td>
                <td>
                    <a href="VistaDetalleLibro.php?id=<?php echo $prestamoE['idlibro']; ?>">
                        <?php echo $libros->obtenerTituloLibro($prestamoE['idlibro']); ?>
                    </a>
                </td>
                <td><?php echo $prestamoE['estadoprest'] == 1 ? 'Prestado' : 'Devuelto'; ?>
                </td>
                <td><?php echo $prestamoE['nombre']; ?></td>
                <td>
                <?php
                    echo $prestamoE['persona'] == 1 ? 'Docente' :
                    ($prestamoE['persona'] == 2 ? 'Estudiante' :
                    ($prestamoE['persona'] == 3 ? 'Directivo' : 
                    ($prestamoE['persona'] == 4 ? 'Oficios Varios' :
                    ($prestamoE['persona'] == 5 ? 'Invitado' : 'Otros')
                    )));
                ?>
                </td>
                <td><?php echo $prestamoE['fecha_inicio']; ?></td>
                <td><?php echo $prestamoE['contacto']; ?></td>
                <td><?php echo $prestamoE['tiempo']; ?></td>
                <td><?php echo $prestamoE['DEVOLUCION'];?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div class="pagination">
    
    <?php if ($paginaActual > 1): ?>
        <a href="?pagina=1">Primero</a>
        <a href="?pagina=<?php echo $paginaActual - 1; ?>">Anterior</a>
    <?php endif; ?>

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

    for ($i = $rangoInicio; $i <= $rangoFin; $i++): ?>
        <a href="?pagina=<?php echo $i; ?>" <?php if ($i == $paginaActual) echo 'class="pagina-actual"'; ?>>
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>

    <?php if ($paginaActual < $totalPaginas): ?>
        <a href="?pagina=<?php echo $paginaActual + 1; ?>">Siguiente</a>
        <a href="?pagina=<?php echo $totalPaginas; ?>">Último</a>
    <?php endif; ?>
</div>
</div>
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