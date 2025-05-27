<?php
require_once '../Modelo/Datosrevista.php';
require_once '../Modelo/revista.php';
require_once '../Modelo/Datosautor3.php';
require_once '../Modelo/Conexion.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {

$RevistaModelo = new misRevistas(); 
$RevistaTitulo = new miRevista(); 
$autor3Modelo = new misAutores(); 

} catch (Exception $e) {
    echo "<script>alert('Error al crear instancias de los modelos: " . $e->getMessage() . "');</script>";
}
try {

$autores = $autor3Modelo->verAutores(); 
$revistas = $RevistaModelo->verTodasLasRevistas(); 
$revistaTitulo = $RevistaTitulo->verRevista();

} catch (Exception $e) {
    echo "<script>alert('Error al obtener datos para formularios: " . $e->getMessage() . "');</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Insertar Revista</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    
    <?php include '../Libreria/libreriacss.php'; ?>
    <?php include '../Libreria/libreriajs.php'; ?>
    <link rel="stylesheet" href="../Libreria/insertar.css">
    <link rel="stylesheet" href="../Libreria/Modal.css">
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
            <li><a href="../Vista/Insertrevista.php" class="active">Insertar Revista</a></li>
            <li><a href="../Vista/Insertperiodico.php" >Insertar Periodico</a></li>

            <br>
            <img src="../imagen/logo-blanco.png" alt="Logo de la Biblioteca" class="logo">
        </ul>
    </div>
    <button class="toggle-btn">☰</button>

<div class="container">
    <h1>Insertar Revista</h1>
    <form method="post" id="revista-form" enctype="multipart/form-data">
        <table class="table table-bordered">
            <tr>
                <td><label for="issn">ISSN:</label></td>
                <td><input type="text" name="issn" placeholder="ISSN" required></td>
            </tr>

            <tr>
                <td><label for="titulo">Título:</label></td>
                <td>
                    <select id="titulo" name="titulo" style="width: 800px;"style="width: 100%;" required>
                        <option value="">Seleccionar Revista</option>
                        <?php foreach ($revistaTitulo as $revistass) : ?>
                            <option value="<?php echo htmlspecialchars($revistass['idrevista'], ENT_QUOTES, 'UTF-8'); ?>" 
                                    data-nombre="<?php echo htmlspecialchars($revistass['revistas'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($revistass['revistas'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" id="TipoRevista" name="TipoRevista">
                    <button type="button" onclick="openModal('editorialModal')">➕ Agregar Revista</button>
                </td>
            </tr>

            <tr>
                <td><label for="proveedor">Proveedor:</label></td>
                <td><input type="text" name="proveedor" placeholder="Proveedor" required></td>
            </tr>

            <tr>
                <td><label for="volumen">Volumen:</label></td>
                <td><input type="text" name="volumen" placeholder="Volumen"></td>
            </tr>

            <tr>
                <td><label for="edicion">Edición:</label></td>
                <td><input type="text" name="edicion" placeholder="Edición"></td>
            </tr>

            <tr>
                <td><label for="numero">Número:</label></td>
                <td><input type="text" name="numero" placeholder="Número"></td>
            </tr>

            <tr>
                <td><label for="anio">Año:</label></td>
                <td><input type="number" name="anio" placeholder="Año"></td>
            </tr>

            <tr>
                <td><label for="resumen">Resumen:</label></td>
                <td><textarea name="resumen" placeholder="Resumen" rows="3" style="width: 100%;"></textarea></td>
            </tr>

            <tr>
                <td><label for="subject_index">Subject Index:</label></td>
                <td><textarea name="subject_index" placeholder="Subject Index" rows="3" style="width: 100%;"></textarea></td>
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
        </table>

  <input type="submit" value="Guardar Revista">
</form>
</div>

<!-- Modal para agregar artículos -->
<div id="articuloModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeArticuloModal()">&times;</span>
        <h2>Agregar Artículo</h2>

        <table class="table table-bordered">
            <tr>
                <td style="width: 150px;"><label for="autor_articulo">Autor:</label></td>
                <td>
                    <select id="autor_articulo" class="extend" name="autor_articulo" style="width: 100%;">
                        <option value="">Seleccionar Autor</option>
                        <?php foreach ($autores as $autor) : ?>
                            <option value="<?php echo htmlspecialchars($autor['idautor3'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($autor['autores3'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" onclick="openModal('autorModal')">➕ Agregar Autor</button>
                </td>
            </tr>
            <tr>
                <td><label for="titulo_articulo">Título del Artículo:</label></td>
                <td>
                    <input type="text" id="titulo_articulo" class="extend" style="width: 100%;">
                </td>
            </tr>
        </table>

        <br>
        <button type="button" onclick="agregarArticulo()">Agregar</button>
        <br><br>

        <h3>Artículo(s) Agregado(s):</h3>
        <ul id="listaArticulos"></ul>

        <button type="button" onclick="guardarArticulosTemporal()">Guardar Articulo(s)</button>
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
    <h2>Agregar Revista</h2>
    <form id="form-editorial">
      <label for="nuevaRevista">Nombre de la Editorial:</label>
      <input type="text" id="nuevaRevista" name="nuevaRevista" placeholder="MAYÚSCULAS" required>
      <button type="button" class="extend" onclick="agregarRevista()">Agregar Revista</button>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../Controlador/ControinsertRevista.js"></script>
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


    if (autor_articulo.trim() === "" || titulo_articulo.trim() === "") {
        alert("Todos los campos son obligatorios");
        return;
    }

    // Guardar en array temporal
    articulosArray.push({ autor_articulo, titulo_articulo });

    // Agregar a la lista visual
    let lista = document.getElementById("listaArticulos");
    let item = document.createElement("li");
    item.textContent = `Autor: ${autorSelect.options[autorSelect.selectedIndex].text}, Título: ${titulo_articulo}}`;
    lista.appendChild(item);

    // Limpiar inputs
    autorSelect.value = "";
    document.getElementById("titulo_articulo").value = "";
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
        $(document).ready(function() {
        $('#form-revista').submit(function(event) {
    console.log("Formulario enviado");
    
});
document.querySelectorAll('.autor-articulo').forEach(select => {
    select.addEventListener('change', function() {
        console.log("Seleccionado:", this.value);
    });
});
});
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
