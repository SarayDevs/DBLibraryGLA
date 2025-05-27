<?php

require_once '../Modelo/Conexion.php';
require_once '../Modelo/Datosrevista.php';  
require_once '../Modelo/Datosprest.php'; 
require_once '../Modelo/revista.php'; 

session_start();


$revistaModelo = new misRevistas(); 
$prestModelo=new misPrestados();
$revistModelo= new miRevista();

$limite = 20;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $limite;

$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';

// Ahora sí, llamamos las funciones con los rangos correctos
$revistas = $revistaModelo->verRevistas1($limite, $offset, $busqueda);
$totalRevistas = $revistaModelo->contarRevistas1($busqueda);
$totalPaginas = ceil($totalRevistas / $limite);


$titurevista = $revistModelo->verRevista(); 


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
    <a href="CatalogoExterno.php" class="back-link">Libros</a> 
    <a href="CatalogoPeriodicos.php" class="back-link">Periodicos</a> 
    <h2>Opac's Revistas</h2>
<center>
    
    <div class="search_bar">
                    <form method="GET" action="CatalogoExterno.php">
                        <input type="text" name="busqueda"
                            placeholder="Puedes buscar el nombre de la revista."
                            value="<?php echo isset($_GET['busqueda']) ? htmlspecialchars($_GET['busqueda']) : ''; ?>">

                      
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
 </div>               
 <div class="catalogo">
<?php foreach ($revistas as $revista): 

    // Obtener nombre real de la revista desde tabla relacionada
    $revistaRelacionada = $revistModelo->verRevistaID($revista['titulo']);
    $nombreRevista = !empty($revistaRelacionada) ? $revistaRelacionada[0]['revistas'] : 'Desconocido';

    // Imagen de carátula
    $caratula = !empty($revista['imagen']) 
        ? "http://localhost/MVC/Controlador/" . htmlspecialchars($revista['imagen']) 
        : "http://localhost/MVC/Controlador/imagen/NOIMAGE.jpg";
?>
    <div class="tarjeta">
        <img src="<?php echo $caratula; ?>" 
             alt="<?php echo htmlspecialchars($nombreRevista); ?>" 
             width="200" height="270" 
             onerror="this.onerror=null; this.src='http://localhost/MVC/Controlador/imagen/NOIMAGE.jpg';">
        
        <h3 style="min-height: 30px; max-height: 100px; overflow: hidden;">
            <?php echo htmlspecialchars($nombreRevista); ?>
        </h3>
        <p style="height: 20px"><strong>Proveedor:</strong> <?php echo htmlspecialchars($revista['proveedor'] ?? 'N/A'); ?></p>
        <p style="height: 20px"><strong>Año:</strong> <?php echo htmlspecialchars($revista['anio'] ?? ''); ?></p>

        <button onclick="window.location.href='ExternoDR.php?id=<?= $revista['id']; ?>'" 
                class="btn detalles-btn">Detalles</button>
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
                </div>
                <br>
                <br>
</body>
</html>
