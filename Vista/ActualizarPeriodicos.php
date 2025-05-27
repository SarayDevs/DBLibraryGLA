
<?php
require_once '../Modelo/Datosautor2.php';
require_once '../Modelo/Datosperiodico.php';
require_once '../Modelo/Conexion.php';
require_once '../Modelo/periodico.php';
require_once '../Modelo/DatosPA.php';

include('../App/Inde.php'); 


$PAModelo = new misArticulos();
$periodicoModelo = new misPeriodicos(); 
$autor2Modelo = new misAutores(); 
$nombrePModelo = new miPeriodico(); 


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $periodico = $periodicoModelo->verPeriodicosID($id); 
    if (!$periodico) {
        echo "Periodico no encontrado";
        exit;
    }
} else {
    echo "ID de periodico no especificado.";
    exit;
}

$articulo= $PAModelo->verArticulosPID($id);
$autores2 = $autor2Modelo->verAutores(); 
$per = $nombrePModelo->verPeriodico();


?>  

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Periodico</title>
    <?php include '../Libreria/libreriacss.php'; ?>
    <?php include '../Libreria/libreriajs.php'; ?>
    <link rel="stylesheet" href="../Libreria/Modal.css">
    <link rel="stylesheet" href="../Libreria/actualizar.css">
    <script src="../Controlador/ControActualizarPeriodico.js"></script>
    <script src="../Controlador/ControinsertPeriodico.js"></script>
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
<div class="content">
<div class="container">
<h1>Actualizar Periodico</h1>

<h2> Periodico <?php echo $id ?> </h2>

<form method="POST" id="form-actualizar-periodico" class="actualizar-libro" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($periodico[0]['id'], ENT_QUOTES, 'UTF-8'); ?>">

    <table class="table table-bordered">
 

        <tr>
            <th><label for="nombre">Nombre:</label></th>
            <td>
                <select id="nombre" name="nombre"  required >
                    <?php foreach ($per as $pers) : ?>
                        <option value="<?php echo htmlspecialchars($pers['idperiodico'], ENT_QUOTES, 'UTF-8'); ?>" 
                            data-nombre="<?php echo htmlspecialchars($pers['periodicos'], ENT_QUOTES, 'UTF-8'); ?>" 
                            <?php echo ($periodico[0]['nombre'] == $pers['idperiodico']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($pers['periodicos'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" id="TipoEditorial" name="TipoEditorial">
                <button type="button" onclick="openModal('editorialModal')">➕ Agregar Periodico</button>
            </td>
        </tr>

        <tr>
            <th><label for="proveedor">Proveedor:</label></th>
            <td><input type="text" name="proveedor" id="proveedor" value="<?php echo htmlspecialchars($periodico[0]['proveedor'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
        </tr>
        <tr>
            <th><label for="fecha">Fecha:</label></th>
            <td><input type="date" name="fecha" id="fecha" value="<?php echo htmlspecialchars($periodico[0]['fecha'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
        </tr>
       
    <th><label for="Periodico">Periodico:</label></th>
    <td>
        <!-- Mostrar la imagen actual -->
        <?php if (!empty($periodico[0]['imagen'])): ?>
            <img src="<?php echo 'http://localhost/MVC' . htmlspecialchars($periodico[0]['imagen'], ENT_QUOTES, 'UTF-8'); ?>" 
                 alt="Imagen de revista" 
                 style="max-width: 150px; max-height: 200px; display: block; margin-bottom: 10px;">
        <?php else: ?>
            <p>No hay imagen de periodico disponible.</p>
        <?php endif; ?>
        
        <!-- Input para subir una nueva imagen -->
        <input type="file" name="imagen" id="imagen" accept="image/*" >
    </td>
    </tr>

        <tr>
        <th><label for="articulos">Articulos:</label></th>
<td colspan="1">
    <table border="0" id="tabla-indice">
        <tr>
            <th>Autor</th>
            <th style="text-align: center;">Título</th>
            <th>Pagina</th>
            
        </tr>
        <?php if (!empty($articulo)): ?>
    <?php foreach ($articulo as $articulos): ?>
     
            <input type="hidden" name="idarticulo[]" value="<?php echo $articulos['id']; ?>">
            
            <td>
                <select style="width: 240px;"  name="autor[]"  >
                    <?php foreach ($autores2 as $autor) : ?>
                        <option value="<?php echo htmlspecialchars($autor['id2autor'], ENT_QUOTES, 'UTF-8'); ?>" 
    data-nombre="<?php echo htmlspecialchars($autor['autores2'], ENT_QUOTES, 'UTF-8'); ?>" 
    <?php echo ($articulos['autor'] == $autor['id2autor']) ? 'selected' : ''; ?>>
    <?php echo htmlspecialchars($autor['autores2'], ENT_QUOTES, 'UTF-8'); ?>
</option>
                    <?php endforeach; ?>
                </select>
            
            </td>
        

            <td><input type="text" style="width: 500px;" name="titulo_articulo[]" value="<?php echo isset($articulos['titulo_articulo']) ? htmlspecialchars($articulos['titulo_articulo']) : ''; ?>"></td>
            <td><input type="text" style="width: 60px;" name="pagina[]" value="<?php echo isset($articulos['pagina']) ? htmlspecialchars($articulos['pagina']) : ''; ?>"></td>
            <!-- Imagen existente -->
            <td>
                <?php if (!empty($articulos['imagen'])): ?>
                    <img src="<?php echo 'http://localhost/MVC/Controlador/' . $articulos['imagen']; ?>" 
                         alt="Índice en imagen" width="100">
                         <input type="checkbox" name="eliminar_imagen[<?php echo $articulos['id']; ?>]" value="1"> Eliminar imagen
                <?php endif; ?>
                <input type="file" style="width: 110px;" name="imagenArticulo[]" accept="image/*">
            </td>


        </tr>
    <?php endforeach; ?>
<?php endif; ?>
</table>

<!-- Botón para agregar nuevos índices -->
<button type="button" id="agregar-fila">➕ Agregar Articulo</button>
<button type="button" onclick="openModal('autorModal')">➕ Agregar Autor</button>
<tr>
            <td colspan="2">


    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <button type="submit" data-id="<?php echo $id; ?>" onclick="console.log('Enviado:', )">Actualizar</button>
   


            </td>
        </tr>
    </table>
</form>
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

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('select').select2(); // Aplica select2 a todos los selectores
});

document.getElementById('agregar-fila').addEventListener('click', function() {
    let tabla = document.getElementById('tabla-indice');
    let nuevaFila = document.createElement('tr');
    nuevaFila.innerHTML = `
     <input type="hidden" name="idarticulo[]" value="">
        <tr>
            <td>
                <select name="autor[]" >
                    <option value="">Seleccionar Autor</option>
                    <?php foreach ($autores2 as $autor) : ?>
                        <option value="<?php echo htmlspecialchars($autor['id2autor'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($autor['autores2'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <input type="text" style="width: 500px;" name="titulo_articulo[]" >
            </td>
            <td>
                <input type="text" style="width: 60px;" name="pagina[]" >
            </td>
            <td>
                <input type="file" name="imagenArticulo[]" accept="image/*">
            </td>
        </tr>
    `;

    tabla.appendChild(nuevaFila);

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