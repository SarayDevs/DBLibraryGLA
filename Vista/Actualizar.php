
<?php
require_once '../Modelo/Datoslibros.php'; 
require_once '../Modelo/Datosautor.php'; 
require_once '../Modelo/Datosestado.php';
require_once '../Modelo/Datosorigen.php';
require_once '../Modelo/Datoseditorial.php';
require_once '../Modelo/Datosmedio.php';
require_once '../Modelo/Datosclase.php';
require_once '../Modelo/Datosarea.php';
require_once '../Modelo/Datosindice.php';
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
$indiceModelo= new misIndices();

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

$indice= $indiceModelo->verIndiceID($id);
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
    <link rel="stylesheet" href="../Libreria/actualizar.css">
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
<h1>Actualizar Libro</h1>

<h2> Libro <?php echo $id ?> </h2>

<form method="POST" id="form-actualizar-libro" class="actualizar-libro" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($libro[0]['ID'], ENT_QUOTES, 'UTF-8'); ?>">

    <table class="table table-bordered">
 

        <tr>
            <th><label for="IDLIB">Código:</label></th>
            <td><input type="text" name="IDLIB" id="IDLIB" value="<?php echo htmlspecialchars($libro[0]['IDLIB'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
        </tr>


        <tr>
            <th><label for="codigo">Signatura:</label></th>
            <td><input type="text" name="Codigo" id="Codigo" value="<?php echo htmlspecialchars($libro[0]['Codigo'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
        </tr>

        <tr>
            <th><label for="Autor">Autor:</label></th>
            <td>
                <select id="autorID" name="autorID"  required >
                    <?php foreach ($autores as $autor) : ?>
                        <option value="<?php echo htmlspecialchars($autor['idautor'], ENT_QUOTES, 'UTF-8'); ?>" 
                            data-nombre="<?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>" 
                            <?php echo ($libro[0]['autorID'] == $autor['idautor']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input type="hidden" id="autor" name="autor">
                <button type="button" onclick="openModal('autorModal')">➕ Agregar Autor</button>

            </td>
        </tr>
        <tr>
            <th><label for="Autor3">Autor 2:</label></th>
            <td>
                <select id="autorID3" name="autorID3"  required >
                    <?php foreach ($autores as $autor) : ?>
                        <option value="<?php echo htmlspecialchars($autor['idautor'], ENT_QUOTES, 'UTF-8'); ?>" 
                            data-nombre="<?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>" 
                            <?php echo ($libro[0]['autorID3'] == $autor['idautor']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" id="autor3" name="autor3">
                <button type="button" onclick="openModal('autorModal')">➕ Agregar Autor</button>


            </td>
        </tr>
        <tr>
            <th><label for="Autor4">Autor 3:</label></th>
            <td>
                <select id="autorID4" name="autorID4"  required >
                    <?php foreach ($autores as $autor) : ?>
                        <option value="<?php echo htmlspecialchars($autor['idautor'], ENT_QUOTES, 'UTF-8'); ?>" 
                            data-nombre="<?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>" 
                            <?php echo ($libro[0]['autorID4'] == $autor['idautor']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" id="autor4" name="autor4">
                <button type="button" onclick="openModal('autorModal')">➕ Agregar Autor</button>


            </td>
        </tr>
        <tr>
            <th><label for="Autor5">Autor 4:</label></th>
            <td>
                <select id="autorID5" name="autorID5"  required >
                    <?php foreach ($autores as $autor) : ?>
                        <option value="<?php echo htmlspecialchars($autor['idautor'], ENT_QUOTES, 'UTF-8'); ?>" 
                            data-nombre="<?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>" 
                            <?php echo ($libro[0]['autorID5'] == $autor['idautor']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" id="autor5" name="autor5">
                <button type="button" onclick="openModal('autorModal')">➕ Agregar Autor</button>


            </td>
        </tr>

        <tr>
            <th><label for="Autor2">Autor corporativo:</label></th>
            <td>
                <select id="autorID2" name="autorID2"  required >
                    <?php foreach ($autores as $autor) : ?>
                        <option value="<?php echo htmlspecialchars($autor['idautor'], ENT_QUOTES, 'UTF-8'); ?>" 
                            data-nombre="<?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>" 
                            <?php echo ($libro[0]['autorID2'] == $autor['idautor']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" id="autor2" name="autor2">
                <button type="button" onclick="openModal('autorModal')">➕ Agregar Autor</button>
            </td>
        </tr>


        <tr>
            <th><label for="titulo">Título:</label></th>
            <td><input type="text" name="Titulo" id="Titulo" value="<?php echo htmlspecialchars($libro[0]['Titulo'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
        </tr>

        
        <tr>
            <th><label for="Temas2">Mención de responsabilidad:</label></th>
            <td>
                <input type="text" name="Temas2" id="Temas2" value="<?php echo htmlspecialchars($libro[0]['Temas2'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </td>
        </tr>

        <tr>
            <th><label for="ISBN">ISBN:</label></th>
            <td><input type="number" name="ISBN" id="ISBN" value="<?php echo htmlspecialchars($libro[0]['ISBN'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
        </tr>


        <tr>
            <th><label for="Existe">¿Existe Fisicamente?</label></th>
            <td>
                <select id="Existe" name="Existe">
                    <option value="SI" <?php echo ($libro[0]['Existe'] == 'SI') ? 'selected' : ''; ?>>SÍ</option>
                    <option value="NO" <?php echo ($libro[0]['Existe'] == 'NO') ? 'selected' : ''; ?>>NO</option>
                </select>
            </td>
        </tr>

        <tr>
            <th><label for="Entrainv">¿Entra en inventario?:</label></th>
            <td>
                <select id="Entrainv" name="Entrainv">
                    <option value="SI" <?php echo ($libro[0]['Entrainv'] == 'SI') ? 'selected' : ''; ?>>SÍ</option>
                    <option value="NO" <?php echo ($libro[0]['Entrainv'] == 'NO') ? 'selected' : ''; ?>>NO</option>
                </select>
            </td>
        </tr>
        <tr>
        <th> <label for="copia">Ejemplar Nº:</label></th>
     <td><input type="text" id="copia" name="copia" value="<?php echo htmlspecialchars($libro[0]['copia'], ENT_QUOTES, 'UTF-8'); ?>"></td>
           </tr>
</tr>
        <tr>
            <th><label for="edicion">Edición:</label></th>
            <td><input type="text" name="Edicion" id="Edicion" value="<?php echo htmlspecialchars($libro[0]['Edicion'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
        </tr>

        <tr>
            <th><label for="Ciudad">Ciudad:</label></th>
            <td><input type="text" name="Ciudad" id="Ciudad" value="<?php echo htmlspecialchars($libro[0]['Ciudad'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
        </tr>

        <tr>
            <th><label for="editorial">Editorial:</label></th>
            <td>
                <select id="Editorial" name="Editorial"  required >
                    <?php foreach ($editoriales as $editorial) : ?>
                        <option value="<?php echo htmlspecialchars($editorial['ideditorial'], ENT_QUOTES, 'UTF-8'); ?>" 
                            data-nombre="<?php echo htmlspecialchars($editorial['ideditorial'], ENT_QUOTES, 'UTF-8'); ?>"
                            <?php echo ($libro[0]['Editorial'] == $editorial['ideditorial']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($editorial['editoriales'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" id="TipoEditorial" name="TipoEditorial">
                <button type="button" onclick="openModal('editorialModal')">➕ Agregar Editorial</button>
            </td>
        </tr>
        <tr>
    <th><label for="Caratula">Carátula:</label></th>
    <td>
        <!-- Mostrar la imagen actual -->
        <?php if (!empty($libro[0]['Caratula'])): ?>
            <img src="<?php echo 'http://localhost/MVC/Controlador/' . htmlspecialchars($libro[0]['Caratula'], ENT_QUOTES, 'UTF-8'); ?>" 
                 alt="Carátula del libro" 
                 style="max-width: 150px; max-height: 200px; display: block; margin-bottom: 10px;">
        <?php else: ?>
            <p>No hay carátula disponible.</p>
        <?php endif; ?>
        
        <!-- Input para subir una nueva imagen -->
        <input type="file" name="Caratula" id="Caratula" accept="image/*" >
    </td>
    </tr>
    <tr>
            <th><label for="Resena">Reseña:</label></th>
            <td><input type="text" name="Resena" id="Resena" value="<?php echo htmlspecialchars($libro[0]['Resena'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
        </tr>
        <tr>
        <th><label for="Indice">Índice:</label></th>
<td colspan="1">
    <table border="0" id="tabla-indice">
        <tr>
            <th>Sección</th>
            <th style="text-align: center;">Título</th>
            <th>Página</th>
        </tr>
        <?php if (!empty($indice)): ?>
    <?php foreach ($indice as $indices): ?>
        <tr>
            <input type="hidden" name="idindice[]" value="<?php echo $indices['idindice']; ?>">
            <td><input type="text" style="width: 60px;" name="seccionnum[]" value="<?php echo isset($indices['seccionnum']) ? htmlspecialchars($indices['seccionnum']) : ''; ?>"></td>
            <td><input type="text" style="width: 500px;" name="titulo[]" value="<?php echo isset($indices['titulo']) ? htmlspecialchars($indices['titulo']) : ''; ?>"></td>
            <td><input type="text" style="width: 50px;" name="pagina[]" value="<?php  echo isset($indices['pagina']) ? htmlspecialchars($indices['pagina']) : ''; ?>"></td>
            
            <!-- Imagen existente -->
            <td>
                <?php if (!empty($indices['imagen'])): ?>
                    <img src="<?php echo 'http://localhost/MVC/Controlador/' . $indices['imagen']; ?>" 
                         alt="Índice en imagen" width="100">
                         <input type="checkbox" name="eliminar_imagen[<?php echo $indices['idindice']; ?>]" value="1"> Eliminar imagen
                <?php endif; ?>
                <input type="file" name="imagenIndice[]" accept="image/*">
            </td>

            <!-- Input para subir nueva imagen -->

        </tr>
    <?php endforeach; ?>
<?php endif; ?>
</table>

<!-- Botón para agregar nuevos índices -->
<button type="button" id="agregar-fila">➕ Agregar Índice</button>


            </tr>

        <tr>
            <th><label for="Fimpresion">Año de publicación:</label></th>
            <td>
                <input type="number" name="Fimpresion" id="Fimpresion" value="<?php echo htmlspecialchars($libro[0]['Fimpresion'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </td>
        </tr>

        <tr>
            <th><label for="Colacion">¿Ilustraciones? ¿Que tipo?:</label></th>
            <td><input type="text" name="Colacion" id="Colacion" value="<?php echo htmlspecialchars($libro[0]['Colacion'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
        </tr>

        <tr>
            <th><label for="npag">Número de páginas:</label></th>
            <td>
                <input type="number" name="npag" id="npag" value="<?php echo htmlspecialchars($libro[0]['npag'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </td>
        </tr>
        
        <tr>
            <th><label for="Serie">Serie:</label></th>
            <td><input type="text" name="Serie" id="Serie" value="<?php echo htmlspecialchars($libro[0]['Serie'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
        </tr>

        <tr>
            <th><label for="Dimensiones">Dimensiones:</label></th>
            <td><input type="text" name="Dimensiones" id="Dimensiones" value="<?php echo htmlspecialchars($libro[0]['Dimensiones'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
        </tr>
        <tr>

<th><label for="Nota">Nota:</label></th>
     <td><input type="text" id="Nota" name="Nota" value="<?php echo htmlspecialchars($libro[0]['Nota'], ENT_QUOTES, 'UTF-8'); ?>"></td>
           
</tr>

        <tr>
            <th><label for="Estado">Estado:</label></th>
            <td>
                <select id="Estado" name="Estado"  required >
                    <?php foreach ($estados as $estado) : ?>
                        <option value="<?php echo htmlspecialchars($estado['IdEst'], ENT_QUOTES, 'UTF-8'); ?>" 
                        data-nombre="<?php echo htmlspecialchars($estado['IdEst'], ENT_QUOTES, 'UTF-8'); ?>"
                        <?php echo ($libro[0]['Estado'] == $estado['IdEst']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($estado['Etados'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

            </td>
        </tr>

        <tr>
            <th><label for="fecha">Fecha de adquisición:</label></th>
            <td><input type="date" name="Fecha" id="Fecha" value="<?php echo htmlspecialchars($libro[0]['Fecha'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
        </tr>

        <tr>
            <th><label for="costo">Costo:</label></th>
            <td><input type="number" name="Costo" id="Costo" value="<?php echo htmlspecialchars($libro[0]['Costo'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
        </tr>

        <tr>
    <th><label for="origen">Vía de ingreso:</label></th>
    <td>
        <select id="Origen" name="Origen"  required >
            <?php foreach ($origenes as $origen) : ?>
                <option value="<?php echo htmlspecialchars($origen['idorigen'], ENT_QUOTES, 'UTF-8'); ?>" 
                data-nombre="<?php echo htmlspecialchars($origen['idorigen'], ENT_QUOTES, 'UTF-8'); ?>"
                <?php echo ($libro[0]['Origen'] == $origen['idorigen']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($origen['origenes'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>
       
    </td>
</tr>


<tr>
    <th><label for="Area">Ubicación local:</label></th>
    <td>
        <select id="Area" name="Area"  required >
            <?php foreach ($areas as $area) : ?>
                <option value="<?php echo htmlspecialchars($area['idarea'], ENT_QUOTES, 'UTF-8'); ?>" 
                data-nombre="<?php echo htmlspecialchars($area['idarea'], ENT_QUOTES, 'UTF-8'); ?>"
                <?php echo ($libro[0]['Area'] == $area['idarea']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($area['areas'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" id="TipoArea" name="TipoArea">
                    <button type="button" onclick="openModal('UbicacionModal')">➕ Agregar Ubicación</button>
    </td>
</tr>

<tr>
    <th><label for="Medio">Medio:</label></th>
    <td>
        <select id="Medio" name="Medio"  required >
            <?php foreach ($medios as $medio) : ?>
                <option value="<?php echo htmlspecialchars($medio['id'], ENT_QUOTES, 'UTF-8'); ?>" 
                data-nombre="<?php echo htmlspecialchars($medio['id'], ENT_QUOTES, 'UTF-8'); ?>"
                <?php echo ($libro[0]['Medio'] == $medio['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($medio['tipo'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>
        
    </td>
</tr>

<tr>
    <th><label for="Clase">Soporte:</label></th>
    <td>
        <select id="Clase" name="Clase"  required >
            <?php foreach ($clases as $clase) : ?>
                <option value="<?php echo htmlspecialchars($clase['idclase'], ENT_QUOTES, 'UTF-8'); ?>" 
                data-nombre="<?php echo htmlspecialchars($clase['idclase'], ENT_QUOTES, 'UTF-8'); ?>"
                <?php echo ($libro[0]['Clase'] == $clase['idclase']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($clase['clases'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>
        
    </td>
</tr>
       


<tr>
    <th><label for="Seccion">Colección:</label></th>
    <td>
        <select id="Seccion" name="Seccion"  required >
            <?php foreach ($secciones as $seccion) : ?>
                <option value="<?php echo htmlspecialchars($seccion['idseccion'], ENT_QUOTES, 'UTF-8'); ?>" 
                data-nombre="<?php echo htmlspecialchars($seccion['idseccion'], ENT_QUOTES, 'UTF-8'); ?>"
                <?php echo ($libro[0]['Seccion'] == $seccion['idseccion']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($seccion['secciones'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>
       
    </td>
</tr>

        <tr>
            <th><label for="Temas">Descriptores:</label></th>
            <td>
                <input type="text" name="Temas" id="Temas" value="<?php echo htmlspecialchars($libro[0]['Temas'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </td>
        </tr>

        <tr>
            <th><label for="AsientosSecundarios">Asientos secundarios:</label></th>
            <td>
                <input type="text" name="AsientosSecundarios" id="AsientosSecundarios" value="<?php echo htmlspecialchars($libro[0]['AsientosSecundarios'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </td>
        </tr>

        <tr>
            <th><label for="Observacion">Observación:</label></th>
            <td><input type="text" name="Observacion" id="Observacion" value="<?php echo htmlspecialchars($libro[0]['Observacion'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
        </tr>

        



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

<!-- Modal para agregar Autor 2 -->
<div id="autor2Modal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('autor2Modal')">&times;</span>
    <h2>Agregar Autor Corporativo</h2>
    <form id="form-autor2">
  
      <label for="nuevoAutor2">Nombre del Autor:</label>
      <input type="text" class="extend" id="nuevoAutor2" name="nuevoAutor2" placeholder="MAYÚSCULAS" required>
      <button type="button" onclick="agregarAutor2()">Agregar Autor</button>
    </form>
  </div>
</div>

<div id="UbicacionModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('UbicacionModal')">&times;</span>
    <h2>Agregar ubicación en las estanterias</h2>
    <form id="form-ubicacion">
  
      <label for="nuevaubicacion">Nombre de la Ubicación:</label>
      <input type="text" class="extend" id="nuevaubicacion" name="nuevaubicacion" placeholder="MAYÚSCULAS" required>
      <button type="button" onclick="agregarUbicacion()">Agregar Ubicación</button>
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

            </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="../Controlador/Controactualizar.js"></script>
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
        <input type="hidden" name="idindice[]" value="">
        <td><input type="text" style="width: 60px;" name="seccionnum[]" ></td>
        <td><input type="text" style="width: 500px;" name="titulo[]" ></td>
        <td><input type="text" style="width: 50px;" name="pagina[]" ></td>
        <td>
            <input type="file" name="imagenIndice[]" accept="image/*">
        </td>
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

