<?php
require_once '../Modelo/Datosarea.php';
require_once '../Modelo/Datosautor.php';
require_once '../Modelo/Datoslibros.php';
require_once '../Modelo/Datoseditorial.php';
require_once '../Modelo/Datosclase.php';
require_once '../Modelo/Datosestado.php';
require_once '../Modelo/Datosmedio.php';
require_once '../Modelo/Datosorigen.php';
require_once '../Modelo/Datosseccion.php';
require_once '../Modelo/Conexion.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {

$libroModelo = new misLibros(); 
$autorModelo = new misAutores(); 
$seccionModelo = new misSecciones(); 
$origenModelo = new misOrigenes(); 
$medioModelo = new misMedios(); 
$estadoModelo = new misEstados(); 
$editorialModelo = new misEditoriales(); 
$claseModelo = new misClases(); 
$areaModelo= new misAreas();
} catch (Exception $e) {
    echo "<script>alert('Error al crear instancias de los modelos: " . $e->getMessage() . "');</script>";
}
try {

$autores = $autorModelo->verAutores(); 
$secciones = $seccionModelo->verSecciones(); 
$origenes = $origenModelo->verOrigenes(); 
$medios = $medioModelo->verMedios(); 
$estados = $estadoModelo->verEstados(); 
$editoriales = $editorialModelo->verEditoriales(); 
$clases = $claseModelo->verClases(); 
$areas = $areaModelo->verAreas();
} catch (Exception $e) {
    echo "<script>alert('Error al obtener datos para formularios: " . $e->getMessage() . "');</script>";
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Libros</title>
    <?php include '../Libreria/libreriacss.php'; ?>
    <?php include '../Libreria/libreriajs.php'; ?>
    <script src="../Controlador/Controinsert.js"></script>
    <link rel="stylesheet" href="../Libreria/Modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {

            font-family: Arial, sans-serif;
            background-color: #f7f8fa;
            color: #333;
            margin: 0;
            padding: 21px;
            background-color: #e5e5f7;
background-image:  linear-gradient(#6fdeab 3px, transparent 3px), linear-gradient(90deg, #6fdeab 3px, transparent 3px), linear-gradient(#6fdeab 1.5px, transparent 1.5px), linear-gradient(90deg, #6fdeab 1.5px, #e5e5f7 1.5px);
background-size: 75px 75px, 75px 75px, 15px 15px, 15px 15px;
background-position: -3px -3px, -3px -3px, -1.5px -1.5px, -1.5px -1.5px;
            
        }
        /* Modal General */
.modal {
    display: none; /* Ocultar por defecto */
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto; /* Habilitar scroll si es necesario */
    background-color: rgba(0, 0, 0, 0.5); /* Fondo semitransparente */
}

/* Contenido del Modal */
.modal-content {
    background-color: #f9f9f9;
    margin: 15% auto; /* Centrar vertical y horizontal */
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    width: 100px; /* Reduce el ancho del modal */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

/* Botón de Cerrar */
.modal-content .close {
    color: #aaa;
    float: right;
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
}

.modal-content .close:hover,
.modal-content .close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

/* Estilo del Formulario */
form {
    display: flex;
    flex-direction: column; /* Los elementos del formulario en columna */
    gap: 15px; /* Espaciado uniforme entre elementos */
}

/* Inputs Más Largos */
form input[type="text"] {
    width: 100%; /* Ajustar al ancho del contenedor */
    padding: 10px 15px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-sizing: border-box;
}

/* Estilo en Foco para Inputs */
form input[type="text"]:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

/* Botón de Enviar */
form button {
    padding: 10px 20px;
    font-size: 16px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

form button:hover {
    background-color: #0056b3;
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
    max-width: 1100px; /* Ancho máximo del modal */
    width: 100%; /* El modal puede ocupar todo el ancho disponible, pero sin exceder el máximo */
    position: relative; /* Necesario para posicionar el botón de cierre */
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
        .sidebar {
    width: 250px;
    background-color: #51d9ce;
    color: white;
    padding-top: 20px;
    position: fixed;
    height: 100%;
    top: 0;
    left: 0;
    transition: width 0.1s ease; /* Añadido para animar la transición al colapsar */
}

.sidebar h2 {
    text-align: center;
    color: #fff;
    padding: 20px 0;
    margin: 0;
}
.logo {
            width: 160px;
            height: auto;
            margin-bottom: 20px;
            margin: 35px;
        }
.sidebar ul {
    list-style-type: none;
    padding: 0;
}

.sidebar ul li {
    padding: 15px;
    text-align: center;
}

.sidebar ul li a {
    color: white;
    text-decoration: none;
    display: block;
    font-size: 18px;
}
.sidebar ul li a.active {
    background-color: #3db2a9; /* Un color ligeramente diferente */
    color: #ffffff; /* Asegúrate de mantener el texto legible */
    font-weight: bold; /* Opcional: para destacar */
}


.sidebar ul li a:hover {
    background-color: #49c4ba;
}

/* Contenido principal */
.content {
    margin-left: 250px;
    padding: 20px;
    flex-grow: 1;
    background-color: #fff;
    min-height: 100vh;
    transition: margin-left 0.3s ease; /* Animación para que el contenido se ajuste */
}

.content h1 {
    font-size: 24px;
    color: #333;
}

/* Estilos para el botón */
.toggle-btn {
    position: fixed;
    top: 20px;
    left: 20px;
    background-color: #5fe9de;
    color: white;
    border: none;
    padding: 10px;
    font-size: 20px;
    cursor: pointer;
    z-index: 10; /* Asegura que el botón esté por encima de otros elementos */
}

.sidebar.collapsed {
    width: 0;
    padding-top: 0;
    overflow: hidden;
}

.sidebar.collapsed ul {
    display: none;
}

/* Ajustes cuando el menú está colapsado */
.sidebar.collapsed + .content {
    margin-left: 0; /* El contenido ocupa todo el espacio */
}
    </style>
</head>

<body>
<div class="sidebar">
        <br>
        <br>
        
        <h2>Panel de Control</h2>
        <ul>
            <li><a href="../Vista/VistaLibros.php">Lista de Libros</a></li>
            <li><a href="../Vista/Insertar.php" class="active">Insertar Libro</a></li>
            <li><a href="../Vista/VistaPrestamos.php">Libros Prestados</a></li>
            <br>
            <br>
            <br>
            <img src="../imagen/logo-blanco.png" alt="Logo de la Biblioteca" class="logo">
        </ul>
    </div>
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
<div class="container">
    <h1>Agregar Libro</h1>
    <form method="POST" action="../Controlador/Controinsert.php" id="form-libro">
        <table class="table table-bordered">
            <tr>
                <td><label for="idlib">ID del libro:</label></td>
                <td><input type="text" id="IDLIB" name="IDLIB"  required></td>
            </tr>
            <tr>
                <td><label for="code">Código del libro:</label></td>
                <td><input type="text" id="Codigo" name="Codigo" required></td>
            </tr>
            <tr>
                <td><label for="titulo">Título:</label></td>
                <td><input type="text" id="Titulo" name="Titulo" placeholder="MAYÚSCULAS" required></td>
            </tr>
            <tr>
                <td><label for="existe">¿Existe?</label></td>
                <td>
                    <select id="Existe" name="Existe" required>
                        <option value="">Selecciona una opción</option>
                        <option value="SI">SI</option>
                        <option value="NO">NO</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="autor">Autor:</label></td>
                <td>
                    <select id="autorID" name="autorID" required onchange="updateAutor()">
                        <option value="">Seleccionar Autor</option>
                        <?php foreach ($autores as $autor) : ?>
                            <option value="<?php echo htmlspecialchars($autor['idautor'], ENT_QUOTES, 'UTF-8'); ?>" data-nombre="<?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" id="autor" name="autor">
                    <button type="button" onclick="openModal('autorModal')">Agregar Autor</button>
                </td>
            </tr>
            <tr>
                <td><label for="edicion">Edición:</label></td>
                <td><input type="number" id="Edicion" name="Edicion" required></td>
            </tr>
            <tr>
                <td><label for="costo">Costo:</label></td>
                <td><input type="number" id="Costo" name="Costo" required></td>
            </tr>
            <tr>
                <td><label for="fecha">Fecha:</label></td>
                <td><input type="date" id="Fecha" name="Fecha" required pattern="\d{4}-\d{2}-\d{2}"></td>
            </tr>
            <tr>
                <td><label for="estado">Estado:</label></td>
                <td>
                    <select id="Estado" name="Estado" required onchange="updateEstado()">
                        <option value="">Seleccionar Estado</option>
                        <?php foreach ($estados as $estado) : ?>
                            <option value="<?php echo htmlspecialchars($estado['IdEst'], ENT_QUOTES, 'UTF-8'); ?>" data-nombre="<?php echo htmlspecialchars($estado['Etados'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($estado['Etados'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" id="TipoEstado" name="TipoEstado">
                </td>
            </tr>
            <tr>
                <td><label for="origen">Origen:</label></td>
                <td>
                    <select id="Origen" name="Origen" required onchange="updateOrigen()">
                        <option value="">Seleccionar Origen</option>
                        <?php foreach ($origenes as $origen) : ?>
                            <option value="<?php echo htmlspecialchars($origen['idorigen'], ENT_QUOTES, 'UTF-8'); ?>" data-nombre="<?php echo htmlspecialchars($origen['origenes'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($origen['origenes'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" id="TipoOrigen" name="TipoOrigen">
                </td>
            </tr>
            <tr>
                <td><label for="editorial">Editorial:</label></td>
                <td>
                    <select id="Editorial" name="Editorial" required onchange="updateEditorial()">
                        <option value="">Seleccionar Editorial</option>
                        <?php foreach ($editoriales as $editorial) : ?>
                            <option value="<?php echo htmlspecialchars($editorial['ideditorial'], ENT_QUOTES, 'UTF-8'); ?>" data-nombre="<?php echo htmlspecialchars($editorial['editoriales'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($editorial['editoriales'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" id="TipoEditorial" name="TipoEditorial">
                    <button type="button" onclick="openModal('editorialModal')">Agregar Editorial</button>
                </td>
            </tr>
   
            <tr>
                <td><label for="area">Área:</label></td>
                <td>
                    <select id="Area" name="Area" required onchange="updateArea()">
                        <option value="">Seleccionar Área</option>
                        <?php foreach ($areas as $area) : ?>
                            <option value="<?php echo htmlspecialchars($area['idarea'], ENT_QUOTES, 'UTF-8'); ?>" data-nombre="<?php echo htmlspecialchars($area['areas'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($area['areas'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" id="TipoArea" name="TipoArea">
                </td>
            </tr>
            <tr>
                <td><label for="medio">Medio:</label></td>
                <td><select id="Medio" name="Medio" required onchange="updateMedio()">
                    <option value="">Seleccionar Medio</option>
                    <?php foreach ($medios as $medio) : ?>
                        <option value="<?php echo htmlspecialchars($medio['id'], ENT_QUOTES, 'UTF-8'); ?>" data-nombre="<?php echo htmlspecialchars($medio['tipo'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($medio['tipo'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" id="TipoMedio" name="TipoMedio">
            </td>
        </tr>
        <tr>
        <td><label for="clase">Clase:</label></td>
        <td> <select id="Clase" name="Clase" required onchange="updateClase()">
        <option value="">Seleccionar Clase</option>
        <?php foreach ($clases as $clase) : ?>
            <option value="<?php echo htmlspecialchars($clase['idclase'], ENT_QUOTES, 'UTF-8'); ?>" data-nombre="<?php echo htmlspecialchars($clase['clases'], ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($clase['clases'], ENT_QUOTES, 'UTF-8'); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <input type="hidden" id="TipoClase" name="TipoClase">
    </td>
    </tr>

    <tr>
    <td><label for="observacion">Observación:</label></td>
    <td><input type="text" id="Observacion" name="Observacion" placeholder="MAYÚSCULAS" required></td>  
</tr>
<tr>
            
<td><label for="seccion">Sección:</label>
<td>  <select id="Seccion" name="Seccion" required onchange="updateSeccion()">
                    <option value="">Seleccionar Sección</option>
                    <?php foreach ($secciones as $seccion) : ?>
                    <option value="<?php echo htmlspecialchars($seccion['idseccion'], ENT_QUOTES, 'UTF-8'); ?>" data-nombre="<?php echo htmlspecialchars($seccion['secciones'], ENT_QUOTES, 'UTF-8'); ?>">
                      <?php echo htmlspecialchars($seccion['secciones'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                 <input type="hidden" id="TipoSeccion" name="TipoSeccion">
</td>  
</tr>          
<tr>
            
<td><label for="temas">Temas:</label></td>
<td><input type="text" id="Temas" name="Temas" placeholder="MAYÚSCULAS" required></td>
           
</tr>
           
<td><label for="codinv">Código de inventario:</label></td>
<td> <input type="number" id="codinv" name="codinv" required></td>
            

</tr>
<td>
     <label for="npag">Número de páginas:</label></td>
     <td><input type="number" id="npag" name="npag" required></td>
           
</tr>
            
<td><label for="Fimpresion">Fecha de impresión:</label></td>
<td><input type="number" id="Fimpresion" name="Fimpresion" placeholder="Año" required></td>
            
</tr>
           
<td><label for="Temas2">Temas 2:</label></td>
<td><input type="text" id="Temas2" name="Temas2" placeholder="MAYÚSCULAS" required></td>
            
</tr>
        
<td><label for="entrainv">¿Entra en inventario?:</label></td>
            <td><select id="Entraimv" name="Entrainv" required>
                         <option value="">Selecciona una opción</option> 
                         <option value="SI">SI</option>
                         <option value="NO">NO</option>
                </select></td>
</tr>
            <tr>
                <td colspan="2">
                    <button type="submit">Agregar Libro</button>
                    <button type="button" id="verLibrosBtn">Ver Libros</button>
                </td>
            </tr>
        </table>
    </form><div id="autorModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('autorModal')">&times;</span>
    <h2>Agregar Autor</h2>
    <form id="form-autor">
      <label for="nuevoAutor">Nombre del Autor:</label>
      <input type="text" class="extend"id="nuevoAutor" name="nuevoAutor" placeholder="MAYÚSCULAS" required>
      <button type="button" onclick="agregarAutor()">Agregar Autor</button>
    </form>
  </div>
</div>
<div id="editorialModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('editorialModal')">&times;</span>
    <h2>Agregar Editorial</h2>
    <form id="form-editorial">
      <label for="nuevaEditorial">Nombre de la Editorial:</label>
      <input type="text" id="nuevaEditorial" name="nuevaEditorial" placeholder="MAYÚSCULAS" required>
      <button type="button" class="extend" onclick="agregarEditorial()">Agregar Editorial</button>
    </form>
  </div>
</div>
</div>

    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('select').select2(); 
        $('#form-libro').submit(function(event) {
    console.log("Formulario enviado");
});
    });
    
</script>
    <script>

function updateAutor() {
    const autorSelect = document.getElementById('autorID');
    const selectedOption = autorSelect.options[autorSelect.selectedIndex];
    const autorNombre = selectedOption.getAttribute('data-nombre');
    
    document.getElementById('autor').value = autorNombre;
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
<script>
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('collapsed');
    }
</script>
</body>
</html>
