<?php
require_once '../Modelo/Datoslibros.php';
include('../App/Inde.php'); 
if (!isset($_GET['id'])) {
    echo "No se proporcionó un ID de libro.";
    exit;
}

$idLibro = $_GET['id'];
$libros = new misLibros();
$detalleLibro = $libros->verLibroID($idLibro);

if (empty($detalleLibro)) {
    echo "El libro no existe o no se encontró.";
    exit;
}

$detalleLibro = $detalleLibro[0]; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Libro</title>
    <?php include '../Libreria/libreriacss.php'; ?>
    <?php include '../Libreria/libreriajs.php'; ?>
    <link rel="stylesheet" href="../Libreria/Modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f8fa;
            color: #333;
            margin: 0;
            padding: 20px;

background-color: #e5e5f7;
background-image:  linear-gradient(#6fdeab 3px, transparent 3px), linear-gradient(90deg, #6fdeab 3px, transparent 3px), linear-gradient(#6fdeab 1.5px, transparent 1.5px), linear-gradient(90deg, #6fdeab 1.5px, #e5e5f7 1.5px);
background-size: 75px 75px, 75px 75px, 15px 15px, 15px 15px;
background-position: -3px -3px, -3px -3px, -1.5px -1.5px, -1.5px -1.5px;
            
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            
        
        }

        h1 {
            font-size: 2em;
            color: #444;
            text-align: center;
            margin-bottom: 1em;
        }

        p {
            font-size: 17px;
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
    </style>
</head>
<body>
<div class="container">
    <h1>Detalle del Libro</h1>

    <p><strong>ID:</strong> <?php echo $detalleLibro['ID']; ?></p>
    <p><strong>IdExtra:</strong> <?php echo $detalleLibro['IDLIB']; ?></p>
    <p><strong>Código:</strong> <?php echo $detalleLibro['Codigo']; ?></p>
    <p><strong>Título:</strong> <?php echo $detalleLibro['Titulo']; ?></p>
    <p><strong>Existe:</strong> <?php echo $detalleLibro['Existe']; ?></p>
    <p><strong>Autor:</strong> <?php echo $detalleLibro['Autor']; ?></p>
    <p><strong>Edición:</strong> <?php echo $detalleLibro['Edicion']; ?></p>
    <p><strong>Costo:</strong> <?php echo $detalleLibro['Costo']; ?></p>
    <p><strong>Fecha:</strong> <?php echo $detalleLibro['Fecha']; ?></p>
    <p><strong>Estado:</strong> <?php echo $detalleLibro['TipoEstado']; ?></p>
    <p><strong>Origen:</strong> <?php echo $detalleLibro['TipoOrigen']; ?></p>
    <p><strong>Editorial:</strong> <?php echo $detalleLibro['TipoEditorial']; ?></p>
    <p><strong>Área:</strong> <?php echo $detalleLibro['TipoArea']; ?></p>
    <p><strong>Medio:</strong> <?php echo $detalleLibro['NombreMedio']; ?></p>
    <p><strong>Clase:</strong> <?php echo $detalleLibro['TipoClase']; ?></p>
    <p><strong>Observaciónes:</strong> <?php echo $detalleLibro['Observacion']; ?></p>
    <p><strong>Sección:</strong> <?php echo $detalleLibro['TipoSeccion']; ?></p>
    <p><strong>Temas:</strong> <?php echo $detalleLibro['Temas']; ?></p>
    <p><strong>Código Inventario:</strong> <?php echo $detalleLibro['codinv']; ?></p>
    <p><strong>Número de Páginas:</strong> <?php echo $detalleLibro['npag']; ?></p>
    <p><strong>Fecha de Impresión:</strong> <?php echo $detalleLibro['Fimpresion']; ?></p>
    <p><strong>Segundo Tema:</strong> <?php echo $detalleLibro['Temas2']; ?></p>
    <p><strong>¿Entra en inventario?:</strong> <?php echo $detalleLibro['Entrainv']; ?></p>

    <a href="javascript:history.back()" class="back-link">Volver a la página anterior</a>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
</html>