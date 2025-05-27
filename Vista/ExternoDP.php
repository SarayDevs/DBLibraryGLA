<?php
require_once '../Modelo/Conexion.php';
require_once '../Modelo/Datosperiodico.php';  
require_once '../Modelo/Datosprest.php'; 
require_once '../Modelo/periodico.php'; 
require_once '../Modelo/DatosPA.php'; 
require_once '../Modelo/Datosautor2.php';  
session_start();

$periodicoModelo = new misPeriodicos(); 
$prestModelo = new misPrestados();
$periodicModelo = new miPeriodico();
$autorModelo=new misAutores();


$idPeriodico = $_GET['id'];

$detallePeriodico = $periodicoModelo->verPeriodicosID($idPeriodico);

if (empty($detallePeriodico)) {
    echo "El libro no existe o no se encontró.";
    exit;
}

$detallePeriodico = $detallePeriodico[0]; 

$periodi = $periodicModelo->verPeriodico(); 


$articuloModelo = new misArticulos();
$articulos = $articuloModelo->verArticulosPID($idPeriodico);
$articulos1 = $articuloModelo->verArticulosPID($idPeriodico);
$articulos1 = $articulos1[0]; 

$periodic = $periodicModelo->verPeriodicoID($detallePeriodico['nombre']);
$nombrePeriodico = !empty($periodic) ? $periodic[0]['periodicos'] : 'Desconocido';

$autor2 = $autorModelo->verAutorID($articulos1['autor']);
$nombreAutor = !empty($autor2) ? $autor2[0]['autores2'] : 'Desconocido';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Periódico</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <link rel="stylesheet" href="../Libreria/detalleexterno.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
</head>
<body>

<div class="container">
    <div class="left-section">
        <h1><?php echo htmlspecialchars($nombrePeriodico); ?></h1>
        <div class="details">
            <p><strong>Proveedor:</strong> <?php echo htmlspecialchars($detallePeriodico['proveedor']); ?></p>
            <p><strong>Fecha:</strong> <?php echo htmlspecialchars($detallePeriodico['fecha']); ?></p>
            <p><strong>Periodico Nº:</strong> <?php echo htmlspecialchars($detallePeriodico['id']); ?></p>

        </div>
        <a href="javascript:history.back()" class="back-link">Volver</a>
    </div>

    <div class="right-section">
        <h1>Carátula</h1>
        <img src="http://localhost/MVC<?php echo htmlspecialchars($detallePeriodico['imagen']); ?>" 
             alt="<?php echo htmlspecialchars($detallePeriodico['nombre']); ?>" 
             width="200" height="375" 
             onerror="this.onerror=null; this.src='http://localhost/MVC/Controlador/imagen/NOIMAGE.jpg';">

       
    </div>
</div>

<div class="indice-container">
    <?php if (!empty($articulos)): ?>
        <h2>Artículos</h2>
        <table class="indice-table">
            <thead>
                <tr>
                    <th>Autor</th>
                    <th style="width: 400px;">Título</th>
                    <th style="width: 60px;">Página</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articulos as $articulo): ?>
                <tr>
                    <td><?= htmlspecialchars($nombreAutor ?? 'NA'); ?></td>
                    <td><?= htmlspecialchars($articulo['titulo_articulo'] ?? 'NA'); ?></td>
                    <td><?= htmlspecialchars($articulo['pagina'] ?? 'NA'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay artículos disponibles.</p>
    <?php endif; ?>
</div>

<?php if (!empty($articulos)): ?>
    <div class="indice-imagenes">
        <?php foreach ($articulos as $articulo): ?>
            <?php if (!empty($articulo['imagen'])): ?>
                <img src="http://localhost/MVC/Controlador/<?php echo $articulo['imagen']; ?>" 
                     alt="Imagen del artículo" 
                     onerror="this.onerror=null; this.src='http://localhost/MVC/Controlador/imagen/NOIMAGE.jpg';">
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

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
