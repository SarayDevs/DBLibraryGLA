
<?php
require_once '../Modelo/Datoslibros.php'; 
require_once '../Modelo/Datosautor.php'; 
require_once '../Modelo/Datosestado.php';
require_once '../Modelo/Datosorigen.php';
require_once '../Modelo/Datoseditorial.php';
require_once '../Modelo/Datosmedio.php';
require_once '../Modelo/Datosclase.php';
require_once '../Modelo/Datosarea.php';
require_once '../Modelo/Datosseccion.php';
include('../App/Inde.php'); 

$libroModelo = new misLibros(); 
$autorModelo = new misAutores();
$estadoModelo = new misEstados();
$origenModelo = new misOrigenes();
$editorialModelo = new misEditoriales();
$medioModelo = new misMedios();
$claseModelo = new misClases();
$areaModelo = new misAreas();
$seccionModelo = new misSecciones();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $libro = $libroModelo->verLibroID($id); 
    if (!$libro) {
        echo "Libro no encontrado";
        exit;
    }
} else {
    echo "ID de libro no especificado.";
    exit;
}


$autores = $autorModelo->verAutores(); 
$estados = $estadoModelo->verEstados();
$origenes = $origenModelo->verOrigenes();
$editoriales = $editorialModelo->verEditoriales();
$medios = $medioModelo->verMedios();
$clases = $claseModelo->verClases();
$areas = $areaModelo->verAreas();
$secciones = $seccionModelo->verSecciones();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Libro</title>
    <?php include '../Libreria/libreriacss.php'; ?>
    <?php include '../Libreria/libreriajs.php'; ?>
    <link rel="stylesheet" href="../Libreria/Modal.css">
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
            max-width: 1000px;
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

        .table {
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
        }
        .table td, .table th {
            padding: 12px;
            text-align: left;
        }
        .table td input, .table td select {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
            margin-top: 5px;
        }
        .form-control {
            margin-top: 5px;
            border-radius: 4px;
        }
        button[type="submit"], button[type="button"] {
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
        button[type="submit"]:hover, button[type="button"]:hover {
            background-color: #0056b3;
        }
        .modal-content {
            padding: 20px;
            border-radius: 8px;
            background-color: #fff;
            max-width: 500px;
            margin: auto;
        }
        .modal-content .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            right: 10px;
            top: 5px;
        }
        .modal-content .close:hover, .modal-content .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .modal {
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

    </style>
</head>
<body>
<div class="container">
<h1>Actualizar Libro</h1>

<h2> Libro <?php echo $id ?> </h2>

<form method="POST" id="form-actualizar-libro" class="actualizar-libro">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($libro[0]['ID'], ENT_QUOTES, 'UTF-8'); ?>">

    <table class="table table-bordered">
 

        <tr>
            <th><label for="IDLIB">Id libro:</label></th>
            <td><input type="text" name="IDLIB" id="IDLIB" value="<?php echo htmlspecialchars($libro[0]['IDLIB'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
        </tr>


        <tr>
            <th><label for="codigo">Código:</label></th>
            <td><input type="text" name="Codigo" id="Codigo" value="<?php echo htmlspecialchars($libro[0]['Codigo'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
        </tr>


        <tr>
            <th><label for="titulo">Título:</label></th>
            <td><input type="text" name="Titulo" id="Titulo" value="<?php echo htmlspecialchars($libro[0]['Titulo'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
        </tr>


        <tr>
            <th><label for="Existe">¿Existe?</label></th>
            <td>
                <select id="Existe" name="Existe">
                    <option value="SI" <?php echo ($libro[0]['Existe'] == 'SI') ? 'selected' : ''; ?>>SÍ</option>
                    <option value="NO" <?php echo ($libro[0]['Existe'] == 'NO') ? 'selected' : ''; ?>>NO</option>
                </select>
            </td>
        </tr>


        <tr>
            <th><label for="Autor">Autor:</label></th>
            <td>
                <select id="autorID" name="autorID"  required onchange="updateAutor()">
                    <?php foreach ($autores as $autor) : ?>
                        <option value="<?php echo htmlspecialchars($autor['idautor'], ENT_QUOTES, 'UTF-8'); ?>" 
                            data-nombre="<?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>" 
                            <?php echo ($libro[0]['autorID'] == $autor['idautor']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" id="Autor" name="Autor">
            </td>
        </tr>



        <tr>
            <th><label for="edicion">Edición:</label></th>
            <td><input type="number" name="Edicion" id="Edicion" value="<?php echo htmlspecialchars($libro[0]['Edicion'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
        </tr>


        <tr>
            <th><label for="costo">Costo:</label></th>
            <td><input type="number" name="Costo" id="Costo" value="<?php echo htmlspecialchars($libro[0]['Costo'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
        </tr>


        <tr>
            <th><label for="fecha">Fecha:</label></th>
            <td><input type="date" name="Fecha" id="Fecha" value="<?php echo htmlspecialchars($libro[0]['Fecha'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
        </tr>

        <tr>
            <th><label for="Estado">Estado:</label></th>
            <td>
                <select id="Estado" name="Estado"  required onchange="updateEstado()">
                    <?php foreach ($estados as $estado) : ?>
                        <option value="<?php echo htmlspecialchars($estado['IdEst'], ENT_QUOTES, 'UTF-8'); ?>" 
                        data-nombre="<?php echo htmlspecialchars($estado['IdEst'], ENT_QUOTES, 'UTF-8'); ?>"
                        <?php echo ($libro[0]['Estado'] == $estado['IdEst']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($estado['Etados'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" id="TipoEstado" name="TipoEstado">
            </td>
        </tr>

        <tr>
    <th><label for="origen">Origen:</label></th>
    <td>
        <select id="Origen" name="Origen"  required onchange="updateOrigen()">
            <?php foreach ($origenes as $origen) : ?>
                <option value="<?php echo htmlspecialchars($origen['idorigen'], ENT_QUOTES, 'UTF-8'); ?>" 
                data-nombre="<?php echo htmlspecialchars($origen['idorigen'], ENT_QUOTES, 'UTF-8'); ?>"
                <?php echo ($libro[0]['Origen'] == $origen['idorigen']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($origen['origenes'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" id="TipoOrigen" name="TipoOrigen">
    </td>
</tr>

<tr>
    <th><label for="editorial">Editorial:</label></th>
    <td>
        <select id="Editorial" name="Editorial"  required onchange="updateEditorial()">
            <?php foreach ($editoriales as $editorial) : ?>
                <option value="<?php echo htmlspecialchars($editorial['ideditorial'], ENT_QUOTES, 'UTF-8'); ?>" 
                data-nombre="<?php echo htmlspecialchars($editorial['ideditorial'], ENT_QUOTES, 'UTF-8'); ?>"
                <?php echo ($libro[0]['Editorial'] == $editorial['ideditorial']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($editorial['editoriales'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" id="TipoEditorial" name="TipoEditorial">
    </td>
</tr>

<tr>
    <th><label for="Area">Área:</label></th>
    <td>
        <select id="Area" name="Area"  required onchange="updateArea()">
            <?php foreach ($areas as $area) : ?>
                <option value="<?php echo htmlspecialchars($area['idarea'], ENT_QUOTES, 'UTF-8'); ?>" 
                data-nombre="<?php echo htmlspecialchars($area['idarea'], ENT_QUOTES, 'UTF-8'); ?>"
                <?php echo ($libro[0]['Area'] == $area['idarea']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($area['areas'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" id="TipoArea" name="TipoArea">
    </td>
</tr>

<tr>
    <th><label for="Medio">Medio:</label></th>
    <td>
        <select id="Medio" name="Medio"  required onchange="updateMedio()">
            <?php foreach ($medios as $medio) : ?>
                <option value="<?php echo htmlspecialchars($medio['id'], ENT_QUOTES, 'UTF-8'); ?>" 
                data-nombre="<?php echo htmlspecialchars($medio['id'], ENT_QUOTES, 'UTF-8'); ?>"
                <?php echo ($libro[0]['Medio'] == $medio['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($medio['tipo'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" id="TipoMedio" name="TipoMedio">
    </td>
</tr>

<tr>
    <th><label for="Clase">Clase:</label></th>
    <td>
        <select id="Clase" name="Clase"  required onchange="updateClase()">
            <?php foreach ($clases as $clase) : ?>
                <option value="<?php echo htmlspecialchars($clase['idclase'], ENT_QUOTES, 'UTF-8'); ?>" 
                data-nombre="<?php echo htmlspecialchars($clase['idclase'], ENT_QUOTES, 'UTF-8'); ?>"
                <?php echo ($libro[0]['Clase'] == $clase['idclase']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($clase['clases'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" id="TipoClase" name="TipoClase">
    </td>
</tr>
       
       <tr>
            <th><label for="Observacion">Observación:</label></th>
            <td><input type="text" name="Observacion" id="Observacion" value="<?php echo htmlspecialchars($libro[0]['Observacion'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
        </tr>


<tr>
    <th><label for="Seccion">Sección:</label></th>
    <td>
        <select id="Seccion" name="Seccion"  required onchange="updateSeccion()">
            <?php foreach ($secciones as $seccion) : ?>
                <option value="<?php echo htmlspecialchars($seccion['idseccion'], ENT_QUOTES, 'UTF-8'); ?>" 
                data-nombre="<?php echo htmlspecialchars($seccion['idseccion'], ENT_QUOTES, 'UTF-8'); ?>"
                <?php echo ($libro[0]['Seccion'] == $seccion['idseccion']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($seccion['secciones'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" id="TipoSeccion" name="TipoSeccion">
    </td>
</tr>

        <tr>
            <th><label for="Temas">Temas:</label></th>
            <td>
                <input type="text" name="Temas" id="Temas" value="<?php echo htmlspecialchars($libro[0]['Temas'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </td>
        </tr>

        <tr>
            <th><label for="codinv">Código inventario:</label></th>
            <td>
                <input type="number" name="codinv" id="codinv" value="<?php echo htmlspecialchars($libro[0]['codinv'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </td>
        </tr>

        <tr>
            <th><label for="npag">Número de páginas:</label></th>
            <td>
                <input type="number" name="npag" id="npag" value="<?php echo htmlspecialchars($libro[0]['npag'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </td>
        </tr>

        <tr>
            <th><label for="Fimpresion">Fecha de impresión:</label></th>
            <td>
                <input type="number" name="Fimpresion" id="Fimpresion" value="<?php echo htmlspecialchars($libro[0]['Fimpresion'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </td>
        </tr>

        <tr>
            <th><label for="Temas2">Temas 2:</label></th>
            <td>
                <input type="text" name="Temas2" id="Temas2" value="<?php echo htmlspecialchars($libro[0]['Temas2'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </td>
        </tr>
<tr>
    <th><label for="Entrainv">¿Entra inventario?:</label></th>
    <td>
        <select id="Entrainv" name="Entrainv">
            <option value="SI" <?php echo ($libro[0]['Entrainv'] == 'SI') ? 'selected' : ''; ?>>SÍ</option>
            <option value="NO" <?php echo ($libro[0]['Entrainv'] == 'NO') ? 'selected' : ''; ?>>NO</option>
        </select>
    </td>
</tr>

<tr>
            <td colspan="2">


    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <button type="submit" data-id="<?php echo $id; ?>">Actualizar</button>




            </td>
        </tr>
    </table>
</form>
            </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="../Controlador/Controactualizar.js"></script>
<script>

    function updateAutor() {
    const autorSelect = document.getElementById('autorID');
    const selectedOption = autorSelect.options[autorSelect.selectedIndex];
    const autorNombre = selectedOption.getAttribute('data-nombre');
    document.getElementById('Autor').value = autorNombre;
    }
    function updateSeccion() {
        const seccionSelect = document.getElementById('Seccion');
        const selectedOption = seccionSelect.options[seccionSelect.selectedIndex];
        const seccionNombre = selectedOption.getAttribute('data-nombre');
        document.getElementById('TipoSeccion').value = seccionNombre;
    }

    function updateOrigen() {
        const origenSelect = document.getElementById('Origen');
        const selectedOption = origenSelect.options[origenSelect.selectedIndex];
        const origenNombre = selectedOption.getAttribute('data-nombre');
        document.getElementById('TipoOrigen').value = origenNombre;
    }

    function updateMedio() {
        const medioSelect = document.getElementById('Medio');
        const selectedOption = medioSelect.options[medioSelect.selectedIndex];
        const medioNombre = selectedOption.getAttribute('data-nombre');
        document.getElementById('TipoMedio').value = medioNombre;
    }

    function updateEditorial() {
        const editorialSelect = document.getElementById('Editorial');
        const selectedOption = editorialSelect.options[editorialSelect.selectedIndex];
        const editorialNombre = selectedOption.getAttribute('data-nombre');
        document.getElementById('TipoEditorial').value = editorialNombre;
    }

    function updateClase() {
        const claseSelect = document.getElementById('Clase');
        const selectedOption = claseSelect.options[claseSelect.selectedIndex];
        const claseNombre = selectedOption.getAttribute('data-nombre');
        document.getElementById('TipoClase').value = claseNombre;
    }

    function updateArea() {
        const areaSelect = document.getElementById('Area');
        const selectedOption = areaSelect.options[areaSelect.selectedIndex];
        const areaNombre = selectedOption.getAttribute('data-nombre');
        document.getElementById('TipoArea').value = areaNombre;
    }

    function updateEstado(){
        const estadoSelect=document.getElementById('Estado');
        const selectedOption=estadoSelect.options[estadoSelect.selectedIndex];
        const estadoNombre =selectedOption.getAttribute('data-nombre');
        document.getElementById('TipoEstado').value=estadoNombre;
    }


</script>
</script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('select').select2(); // Aplica select2 a todos los selectores
    });
</script>


</body>
</html>

