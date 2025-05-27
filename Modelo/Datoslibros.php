<?php
require_once 'Conexion.php';

class misLibros {
    private $lastError;
   
    function verTodosLosLibros() {
        $conexion = new Conexion();
        $arreglo = array();

        $consulta = "SELECT 
    l.ID, 
    l.IDLIB, 
    l.Codigo, 
    l.Titulo, 
    a.autores AS Autor, 
    l.Edicion, 
    l.Costo, 
    l.Fecha, 
    e.Etados AS Estado, 
    o.origenes AS Origen,  
    ed.editoriales AS Editorial,  
    l.Area,  
    m.tipo AS Medio,  
    c.clases AS Clase,  
    l.Observacion, 
    s.secciones AS Seccion,  
    l.Temas,  
    act.disponibilidad AS Actividad,  
    l.ISBN,  
    l.Ciudad,  
    l.Colacion,  
    l.Serie,  
    l.Dimensiones,  
    l.Nota,  
    l.copia,
    l.Resena
FROM libros l
LEFT JOIN autor a ON l.autorID = a.idautor
LEFT JOIN estado e ON l.Estado = e.IdEst
LEFT JOIN origen o ON l.Origen = o.idorigen
LEFT JOIN editorial ed ON l.Editorial = ed.ideditorial
LEFT JOIN medio m ON l.Medio = m.id
LEFT JOIN clase c ON l.Clase = c.idclase
LEFT JOIN seccion s ON l.Seccion = s.idseccion
LEFT JOIN posibles act ON l.Actividad = act.IDposibles;

         ";
        $modulos = $conexion->prepare($consulta);
        $modulos->execute();
        $total = $modulos->rowCount();
        if ($total > 0) {
            $a = 0;
            while($data = $modulos->fetch(PDO::FETCH_ASSOC)) {
                $arreglo[$a] = $data;
                $a++;
            }
        }
        return $arreglo;
    }



    function verLibroID($id = null) {
        $conexion = new Conexion();
        $arreglo = array();
        if ($id) {
            $consulta = "SELECT * FROM libros WHERE ID = :id";
            $modules = $conexion->prepare($consulta);
            $modules->bindParam(":id", $id);
        } else {
            $consulta = "SELECT * FROM libros";
            $modules = $conexion->prepare($consulta);
        }
        $modules->execute();
        $total = $modules->rowCount();
        if ($total > 0) {
            $i = 0;
            while($data = $modules->fetch(PDO::FETCH_ASSOC)) {
                $arreglo[$i] = $data;
                $i++;
            }
        } else {
            echo "No hay resultados";
        }
        return $arreglo;
    }

   
    function agregarLibros($IDLIB, $Codigo, $Titulo, $Existe, $autorID,  $Edicion, $Costo, $Fecha, $Estado,  $Origen, 
     $Editorial,  $Area,  $Medio,  $Clase,  $Observacion, $Seccion, 
     $Temas, $npag, $Fimpresion, $Temas2, $Entrainv, $actividad, $autorID2, 
	$ISBN, $Ciudad, $Colacion, $Serie, $Dimensiones, $AsientosSecundarios, $Caratula, $Nota, $copia, $autorID3, $autorID4, $Resena, $autorID5 ) {

        try {
            $conexion = new Conexion();
            $consulta = "INSERT INTO libros (IDLIB, Codigo, Titulo, Existe, autorID,  Edicion, Costo, Fecha, Estado,  Origen, 
             Editorial,  Area,  Medio,  Clase,  Observacion, Seccion,  Temas, 
           npag, Fimpresion, Temas2, Entrainv, Actividad, autorID2, ISBN, Ciudad, Colacion, Serie, Dimensiones, 
            AsientosSecundarios, Caratula, Nota, copia, autorID3, autorID4, Resena, autorID5) 

                         VALUES (:IDLIB, :Codigo, :Titulo, :Existe, :autorID,  :Edicion, :Costo, :Fecha, :Estado,  :Origen, 
                          :Editorial,  :Area,  :Medio,  :Clase,  :Observacion, :Seccion, 
                          :Temas,  :npag, :Fimpresion, :Temas2, :Entrainv, :actividad, :autorID2,  :ISBN, :Ciudad, 
                         :Colacion, :Serie, :Dimensiones, :AsientosSecundarios, :Caratula, :Nota, :copia, :autorID3, :autorID4, :Resena, :autorID5)";
    
            $stmt = $conexion->prepare($consulta);
    

            $stmt->bindParam(':IDLIB', $IDLIB);
            $stmt->bindParam(':Codigo', $Codigo);
            $stmt->bindParam(':Titulo', $Titulo);
            $stmt->bindParam(':Existe', $Existe);
            $stmt->bindParam(':autorID', $autorID);
            $stmt->bindParam(':Edicion', $Edicion);
            $stmt->bindParam(':Costo', $Costo);
            $stmt->bindParam(':Fecha', $Fecha);
            $stmt->bindParam(':Estado', $Estado);
            $stmt->bindParam(':Origen', $Origen);
            $stmt->bindParam(':Editorial', $Editorial);
            $stmt->bindParam(':Area', $Area);
            $stmt->bindParam(':Medio', $Medio);
            $stmt->bindParam(':Clase', $Clase);
            $stmt->bindParam(':Observacion', $Observacion);
            $stmt->bindParam(':Seccion', $Seccion);
            $stmt->bindParam(':Temas', $Temas);
            $stmt->bindParam(':npag', $npag);
            $stmt->bindParam(':Fimpresion', $Fimpresion);
            $stmt->bindParam(':Temas2', $Temas2);
            $stmt->bindParam(':Entrainv', $Entrainv);
            $stmt->bindParam(':actividad', $actividad);
            $stmt->bindParam(':autorID2', $autorID2);
            $stmt->bindParam(':ISBN', $ISBN);
            $stmt->bindParam(':Ciudad', $Ciudad);
            $stmt->bindParam(':Colacion', $Colacion);
            $stmt->bindParam(':Serie', $Serie);
            $stmt->bindParam(':Dimensiones', $Dimensiones);
            $stmt->bindParam(':AsientosSecundarios', $AsientosSecundarios);
            $stmt->bindParam(':Caratula', $Caratula);
            $stmt->bindParam(':Nota', $Nota);
            $stmt->bindParam(':copia', $copia);
            $stmt->bindParam(':autorID3', $autorID3);
            $stmt->bindParam(':autorID4', $autorID4);
            $stmt->bindParam(':Resena', $Resena);
            $stmt->bindParam(':autorID5', $autorID5);

            if ($autorID2 === null) {
                $stmt->bindValue(':autorID2', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':autorID2', $autorID2, PDO::PARAM_INT);
            }
            if ($autorID3 === null) {
                $stmt->bindValue(':autorID3', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':autorID3', $autorID3, PDO::PARAM_INT);
            }
            if ($autorID4 === null) {
                $stmt->bindValue(':autorID4', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':autorID4', $autorID4, PDO::PARAM_INT);
            }
            if ($autorID5 === null) {
                $stmt->bindValue(':autorID5', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':autorID5', $autorID5, PDO::PARAM_INT);
            }
            
            
            if (!$stmt->execute()) {
                
                $errorInfo = $stmt->errorInfo();
                error_log("Error en la consulta SQL: " . $errorInfo[2]); 
                $this->lastError = $errorInfo[2]; 
                return false;
            }
    
            return $conexion->lastInsertId(); 
    
        } catch (Exception $e) {
           
            error_log("Excepción capturada: " . $e->getMessage());
            $this->lastError = $e->getMessage(); 
            return false;
        }
    }
    public function getLastError() {
        return $this->lastError;
    }

    function actualizarLibro($id, $IDLIB, $Codigo, $Titulo, $Existe, $autorID,  $Edicion, $Costo, $Fecha, $Estado, 
    $Origen,$Editorial, $Area, $Medio, $Clase,  
    $Observacion, $Seccion,  $Temas,  $npag, $Fimpresion, $Temas2, $Entrainv, $autorID2, 
	$ISBN, $Ciudad, $Colacion, $Serie, $Dimensiones, $AsientosSecundarios, $Caratula, $Nota, $copia, $autorID3, $autorID4, $Resena, $autorID5) {
    
    try {
 
        $conexion = new Conexion();
        

        $consulta = "UPDATE libros SET IDLIB = :IDLIB, Codigo = :Codigo, Titulo = :Titulo, Existe = :Existe, 
                     autorID = :autorID,  Edicion = :Edicion, Costo = :Costo, Fecha = :Fecha, 
                     Estado = :Estado,  Origen = :Origen,  
                     Editorial = :Editorial,  Area = :Area, 
                     Medio = :Medio,  Clase = :Clase, 
                     Observacion = :Observacion, Seccion = :Seccion,  Temas = :Temas,
                     npag = :npag, Fimpresion = :Fimpresion, Temas2 = :Temas2, Entrainv = :Entrainv, autorID2 = :autorID2, 
                     ISBN = :ISBN, Ciudad = :Ciudad, Colacion = :Colacion, Serie = :Serie,
                     Dimensiones = :Dimensiones, AsientosSecundarios = :AsientosSecundarios, Caratula = :Caratula, Nota = :Nota, copia = :copia,
                     autorID3= :autorID3, autorID4= :autorID4, Resena= :Resena, autorID5 = :autorID5
                     WHERE ID = :ID";

        $stmt = $conexion->prepare($consulta);

   
        $stmt->bindParam(':ID', $id);
        $stmt->bindParam(':IDLIB', $IDLIB);
        $stmt->bindParam(':Codigo', $Codigo);
        $stmt->bindParam(':Titulo', $Titulo);
        $stmt->bindParam(':Existe', $Existe);
        $stmt->bindParam(':autorID', $autorID);
        $stmt->bindParam(':Edicion', $Edicion);
        $stmt->bindParam(':Costo', $Costo);
        $stmt->bindParam(':Fecha', $Fecha);
        $stmt->bindParam(':Estado', $Estado);
        $stmt->bindParam(':Origen', $Origen);
        $stmt->bindParam(':Editorial', $Editorial);
        $stmt->bindParam(':Area', $Area);
        $stmt->bindParam(':Medio', $Medio);
        $stmt->bindParam(':Clase', $Clase);
        $stmt->bindParam(':Observacion', $Observacion);
        $stmt->bindParam(':Seccion', $Seccion);
        $stmt->bindParam(':Temas', $Temas);
        $stmt->bindParam(':npag', $npag);
        $stmt->bindParam(':Fimpresion', $Fimpresion);
        $stmt->bindParam(':Temas2', $Temas2);
        $stmt->bindParam(':Entrainv', $Entrainv);
        $stmt->bindParam(':autorID2', $autorID2);
        $stmt->bindParam(':ISBN', $ISBN);
        $stmt->bindParam(':Ciudad', $Ciudad);
        $stmt->bindParam(':Colacion', $Colacion);
        $stmt->bindParam(':Serie', $Serie);
        $stmt->bindParam(':Dimensiones', $Dimensiones);
        $stmt->bindParam(':AsientosSecundarios', $AsientosSecundarios);
        $stmt->bindParam(':Caratula', $Caratula);
        $stmt->bindParam(':Nota', $Nota);
        $stmt->bindParam(':copia', $copia);
        $stmt->bindParam(':autorID3', $autorID3);
            $stmt->bindParam(':autorID4', $autorID4);
            $stmt->bindParam(':Resena', $Resena);
            $stmt->bindParam(':autorID5', $autorID5);


        if ($autorID2 === null) {
            $stmt->bindValue(':autorID2', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':autorID2', $autorID2, PDO::PARAM_INT);
        }
        if ($autorID3 === null) {
            $stmt->bindValue(':autorID3', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':autorID3', $autorID3, PDO::PARAM_INT);
        }
        if ($autorID4 === null) {
            $stmt->bindValue(':autorID4', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':autorID4', $autorID4, PDO::PARAM_INT);
        }
        if ($autorID5 === null) {
            $stmt->bindValue(':autorID5', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':autorID5', $autorID5, PDO::PARAM_INT);
        }
        
        if (!$stmt->execute()) {
          
            $this->lastError = $stmt->errorInfo()[2]; 
            return false; 
        }

        return true; 

    } catch (PDOException $e) {
        
        $this->lastError = $e->getMessage(); 
        return false; 
    }
}
function eliminarLibro($id) {
    try {
        // Crear una instancia de la conexión
        $conexion = new Conexion();

        // Iniciar una transacción
        $conexion->beginTransaction();

        // Verificar si el libro existe antes de eliminarlo
        $verificar = $conexion->prepare("SELECT COUNT(*) FROM libros WHERE ID = :id");
        $verificar->bindParam(':id', $id, PDO::PARAM_INT);
        $verificar->execute();

        if ($verificar->fetchColumn() == 0) {
            // Si el libro no existe, lanza un error específico
            throw new Exception("El libro con ID $id no existe en la base de datos.");
        }

        // Eliminar el libro con el ID proporcionado
        $consulta = "DELETE FROM libros WHERE ID = :id";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            // Si la eliminación falla, lanza una excepción
            throw new Exception("No se pudo eliminar el libro con ID: $id");
        }

        // Reasignar los IDs en orden secuencial
        $reasignarConsulta = "
            SET @new_id = 0;
            UPDATE libros
            SET ID = (@new_id := @new_id + 1)
            ORDER BY ID;
        ";
        $conexion->exec($reasignarConsulta);

        // Reiniciar el AUTO_INCREMENT
        $reiniciarAutoIncrement = "ALTER TABLE libros AUTO_INCREMENT = 1;";
        $conexion->exec($reiniciarAutoIncrement);

        // Confirmar los cambios
        $conexion->commit();

        return true;

    } catch (Exception $e) {
        // Si ocurrió un error real, se registra y devuelve un mensaje.
        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }
        error_log("Error en eliminarLibro: " . $e->getMessage());
        return false;
    }
}

    function obtenerTituloLibro($id) {
    $conexion = new Conexion();
    $consulta = "SELECT titulo FROM libros WHERE ID = :id";
    $modules = $conexion->prepare($consulta);
    $modules->bindParam(":id", $id, PDO::PARAM_INT);
    $modules->execute();
    $resultado = $modules->fetch(PDO::FETCH_ASSOC);

    // Retorna el título si existe, o un mensaje de error
    return $resultado ? $resultado['titulo'] : "Libro no encontrado";
}
function actualizarLibroPrestamo($id, $SiPrest ) {
try {
    $conexion = new Conexion();
    $consulta = "UPDATE libros SET SiPrest = :SiPrest WHERE ID = :id";

    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':SiPrest', $SiPrest);
   
    if (!$stmt->execute()) {
      
        $this->lastError = $stmt->errorInfo()[2]; 
        return false; 
    }

    return true; 

} catch (PDOException $e) {
    
    $this->lastError = $e->getMessage(); 
    return false; 
}
}
function actualizarActividad($ID, $Actividad){
    try {
     
        $conexion = new Conexion();
        

        $consulta = "UPDATE libros SET Actividad = :Actividad
                    WHERE ID = :ID";

        $stmt = $conexion->prepare($consulta);

        $stmt->bindParam(':ID', $ID);
        $stmt->bindParam(':Actividad', $Actividad);
     
        if (!$stmt->execute()) {
          
            $this->lastError = $stmt->errorInfo()[2]; 
            return false; 
        }

        return true; 

    } catch (PDOException $e) {
        
        $this->lastError = $e->getMessage(); 
        return false; 
    }
}
public function verLibros1($limite, $offset, $busqueda = '', $filtro = null) {
    $conexion = new Conexion();

    // Iniciamos la consulta base
    $sql = "SELECT DISTINCT libros.*, 
                   autor.autores AS autorNombre, 
                   editorial.editoriales AS editorialNombre";

    // Si hay una búsqueda, añadimos la columna coincidencia_indice
    if (!empty($busqueda)) {
        $sql .= ", CASE 
                      WHEN indice.titulo LIKE :busqueda THEN 1 
                      ELSE 0 
                  END AS coincidencia_indice";
    }

    // Continuamos con los joins y el where
    $sql .= " FROM libros
              LEFT JOIN autor ON libros.autorID = idautor
              LEFT JOIN editorial ON libros.Editorial = ideditorial
              LEFT JOIN indice ON libros.ID = indice.idlibro
              WHERE 1=1";

    $params = [];

    // Agregamos la condición de búsqueda si no está vacía
    if (!empty($busqueda)) {
        $sql .= " AND (
            libros.ID LIKE :busqueda OR
            libros.ISBN LIKE :busqueda OR
            libros.IDLIB LIKE :busqueda OR
            libros.Temas LIKE :busqueda OR
            libros.Codigo LIKE :busqueda OR
            libros.Titulo LIKE :busqueda OR
            autor.autores LIKE :busqueda OR
            editorial.editoriales LIKE :busqueda OR
            indice.titulo LIKE :busqueda
        )";
        $params[':busqueda'] = '%' . $busqueda . '%';
    }

    if (!is_null($filtro)) {
        $sql .= " AND Actividad = :filtro";
        $params[':filtro'] = $filtro;
    }

    // Agregamos paginación
    $sql .= " LIMIT :offset, :limite";
    $params[':offset'] = $offset;
    $params[':limite'] = $limite;

    // Preparamos la consulta
    $stmt = $conexion->prepare($sql);

    // Solo bindeamos los valores si existen
    if (!empty($busqueda)) {
        $stmt->bindValue(':busqueda', '%' . $busqueda . '%', PDO::PARAM_STR);
    }

    if (!is_null($filtro)) {
        $stmt->bindValue(':filtro', $filtro, PDO::PARAM_STR);
    }

    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);

    // Ejecutamos y retornamos los resultados
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/*public function verLibros1($limite, $offset, $busqueda = '', $filtro = null) {
    $conexion = new Conexion();

   
    $sql = "SELECT libros.*, autor.autores as autorNombre, editorial.editoriales as editorialNombre 
            FROM libros 
            LEFT JOIN autor ON libros.autorID = idautor 
            LEFT JOIN editorial ON libros.Editorial = ideditorial
            LEFT JOIN indice ON libros.ID= indice.idlibro 
            WHERE 1=1";

    $params = [];

    
    if (!empty($busqueda)) {
        $sql .= " AND (
            libros.ID LIKE :busqueda OR
            libros.IDLIB LIKE :busqueda OR
            libros.Codigo LIKE :busqueda OR
            libros.Titulo LIKE :busqueda OR
            autor.autores LIKE :busqueda OR
           editorial.editoriales LIKE :busqueda OR
            indice.titulo LIKE :busqueda
        )";
        $params[':busqueda'] = '%' . $busqueda . '%';
    }

  
    if (!is_null($filtro)) {
        $sql .= " AND Actividad = :filtro";
        $params[':filtro'] = $filtro;
    }


    $sql .= " LIMIT :offset, :limite";
    $params[':offset'] = $offset;
    $params[':limite'] = $limite;


    $stmt = $conexion->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}*/


public function contarLibros1($busqueda = '', $filtro = null) {
    $conexion = new Conexion();


    $query = "SELECT COUNT(*) as total FROM libros 
              LEFT JOIN autor ON libros.autorID = idautor 
              LEFT JOIN editorial ON libros.Editorial = ideditorial 
              WHERE 1=1";

    $params = [];

    if (!empty($busqueda)) {
        $query .= " AND (
            libros.ID LIKE :busqueda OR
            libros.ISBN LIKE :busqueda OR
            libros.IDLIB LIKE :busqueda OR
            libros.Temas LIKE :busqueda OR
            libros.Codigo LIKE :busqueda OR
            libros.Titulo LIKE :busqueda OR
            autor.autores LIKE :busqueda OR
            editorial.editoriales LIKE :busqueda
        )";
        $params[':busqueda'] = '%' . $busqueda . '%';
    }


    if (!is_null($filtro)) {
        $query .= " AND Actividad = :filtro";
        $params[':filtro'] = $filtro;
    }

    $stmt = $conexion->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}
function obtenerCaratulaActual($id) {
    $conexion = new Conexion();
    $consulta = "SELECT Caratula FROM libros WHERE ID = :ID";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':ID', $id);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultado ? $resultado['Caratula'] : null;
}
public function verLibros2($limite, $offset, $busqueda = '', $signatura_min = 0, $signatura_max = 999, $filtro = null, $filtro_bilingue = false ) {
    $conexion = new Conexion();

    // Iniciamos la consulta base
    $sql = "SELECT DISTINCT libros.*, 
                   autor.autores AS autorNombre, 
                   editorial.editoriales AS editorialNombre";

    // Si hay una búsqueda, añadimos la columna coincidencia_indice
    if (!empty($busqueda)) {
        $sql .= ", CASE 
                      WHEN indice.titulo LIKE :busqueda THEN 1 
                      ELSE 0 
                  END AS coincidencia_indice";
    }


    // Continuamos con los joins y el where
    $sql .= " FROM libros
              LEFT JOIN autor ON libros.autorID = idautor
              LEFT JOIN editorial ON libros.Editorial = ideditorial
              LEFT JOIN indice ON libros.ID = indice.idlibro
              WHERE 1=1";

    $params = [];

    // Agregamos la condición de búsqueda si no está vacía
    if (!empty($busqueda)) {
        $sql .= " AND (
            libros.ID LIKE :busqueda OR
            libros.ISBN LIKE :busqueda OR
            libros.IDLIB LIKE :busqueda OR
            libros.Temas LIKE :busqueda OR
            libros.Codigo LIKE :busqueda OR
            libros.Titulo LIKE :busqueda OR
            autor.autores LIKE :busqueda OR
            editorial.editoriales LIKE :busqueda OR
            indice.titulo LIKE :busqueda
        )";
        $params[':busqueda'] = '%' . $busqueda . '%';
    }

    if (!is_null($filtro)) {
        $sql .= " AND Actividad = :filtro";
        $params[':filtro'] = $filtro;
    }
    if (!$filtro_bilingue) {
        if ($signatura_min != 0 || $signatura_max != 999) {
            $sql .= " AND CAST(REGEXP_SUBSTR(libros.Codigo, '[0-9]+') AS UNSIGNED) BETWEEN :signatura_min AND :signatura_max";
            $params[':signatura_min'] = $signatura_min;
            $params[':signatura_max'] = $signatura_max;
        }}
    if ($filtro_bilingue) {
        $sql .= " AND (libros.Codigo LIKE :bilingue1 OR libros.Codigo LIKE :bilingue2)";

    }
    


    // Agregamos paginación
    $sql .= " LIMIT :offset, :limite";
    $params[':offset'] = $offset;
    $params[':limite'] = $limite;

    // Preparamos la consulta
    $stmt = $conexion->prepare($sql);

    // Solo bindeamos los valores si existen
   // SOLO bindea búsqueda si hay búsqueda
if (!empty($busqueda)) {
    $stmt->bindValue(':busqueda', '%' . $busqueda . '%', PDO::PARAM_STR);
}

// SOLO bindea filtro si existe
if (!is_null($filtro)) {
    $stmt->bindValue(':filtro', $filtro, PDO::PARAM_STR);
}

// SOLO bindea rango numérico si filtro bilingüe está desactivado
if (!$filtro_bilingue && ($signatura_min != 0 || $signatura_max != 999)) {
    $stmt->bindValue(':signatura_min', $signatura_min, PDO::PARAM_INT);
    $stmt->bindValue(':signatura_max', $signatura_max, PDO::PARAM_INT);
}


// SOLO bindea bilingüe si está activo
if ($filtro_bilingue) {
    $stmt->bindValue(':bilingue1', '%LB%', PDO::PARAM_STR);
    $stmt->bindValue(':bilingue2', '%BL%', PDO::PARAM_STR);
}

// Siempre bindea paginación
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limite', $limite, PDO::PARAM_INT);


    // Ejecutamos y retornamos los resultados
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function contarLibros2($busqueda = '', $signatura_min = 0, $signatura_max = 999, $filtro = null, $filtro_bilingue = false) {
    $conexion = new Conexion();

    $query = "SELECT COUNT(DISTINCT libros.ID) as total FROM libros 
              LEFT JOIN autor ON libros.autorID = idautor 
              LEFT JOIN editorial ON libros.Editorial = ideditorial 
              LEFT JOIN indice ON libros.ID = indice.idlibro
              WHERE 1=1";

    $params = [];

    if (!empty($busqueda)) {
        $query .= " AND (
            libros.ID LIKE :busqueda OR
            libros.ISBN LIKE :busqueda OR
            libros.IDLIB LIKE :busqueda OR
            libros.Temas LIKE :busqueda OR
            libros.Codigo LIKE :busqueda OR
            libros.Titulo LIKE :busqueda OR
            autor.autores LIKE :busqueda OR
            editorial.editoriales LIKE :busqueda OR
            indice.titulo LIKE :busqueda
        )";
        $params[':busqueda'] = '%' . $busqueda . '%';
    }

    if (!is_null($filtro)) {
        $query .= " AND Actividad = :filtro";
        $params[':filtro'] = $filtro;
    }

    if (!$filtro_bilingue) {
        if ($signatura_min != 0 || $signatura_max != 999) {
            $query .= " AND CAST(REGEXP_SUBSTR(libros.Codigo, '[0-9]+') AS UNSIGNED) BETWEEN :signatura_min AND :signatura_max";
            $params[':signatura_min'] = $signatura_min;
            $params[':signatura_max'] = $signatura_max;
        }
    }

    if ($filtro_bilingue) {
        $query .= " AND (libros.Codigo LIKE :bilingue1 OR libros.Codigo LIKE :bilingue2)";
    }

    $stmt = $conexion->prepare($query);

    // Bindeo de parámetros
    foreach ($params as $key => $value) {
        if ($key == ':signatura_min' || $key == ':signatura_max') {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
    }

    // Bindeo especial bilingüe
    if ($filtro_bilingue) {
        $stmt->bindValue(':bilingue1', '%LB%', PDO::PARAM_STR);
        $stmt->bindValue(':bilingue2', '%BL%', PDO::PARAM_STR);
    }

    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}


}