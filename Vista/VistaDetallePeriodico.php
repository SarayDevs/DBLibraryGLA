<?php
require_once '../Modelo/Conexion.php';
require_once '../Modelo/Datosperiodico.php';  
require_once '../Modelo/periodico.php'; 
require_once '../Modelo/DatosPA.php'; 
require_once '../Modelo/Datosautor2.php';  
require_once '../Modelo/Datosprest.php';

include('../App/Inde.php'); 

if (!isset($_GET['id'])) {
    echo "No se proporcionó un ID de periódico.";
    exit;
}

$idPeriodico = $_GET['id'];

$periodicoModelo = new misPeriodicos(); 
$prestModelo = new misPrestados();
$periodicModelo = new miPeriodico();
$autorModelo = new misAutores();
$articuloModelo = new misArticulos();

$detallePeriodico = $periodicoModelo->verPeriodicosID($idPeriodico);

if (empty($detallePeriodico)) {
    echo "El periódico no existe o no se encontró.";
    exit;
}

$detallePeriodico = $detallePeriodico[0];

$periodic = $periodicModelo->verPeriodicoID($detallePeriodico['nombre']);
$nombrePeriodico = !empty($periodic) ? $periodic[0]['periodicos'] : 'Desconocido';

$articulos = $articuloModelo->verArticulosPID($idPeriodico);
$autorNombre = '';

if (!empty($articulos)) {
    $autor2 = $autorModelo->verAutorID($articulos[0]['autor']);
    $autorNombre = !empty($autor2) ? $autor2[0]['autores2'] : 'Desconocido';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Periódico</title>
    <?php include '../Libreria/libreriacss.php'; ?>
    <?php include '../Libreria/libreriajs.php'; ?>
    <link rel="stylesheet" href="../Libreria/Modal.css">
    <link rel="stylesheet" href="../Libreria/detalles.css">
    <style>
    .tabla-bonita {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-family: Arial, sans-serif;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .tabla-bonita thead {
        background-color: #007BFF;
        color: white;
    }

    .tabla-bonita th, .tabla-bonita td {
        padding: 12px 15px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .tabla-bonita tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .tabla-bonita tbody tr:hover {
        background-color: #f1f1f1;
    }
</style>
</head>
<body>
<div class="container">

    <div class="left-section">
        <h1><?= htmlspecialchars($nombrePeriodico) ?></h1>
        <div class="details">
            <p><strong>Item: </strong> <?= $detallePeriodico['id']; ?></p>
            <p><strong>Proveedor: </strong> <?= $detallePeriodico['proveedor']; ?></p>
            <p><strong>Fecha: </strong> <?= $detallePeriodico['fecha']; ?></p>
        </div>

        <?php if (!empty($articulos)): ?>
            <br>
            <h2>Artículos</h2>
            <br>
            <table class="tabla-bonita">
                <thead>
                    <tr>
                        <th>Autor</th>
                        <th style="width: 290px;">Título</th>
                        <th style="width: 80px;">Página</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articulos as $art): ?>
                        <tr>
                            <td><?= htmlspecialchars($autorNombre ?? 'Desconocido'); ?></td>
                            <td style="max-width: 290px;"><?= htmlspecialchars($art['titulo_articulo'] ?? ''); ?></td>
                            <td style="width: 80px;"><?= htmlspecialchars($art['pagina'] ?? ''); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div>
                <?php foreach ($articulos as $art): ?>
                    <?php if (!empty($art['imagen'])): ?>
                        <img src="http://localhost/MVC/Controlador/<?= $art['imagen']; ?>" 
                             alt="Imagen del artículo" width="150" style="display: block; margin: 10px 0;"
                             onerror="this.onerror=null; this.src='http://localhost/MVC/Controlador/imagen/NOIMAGE.jpg';">
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No hay artículos disponibles para este periódico.</p>
        <?php endif; ?>

        <a href="javascript:history.back()" class="back-link">Volver</a>
        <a href="ActualizarPeriodicos.php?id=<?php echo $detallePeriodico['id']; ?>" class="back-link">Editar</a> 
    </div>

    <div class="right-section">
        <h1>Carátula</h1>
        <img src="http://localhost/MVC<?= htmlspecialchars($detallePeriodico['imagen']); ?>" 
             alt="<?= htmlspecialchars($detallePeriodico['nombre']); ?>" 
             width="200" height="500" 
             onerror="this.onerror=null; this.src='http://localhost/MVC/Controlador/imagen/NOIMAGE.jpg';">
        <br><br>
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