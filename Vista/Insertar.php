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
require_once '../Modelo/Datosindice.php';
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
$indiceModelo=new misIndices();
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
<lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Libros</title>

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
            <li><a href="../Vista/Insertar.php" class="active">Insertar Libro</a></li>
            <li><a href="../Vista/Insertrevista.php" >Insertar Revista</a></li>
            <li><a href="../Vista/Insertperiodico.php" >Insertar Periodico</a></li>

            <br>
            <img src="../imagen/logo-blanco.png" alt="Logo de la Biblioteca" class="logo">
        </ul>
    </div>
    <button class="toggle-btn" >☰</button>
<div class="container">
    <h1>Agregar Libro</h1>
    <form method="POST" action="../Controlador/Controinsert.php" id="form-libro" enctype="multipart/form-data">
        <table class="table table-bordered">
            <tr>
                <td><label for="idlib">Código:</label></td>
                <td><input type="text" id="IDLIB" name="IDLIB" placeholder="BEGLA000" required></td>
            </tr>
            <tr>
                <td><label for="code">Signatura:</label></td>
                <td><input type="text" id="Codigo" name="Codigo" placeholder="Números y letras mayúsculas o minúsculas" required></td>
            </tr>
            <tr>
  <td><label for="autor">Autor:</label></td>
  <td>
    <select id="autorID" name="autorID" required >
      <option value="">Seleccionar Autor</option>
      <?php foreach ($autores as $autor) : ?>
        <option value="<?php echo htmlspecialchars($autor['idautor'], ENT_QUOTES, 'UTF-8'); ?>" 
                data-nombre="<?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>">
          <?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>
        </option>
      <?php endforeach; ?>
    </select>
    <input type="hidden" id="autor" name="autor">
    <button type="button" onclick="openModal('autorModal')">➕ Agregar Autor</button>
    <button type="button" onclick="mostrarSegundoAutor()">¿Segundo autor?</button>
  </td>
</tr>
<tr id="segundoAutorRow" style="display: none;">
  <td><label for="autor3">Autor 2:</label></td>
  <td>
    <select id="autorID3" name="autorID3" style="width: 800px;" >
      <option value="">Seleccionar Autor</option>
      <?php foreach ($autores as $autor) : ?>
        <option value="<?php echo htmlspecialchars($autor['idautor'], ENT_QUOTES, 'UTF-8'); ?>" 
                data-nombre="<?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>">
          <?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>
        </option>
      <?php endforeach; ?>
    </select>
    <input type="hidden" id="autor3" name="autor3">
    <button type="button" onclick="openModal('autorModal')">➕ Agregar Autor</button>
    <button type="button" onclick="mostrarTercerAutor()">¿Tercer autor?</button>
  </td>
</tr>
<tr id="tercerAutorRow" style="display: none;">
  <td><label for="autor4">Autor 3:</label></td>
  <td>
    <select id="autorID4" name="autorID4" style="width: 800px;" >
      <option value="">Seleccionar Autor</option>
      <?php foreach ($autores as $autor) : ?>
        <option value="<?php echo htmlspecialchars($autor['idautor'], ENT_QUOTES, 'UTF-8'); ?>" 
                data-nombre="<?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>">
          <?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>
        </option>
      <?php endforeach; ?>
    </select>
    <input type="hidden" id="autor4" name="autor4">
    <button type="button" onclick="openModal('autorModal')">➕ Agregar Autor</button>
    <button type="button" onclick="mostrarCuartoAutor()">¿Cuarto autor?</button>
  </td>
</tr>
<tr id="cuartoAutorRow" style="display: none;">
  <td><label for="autor5">Autor 4:</label></td>
  <td>
    <select id="autorID5" name="autorID5" style="width: 800px;" >
      <option value="">Seleccionar Autor</option>
      <?php foreach ($autores as $autor) : ?>
        <option value="<?php echo htmlspecialchars($autor['idautor'], ENT_QUOTES, 'UTF-8'); ?>" 
                data-nombre="<?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>">
          <?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>
        </option>
      <?php endforeach; ?>
    </select>
    <input type="hidden" id="autor5" name="autor5">
    <button type="button" onclick="openModal('autorModal')">➕ Agregar Autor</button>
  </td>
</tr>

  <td><label for="autor2">Autor Corporativo:</label></td>
  <td>
    <select id="autorID2" name="autorID2" style="width: 800px;" >
      <option value="">Seleccionar Autor</option>
      <?php foreach ($autores as $autor) : ?>
        <option value="<?php echo htmlspecialchars($autor['idautor'], ENT_QUOTES, 'UTF-8'); ?>" 
                data-nombre="<?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>">
          <?php echo htmlspecialchars($autor['autores'], ENT_QUOTES, 'UTF-8'); ?>
        </option>
      <?php endforeach; ?>
    </select>
    <input type="hidden" id="autor2" name="autor2">
    <button type="button" onclick="openModal('autor2Modal')">➕ Agregar Autor</button>
  </td>
</tr>


            <tr>
                <td><label for="titulo">Título:</label></td>
                <td><input type="text" id="Titulo" name="Titulo" placeholder="MAYÚSCULAS" required></td>
            </tr>
            </tr>
            <td><label for="Temas2">Mención de responsailidad:</label></td>
<td><input type="text" id="Temas2" name="Temas2" placeholder="MAYÚSCULAS" required></td>
<tr>
<tr>
                <td><label for="ISBN">ISBN:</label></td>
                <td><input type="text" id="ISBN" name="ISBN" placeholder="Número ISBN" required></td>
            </tr>
            <tr>
                <td><label for="existe">¿Existe Fisicamente?:</label></td>
                <td>
                    <select id="Existe" name="Existe" style="width: 800px;" required>
                        <option value="">Selecciona una opción</option>
                        <option value="SI">SI</option>
                        <option value="NO">NO</option>
                    </select>
                </td>
            </tr>
            <tr>    
<td><label for="entrainv">¿Entra en inventario?:</label></td>
            <td><select id="Entraimv" name="Entrainv" required>
                         <option value="">Selecciona una opción</option> 
                         <option value="SI">SI</option>
                         <option value="NO">NO</option>
                </select></td>
</tr>  
<tr>
    <td><label for="copia">Ejemplar Nº:</label></td>
    <td><input type="text" id="copia" name="copia" placeholder="Ejemplar Nº" required></td>  
</tr>
            
            <tr>
                <td><label for="edicion">Edición:</label></td>
                <td><input type="text" id="Edicion" name="Edicion" placeholder="Número de edición" required></td>
            </tr>
            <tr>
                <td><label for="ciudad">Ciudad:</label></td>
                <td><input type="text" id="Ciudad" name="Ciudad" placeholder="MAYÚSCULAS" required></td>
            </tr>

            <tr>
                <td><label for="editorial">Editorial:</label></td>
                <td>
                    <select id="Editorial" name="Editorial" style="width: 800px;" required >
                        <option value="">Seleccionar Editorial</option>
                        <?php foreach ($editoriales as $editorial) : ?>
                            <option value="<?php echo htmlspecialchars($editorial['ideditorial'], ENT_QUOTES, 'UTF-8'); ?>" data-nombre="<?php echo htmlspecialchars($editorial['editoriales'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($editorial['editoriales'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" id="TipoEditorial" name="TipoEditorial">
                    <button type="button" onclick="openModal('editorialModal')">➕ Agregar Editorial</button>
                </td></tr>
                <tr>
    <td>
    <label for="Caratula">Selecciona una Carátula:</label></td>
    <td> <input type="file" name="Caratula" id="Caratula" accept="image/*" required>

</td>  
</tr>
<tr>          
<td><label for="Resena">Reseña:</label></td>
<td><input type="text" id="Resena" name="Resena" placeholder="MAYÚSCULAS" required></td>         
</tr>
<tr>
    <td><label for="Indice">Índice:</label></td>
    <td>
        <button type="button" onclick="openIndiceModal()">Escribir Índice</button>
        <input type="hidden" id="indiceDatos" name="indiceDatos">
        <br>
        <span id="indiceTexto"></span> <!-- Aquí se mostrará el índice ingresado -->
        <br><br>
        <label for="imagenIndice">O cargar imagen del índice:</label>
        <input type="file" id="imagenIndice" name="imagenIndice[]" accept="image/* "multiple>
    </td>
</tr>


                                     
<td><label for="Fimpresion">Año de publicación:</label></td>
<td><input type="number" id="Fimpresion" name="Fimpresion" placeholder="Año" required></td>
            
</tr>
<tr>          
<td><label for="Colacion">¿Ilustraciones? ¿Que tipo?:</label></td>
<td><input type="text" id="Colacion" name="Colacion" placeholder="MAYÚSCULAS" required></td>         
</tr>

<tr>
<td>
     <label for="npag">Número de páginas:</label></td>
     <td><input type="text" id="npag" name="npag" placeholder="Números de páginas" required></td>
           
</tr>
<tr>
<td>
     <label for="Dimensiones">Dimensiones:</label></td>
     <td><input type="text" id="Dimensiones" name="Dimensiones" placeholder="MAYÚSCULAS" required></td>
</tr>
<tr>
<td>
     <label for="Serie">Serie:</label></td>
     <td><input type="text" id="Serie" name="Serie" placeholder="MAYÚSCULAS" ></td> 
</tr>
<tr>
<td>
     <label for="Nota">Nota:</label></td>
     <td><input type="text" id="Nota" name="Nota" placeholder="Notas" ></td>
           
</tr>
<tr></tr>
            <tr>
                <td><label for="estado">Estado:</label></td>
                <td>
                    <select id="Estado" name="Estado" required >
                        <option value="">Seleccionar Estado</option>
                        <?php foreach ($estados as $estado) : ?>
                            <option value="<?php echo htmlspecialchars($estado['IdEst'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($estado['Etados'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                </td>
            </tr>
            <tr>
                <td><label for="fecha">Fecha de Adquisición:</label></td>
                <td><input type="date" id="Fecha" name="Fecha" required pattern="\d{4}-\d{2}-\d{2}"></td>
            </tr>
            

            <tr>
                <td><label for="costo">Costo:</label></td>
                <td><input type="text" id="Costo" name="Costo" placeholder="Números" required></td>
            </tr>
            <tr>
                <td><label for="origen">Vía de ingreso:</label></td>
                <td>
                    <select id="Origen" name="Origen" required >
                        <option value="">Seleccionar ingreso</option>
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
                <td><label for="area">Ubicación local:</label></td>
                <td>
                    <select id="Area" name="Area" required >
                        <option value="">Seleccionar Ubicación</option>
                        <?php foreach ($areas as $area) : ?>
                            <option value="<?php echo htmlspecialchars($area['idarea'], ENT_QUOTES, 'UTF-8'); ?>" data-nombre="<?php echo htmlspecialchars($area['areas'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($area['areas'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <input type="hidden" id="TipoArea" name="TipoArea">
                    <button type="button" onclick="openModal('UbicacionModal')">➕ Agregar Ubicación</button>
                </td>
            </tr>
            <tr>
                <td><label for="medio">Medio:</label></td>
                <td><select id="Medio" name="Medio" required >
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
        <td><label for="clase">Tipo de soporte:</label></td>
        <td> <select id="Clase" name="Clase" required >
        <option value="">Seleccionar Soporte</option>
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
            
            <td><label for="seccion">Tipo de Colecciones:</label>
            <td>  <select id="Seccion" name="Seccion" required >
                                <option value="">Seleccionar Colección</option>
                                <?php foreach ($secciones as $seccion) : ?>
                                <option value="<?php echo htmlspecialchars($seccion['idseccion'], ENT_QUOTES, 'UTF-8'); ?>" data-nombre="<?php echo htmlspecialchars($seccion['secciones'], ENT_QUOTES, 'UTF-8'); ?>">
                                  <?php echo htmlspecialchars($seccion['secciones'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>

            </td>  
            </tr>   
            <tr>
            
<td><label for="temas">Descriptores:</label></td>
<td><input type="text" id="Temas" name="Temas" placeholder="MAYÚSCULAS" required></td>
</tr>
    <tr>
    <td><label for="AsientosSecundarios">Asientos secundarios:</label></td>
    <td><input type="text" id="AsientosSecundarios" name="AsientosSecundarios" placeholder="MAYÚSCULAS" required></td>  
</tr> 

    <tr>
    <td><label for="observacion">Observaciones:</label></td>
    <td><input type="text" id="Observacion" name="Observacion" placeholder="MAYÚSCULAS" required></td>  
</tr>

            <tr>
                <td colspan="2">
                    <button type="submit">Agregar Libro</button>
                    <button type="button" id="verLibrosBtn">Ver Libros</button>
                </td>
            </tr>
        </table>

    </form>

    <div id="indiceModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeIndiceModal()">&times;</span>
        <h2>Agregar Índice</h2>
        
        <label for="seccionnum">Número de Sección:</label>
        <input type="number" id="seccionnum" name="seccionnum">

        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo">

        <label for="pagina">Número de Página:</label>
        <input type="number" id="pagina" name="pagina">

        <button type="button" onclick="agregarSeccion()">Añadir Sección</button>
<br>
        <h3>Índice Agregado:</h3>
        <ul id="listaIndice"></ul>

        <button type="button" onclick="guardarIndiceTemporal()">Guardar Índice</button>
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

<script src="../Controlador/Controinsert.js"></script>
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

let indiceArray = [];

function openIndiceModal() {
    document.getElementById("indiceModal").style.display = "block";
}

function closeIndiceModal() {
    document.getElementById("indiceModal").style.display = "none";
}

function agregarSeccion() {
    let seccionnum = document.getElementById("seccionnum").value;
    let titulo = document.getElementById("titulo").value;
    let pagina = document.getElementById("pagina").value;

    if (titulo.trim() === "" || pagina.trim() === "") {
        alert("Todos los campos son obligatorios");
        return;
    }

    // Agregar sección al array
    indiceArray.push({ seccionnum, titulo, pagina });

    // Mostrar en la lista
    let lista = document.getElementById("listaIndice");
    let item = document.createElement("li");
    item.textContent = `Sección ${seccionnum}: ${titulo} (Página ${pagina})`;
    lista.appendChild(item);

    // Limpiar campos para nueva entrada
    document.getElementById("seccionnum").value = "";
    document.getElementById("titulo").value = "";
    document.getElementById("pagina").value = "";
}

function guardarIndiceTemporal() {
    if (indiceArray.length === 0) {
        alert("Debe agregar al menos una sección");
        return;
    }

    // Convertir array a JSON y almacenarlo en el input oculto
    document.getElementById("indiceDatos").value = JSON.stringify(indiceArray);

    // Mostrar resumen en la vista principal
    document.getElementById("indiceTexto").innerText = `Índice: ${indiceArray.length} secciones agregadas`;

    closeIndiceModal();
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
