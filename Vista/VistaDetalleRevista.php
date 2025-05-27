<?php
require_once '../Modelo/Conexion.php';
require_once '../Modelo/Datosrevista.php';  
require_once '../Modelo/revista.php'; 
require_once '../Modelo/DatosRA.php'; 
require_once '../Modelo/Datosautor3.php';  
require_once '../Modelo/Datosprest.php';

include('../App/Inde.php'); 

if (!isset($_GET['id'])) {
    echo "No se proporcionó un ID de revista.";
    exit;
}

$idRevista = $_GET['id'];

$revistaModelo = new misRevistas(); 
$prestModelo = new misPrestados();
$revistModelo = new miRevista();
$autorModelo = new misAutores();
$articuloModelo = new misArticulosR();

$detalleRevista = $revistaModelo->verRevistasID($idRevista);

if (empty($detalleRevista)) {
    echo "El periódico no existe o no se encontró.";
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
        <h1><?= htmlspecialchars($nombreRevista) ?></h1>

        <div class="details">
            <p><strong>ISSN:</strong> <?= htmlspecialchars($detalleRevista['issn']); ?></p>
            <p><strong>Proveedor:</strong> <?= htmlspecialchars($detalleRevista['proveedor']); ?></p>
            <p><strong>Volumen:</strong> <?= htmlspecialchars($detalleRevista['volumen']); ?></p>
            <p><strong>Edición:</strong> <?= htmlspecialchars($detalleRevista['edicion']); ?></p>
            <p><strong>Número:</strong> <?= htmlspecialchars($detalleRevista['numero']); ?></p>
            <p><strong>Año:</strong> <?= htmlspecialchars($detalleRevista['anio']); ?></p>
            <p><strong>Índice de temas:</strong> <?= htmlspecialchars($detalleRevista['subject_index']); ?></p>
            <p><strong>Resumen:</strong>
                <?= !empty($detalleRevista['resumen']) ? htmlspecialchars($detalleRevista['resumen']) : '<em>Reseña no digitada</em>'; ?>
            </p>
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
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articulos as $art): ?>
                        <?php
                        $autorNombre = 'Desconocido';
                        if (!empty($art['autor'])) {
                            $autorInfo = $autorModelo->verAutorID($art['autor']);
                            if (!empty($autorInfo)) {
                                $autorNombre = $autorInfo[0]['autores3'];
                            }
                        }
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($autorNombre); ?></td>
                            <td style="max-width: 290px;"><?= htmlspecialchars($art['titulo_articulo'] ?? ''); ?></td>
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
            <p>No hay artículos disponibles para esta revista.</p>
        <?php endif; ?>

        <a href="javascript:history.back()" class="back-link">Volver</a>
        <a href="ActualizarRevistas.php?id=<?php echo $detalleRevista['id']; ?>" class="back-link">Editar</a> 
    </div>

    <div class="right-section">
        <h1>Carátula</h1>
        <img src="http://localhost/MVC<?= htmlspecialchars($detalleRevista['imagen']); ?>" 
             alt="<?= htmlspecialchars($detalleRevista['titulo']); ?>" 
             width="200" height="500"
             onerror="this.onerror=null; this.src='http://localhost/MVC/Controlador/imagen/NOIMAGE.jpg';">
        <br><br>
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