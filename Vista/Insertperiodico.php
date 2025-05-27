<?php

require_once '../Modelo/Datosautor2.php';
require_once '../Modelo/Datosperiodico.php';
require_once '../Modelo/Conexion.php';
require_once '../Modelo/periodico.php';
require_once '../Modelo/DatosPA.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {

$periodicoModelo = new misPeriodicos(); 
$autor2Modelo = new misAutores(); 
$nombrePModelo = new miPeriodico(); 

} catch (Exception $e) {
    echo "<script>alert('Error al crear instancias de los modelos: " . $e->getMessage() . "');</script>";
}
try {

$autores2 = $autor2Modelo->verAutores(); 
$periodicos = $periodicoModelo->verTodosLosPeriodicos(); 
$nombrePeriodico = $nombrePModelo->verPeriodico(); 

} catch (Exception $e) {
    echo "<script>alert('Error al obtener datos para formularios: " . $e->getMessage() . "');</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Insertar Periódico</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>

    <?php include '../Libreria/libreriacss.php'; ?>
    <?php include '../Libreria/libreriajs.php'; ?>
    
    <link rel="stylesheet" href="../Libreria/Modal.css">
    <link rel="stylesheet" href="../Libreria/insertar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   
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
            <li><a href="../Vista/VistaPrestamos.php">Prestamos</a></li>
            <li><a href="../Vista/Insertar.php" >Insertar Libro</a></li>
            <li><a href="../Vista/Insertrevista.php" >Insertar Revista</a></li>
            <li><a href="../Vista/Insertperiodico.php" class="active">Insertar Periodico</a></li>
            <br>
            <img src="../imagen/logo-blanco.png" alt="Logo de la Biblioteca" class="logo">
        </ul>
    </div>
    <button class="toggle-btn" >☰</button>
<div class="container">
    <h1>Insertar Periódico</h1>
<form id="periodico-form" method="post" enctype="multipart/form-data">

        <table class="table table-bordered"  >
            <tr>
                <td><label for="nombre">Nombre periódico:</label></td>
                <td>
                    <select id="nombre" name="nombre" style="width: 800px;" required>
                        <option value="">Seleccionar Periódico</option>
                        <?php foreach ($nombrePeriodico as $periodicoss) : ?>
                            <option value="<?php echo htmlspecialchars($periodicoss['idperiodico'], ENT_QUOTES, 'UTF-8'); ?>" 
                                    data-nombre="<?php echo htmlspecialchars($periodicoss['periodicos'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($periodicoss['periodicos'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" id="TipoPeriodico" name="TipoPeriodico">
                    <button type="button" onclick="openModal('editorialModal')">➕ Agregar Periódico</button>
                </td>
            </tr>

            <tr>
                <td><label for="proveedor">Proveedor:</label></td>
                <td><input type="text" name="proveedor" placeholder="Proveedor" required></td>
            </tr>

            <tr>
                <td><label for="fecha">Fecha:</label></td>
                <td><input type="date" name="fecha" required></td>
            </tr>

            <tr>
                <td><label for="imagen">Imagen:</label></td>
                <td><input type="file" name="imagen"></td>
            </tr>

            <tr>
                <td colspan="2"><h2>Artículos</h2></td>
            </tr>

            <tr>
                <td><label for="insertarArticulo">Agregar Artículo:</label></td>
                <td>
                    <button type="button" onclick="openArticuloModal()">Escribir Artículo</button>
                    <input type="hidden" id="articulosDatos" name="articulosDatos">
                    <br>
                    <span id="articulosTexto"></span>
                </td>
            </tr>

            <tr>
                <td><label for="imagenArticulo">Cargar Imágenes:</label></td>
                <td><input type="file" id="imagenArticulo" name="imagenArticulo[]" accept="image/*" multiple></td>
            </tr>

            <tr>
                <td colspan="2" style="text-align: center;">
                    <input type="submit" value="Guardar Periódico">
                </td>
            </tr>
        </table>
    </form>
</div>

<div id="articuloModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeArticuloModal()">&times;</span>
        <h2>Agregar Artículo</h2>
        
        <table class="table table-bordered">
            <tr>
                <td style="width: 120px;"><label for="autor_articulo">Autor:</label></td>
                <td>
                    <select id="autor_articulo" name="autor_articulo" style="width: 100%;">
                        <option value="">Seleccionar Autor</option>
                        <?php foreach ($autores2 as $autor) : ?>
                            <option value="<?php echo htmlspecialchars($autor['id2autor'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($autor['autores2'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" onclick="openModal('autorModal')">➕ Agregar Autor</button>
                </td>
            </tr>

            <tr>
                <td><label for="titulo_articulo">Título del Artículo:</label></td>
                <td><input type="text" id="titulo_articulo" placeholder="Título del artículo"></td>
            </tr>

            <tr>
                <td><label for="pagina">Página:</label></td>
                <td><input type="text" id="pagina" placeholder="Página"></td>
            </tr>

            <tr>
                <td colspan="2" style="text-align: center;">
                    <button type="button" onclick="agregarArticulo()">Agregar</button>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <h3>Artículo Agregado:</h3>
                    <ul id="listaArticulos"></ul>
                </td>
            </tr>

            <tr>
                <td colspan="2" style="text-align: center;">
                    <button type="button" onclick="guardarArticulosTemporal()">Guardar Articulo(s)</button>
                </td>
            </tr>
        </table>
    </div>
</div>


   <!-- Modal para agregar Autor 1 -->
   <div id="autorModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('autorModal')">&times;</span>
    <h2>Agregar Autor</h2>
    <form id="form-autor">
      <label for="nuevoAutor">Nombre del Autor:</label>
      <input type="text" class="extend" id="nuevoAutor" name="nuevoAutor" placeholder="MAYÚSCULAS" required>
      <button type="button" onclick="agregarAutor()">Agregar Autor</button>
    </form>
  </div>
</div>

<div id="editorialModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('editorialModal')">&times;</span>
    <h2>Agregar Periodico</h2>
    <form id="form-editorial">
      <label for="nuevoPeriodico">Nombre del periodico:</label>
      <input type="text" id="nuevoPeriodico" name="nuevoPeriodico" placeholder="MAYÚSCULAS" required>
      <button type="button" class="extend" onclick="agregarPeriodico()">Agregar Editorial</button>
    </form>
  </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            <script src="../Controlador/ControinsertPeriodico.js"></script>
<script>
        $(document).ready(function() {
        $('select').select2(); 
        $('#periodico-form').submit(function(event) {
    console.log("Formulario enviado");
});
    });
</script>
<script>
    
let articulosArray = [];

function openArticuloModal() {
    document.getElementById("articuloModal").style.display = "block";
}

function closeArticuloModal() {
    document.getElementById("articuloModal").style.display = "none";
}
function agregarArticulo() {
    let autorSelect = document.getElementById("autor_articulo");
    let autor_articulo = autorSelect.value;
    let titulo_articulo = document.getElementById("titulo_articulo").value;
    let pagina = document.getElementById("pagina").value;



    if (autor_articulo.trim() === "" || titulo_articulo.trim() === "" || pagina.trim() === "") {
        alert("Todos los campos son obligatorios");
        return;
    }

    // Guardar en array temporal
    articulosArray.push({ autor_articulo, titulo_articulo, pagina });

    // Agregar a la lista visual
    let lista = document.getElementById("listaArticulos");
    let item = document.createElement("li");
    item.textContent = `Autor: ${autorSelect.options[autorSelect.selectedIndex].text}, Título: ${titulo_articulo}, Pagina: ${pagina}}`;
    lista.appendChild(item);

    // Limpiar inputs
    autorSelect.value = "";
    document.getElementById("titulo_articulo").value = "";
    document.getElementById("pagina").value = "";
}

function guardarArticulosTemporal() {
    if (articulosArray.length === 0) {
        alert("Debe agregar al menos un artículo antes de guardar.");
        return;
    }

    document.getElementById("articulosDatos").value = JSON.stringify(articulosArray);
    document.getElementById("articulosTexto").innerText = `Artículos: ${articulosArray.length} agregados`;

    closeArticuloModal();
}

</script>

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

