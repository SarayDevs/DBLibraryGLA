<?php
require_once '../Modelo/Conexion.php';
require_once '../Modelo/Datosrevista.php';  
require_once '../Modelo/revista.php'; 
require_once '../Modelo/DatosRA.php'; 
require_once '../Modelo/Datosautor3.php';  
require_once '../Modelo/Datosprest.php';

session_start();

$revistaModelo = new misRevistas(); 
$prestModelo = new misPrestados();
$revistModelo = new miRevista();
$autorModelo = new misAutores();
$articuloModelo = new misArticulosR();

if (!isset($_GET['id'])) {
    echo "No se proporcionó un ID de revista.";
    exit;
}

$idRevista = $_GET['id'];

$detalleRevista = $revistaModelo->verRevistasID($idRevista);
if (empty($detalleRevista)) {
    echo "La revista no existe o no se encontró.";
    exit;
}
$detalleRevista = $detalleRevista[0];

$revist = $revistModelo->verRevistaID($detalleRevista['titulo']);
$nombreRevista = !empty($revist) ? $revist[0]['revistas'] : 'Desconocido';

$articulos = $articuloModelo->verArticulosRID($idRevista);
$autorNombre = '';
if (!empty($articulos)) {
    $autor3 = $autorModelo->verAutorID($articulos[0]['autor']);
    $autorNombre = !empty($autor3) ? $autor3[0]['autores3'] : 'Desconocido';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Revista</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <link rel="stylesheet" href="../Libreria/detalleexterno.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
</head>
<body>

<div class="container">
    <div class="left-section">
        <h1><?php echo htmlspecialchars($nombreRevista); ?></h1>
        <div class="details">
            <p><strong>ISSN:</strong> <?php echo htmlspecialchars($detalleRevista['issn']); ?></p>
            <p><strong>Proveedor:</strong> <?php echo htmlspecialchars($detalleRevista['proveedor']); ?></p>
            <p><strong>Año:</strong> <?php echo htmlspecialchars($detalleRevista['anio']); ?></p>
            <p><strong>Volumen:</strong> <?php echo htmlspecialchars($detalleRevista['volumen']); ?></p>
            <p><strong>Edición:</strong> <?php echo htmlspecialchars($detalleRevista['edicion']); ?></p>
            <p><strong>Número:</strong> <?php echo htmlspecialchars($detalleRevista['numero']); ?></p>
            <p><strong>Resumen:</strong> <?php echo !empty($detalleRevista['resumen']) ? htmlspecialchars($detalleRevista['resumen']) : 'No digitada'; ?></p>
        </div>
        <a href="javascript:history.back()" class="back-link">Volver</a>
    </div>

    <div class="right-section">
        <h1>Carátula</h1>
        <img src="http://localhost/MVC<?php echo htmlspecialchars($detalleRevista['imagen']); ?>" 
             alt="<?php echo htmlspecialchars($detalleRevista['titulo']); ?>" 
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
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articulos as $articulo): ?>
                <tr>
                    <td><?= htmlspecialchars($autorNombre ?? 'Desconocido'); ?></td>
                    <td><?= htmlspecialchars($articulo['titulo_articulo'] ?? 'NA'); ?></td>
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
