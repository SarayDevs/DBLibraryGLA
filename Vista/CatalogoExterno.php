<?php
require_once '../Modelo/Datoslibros.php'; 
require_once '../Modelo/Conexion.php'; 
require_once '../Modelo/Datosautor.php';

$autorModelo = new misAutores(); 
$libroModelo = new misLibros(); 
$limite = 18;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $limite;

$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
$filtroSeleccionado = '1'; // Siempre aplicar filtro 1


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
$libros = $libroModelo->verLibros2($limite, $offset, $busqueda,  $signatura_min, $signatura_max, 1, $filtro_bilingue);
$totalLibros = $libroModelo->contarLibros2($busqueda, $signatura_min, $signatura_max, 1, $filtro_bilingue);
$totalPaginas = ceil($totalLibros / $limite);




?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Libros</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <link rel="stylesheet" href="../Libreria/catalogoexterno.css">
</head>
<body>
    <br><br><div class="container">
    <a href="CatalogoPeriodicos.php" class="back-link">Periodicos</a> 
    <a href="CatalogoRevistas.php" class="back-link">Revistas</a> 
    <h2>Opac's Libros</h2>
<center>
    
    <div class="search_bar">
                    <form method="GET" action="CatalogoExterno.php">
                        <input type="text" name="busqueda"
                            placeholder="Puedes buscar por título, autor, ISBN, tema o editorial. "
                            value="<?php echo isset($_GET['busqueda']) ? htmlspecialchars($_GET['busqueda']) : ''; ?>">

                        <input type="hidden" name="filtros"
                            value="<?php echo isset($_GET['filtros']) ? htmlspecialchars($_GET['filtros']) : ''; ?>">

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
    </select>
                        <input type="hidden" name="pagina" value="1">

                        <button type="submit" class="btn buscar-btn">Buscar</button>
                    </form>
                </div></center>

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

function crearEnlace($pagina, $paginaActual, $busqueda = '', $filtroSeleccionado = 1, $texto = null, $filtro_area = '') {
    if (isset($_GET['busqueda']) && empty($busqueda)) {
        $busqueda = $_GET['busqueda'];
    }
    if (isset($_GET['filtros']) && empty($filtroSeleccionado)) {
        $filtroSeleccionado = $_GET['filtros'];
    } else {
        $filtroSeleccionado = '1'; // Asegurar que siempre haya un filtro
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
                    <?= crearEnlace(1, $paginaActual, $busqueda, $filtroSeleccionado,'Primero', $filtro_area) ?>
                    <?= crearEnlace($paginaActual - 1, $paginaActual, $busqueda, $filtroSeleccionado, 'Anterior', $filtro_area) ?>
                    <?php endif; ?>

                    <?php for ($i = $rangoInicio; $i <= $rangoFin; $i++): ?>
                    <?= crearEnlace($i, $paginaActual, $busqueda, $filtroSeleccionado, null, $filtro_area) ?>
                    <?php endfor; ?>

                    <?php if ($paginaActual < $totalPaginas): ?>
                    <?= crearEnlace($paginaActual + 1, $paginaActual, $busqueda, $filtroSeleccionado ,'Siguiente', $filtro_area) ?>
                    <?= crearEnlace($totalPaginas, $paginaActual, $busqueda, $filtroSeleccionado,'Último', $filtro_area) ?>
                    <?php endif; ?>
                </div>
 </div>               
    <div class="catalogo">
        <?php foreach ($libros as $libro): 
             $autor = $autorModelo->verAutorID($libro['autorID']);
             $nombreAutor = !empty($autor) ? $autor[0]['autores'] : 'Desconocido';
             $caratula = !empty($libro['Caratula']) ? "http://localhost/BASE%20DE%20DATOS%20COLEGIO/MVC/Controlador/" . htmlspecialchars($libro['Caratula']) : "http://localhost/BASE%20DE%20DATOS%20COLEGIO/MVC/Controlador/NOIMAGE.jpg";
             ?>
            <div class="tarjeta">
            <img src="http://localhost/MVC/Controlador/<?php echo htmlspecialchars($libro['Caratula']); ?>" 
         alt="<?php echo htmlspecialchars($libro['Titulo']); ?>" 
         width="200" height="270" 
         onerror="this.onerror=null; this.src='http://localhost/MVC/Controlador/imagen/NOIMAGE.jpg';">
                <h3 style="min-height: 30px; max-height: 100px; overflow: hidden;"><?php echo htmlspecialchars($libro['Titulo']); ?></h3>
                <p style="height: 20px"><strong>Autor:</strong> <?php echo htmlspecialchars($nombreAutor); ?></p>
                <p style="min-height: 100px; max-height:300px; overflow: hidden;"><strong>Temas:</strong> <?php echo htmlspecialchars($libro['Temas']); ?></p>
                <p style="height: 20px"  ><strong>Año:</strong> <?php echo htmlspecialchars($libro['Fimpresion']); ?></p>
                
                <button onclick="window.location.href='DetallesExterno.php?id=<?= $libro['ID']; ?>'" class="btn detalles-btn ">Detalles</button>

            </div>
        <?php endforeach; ?>
    </div>
    <div class="container">
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
                </div>
                <br>
                <br>
</body>
</html>
