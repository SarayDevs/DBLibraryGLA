<?php
require_once 'Conexion.php';

class misLibros {
    private $lastError;
   
    function verTodosLosLibros() {
        $conexion = new Conexion();
        $arreglo = array();

        $consulta = "SELECT * FROM libros";
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

   
    function agregarLibros($IDLIB, $Codigo, $Titulo, $Existe, $autorID, $Autor, $Edicion, $Costo, $Fecha, $Estado, $TipoEstado, $Origen, $TipoOrigen, $Editorial, $TipoEditorial, $Area, $TipoArea, $Medio, $NombreMedio, $Clase, $TipoClase, $Observacion, $Seccion, $TipoSeccion, $Temas, $codinv, $npag, $Fimpresion, $Temas2, $Entrainv, $actividad) {
        try {
            $conexion = new Conexion();
            $consulta = "INSERT INTO libros (IDLIB, Codigo, Titulo, Existe, autorID, Autor, Edicion, Costo, Fecha, Estado, TipoEstado, Origen, TipoOrigen, Editorial, TipoEditorial, Area, TipoArea, Medio, NombreMedio, Clase, TipoClase, Observacion, Seccion, TipoSeccion, Temas, codinv, npag, Fimpresion, Temas2, Entrainv, Actividad) 
                         VALUES (:IDLIB, :Codigo, :Titulo, :Existe, :autorID, :autor, :Edicion, :Costo, :Fecha, :Estado, :TipoEstado, :Origen, :TipoOrigen, :Editorial, :TipoEditorial, :Area, :TipoArea, :Medio, :NombreMedio, :Clase, :TipoClase, :Observacion, :Seccion, :TipoSeccion, :Temas, :codinv, :npag, :Fimpresion, :Temas2, :Entrainv, :actividad)";
    
            $stmt = $conexion->prepare($consulta);
    

            $stmt->bindParam(':IDLIB', $IDLIB);
            $stmt->bindParam(':Codigo', $Codigo);
            $stmt->bindParam(':Titulo', $Titulo);
            $stmt->bindParam(':Existe', $Existe);
            $stmt->bindParam(':autorID', $autorID);
            $stmt->bindParam(':autor', $Autor);
            $stmt->bindParam(':Edicion', $Edicion);
            $stmt->bindParam(':Costo', $Costo);
            $stmt->bindParam(':Fecha', $Fecha);
            $stmt->bindParam(':Estado', $Estado);
            $stmt->bindParam(':TipoEstado', $TipoEstado);
            $stmt->bindParam(':Origen', $Origen);
            $stmt->bindParam(':TipoOrigen', $TipoOrigen);
            $stmt->bindParam(':Editorial', $Editorial);
            $stmt->bindParam(':TipoEditorial', $TipoEditorial);
            $stmt->bindParam(':Area', $Area);
            $stmt->bindParam(':TipoArea', $TipoArea);
            $stmt->bindParam(':Medio', $Medio);
            $stmt->bindParam(':NombreMedio', $NombreMedio);
            $stmt->bindParam(':Clase', $Clase);
            $stmt->bindParam(':TipoClase', $TipoClase);
            $stmt->bindParam(':Observacion', $Observacion);
            $stmt->bindParam(':Seccion', $Seccion);
            $stmt->bindParam(':TipoSeccion', $TipoSeccion);
            $stmt->bindParam(':Temas', $Temas);
            $stmt->bindParam(':codinv', $codinv);
            $stmt->bindParam(':npag', $npag);
            $stmt->bindParam(':Fimpresion', $Fimpresion);
            $stmt->bindParam(':Temas2', $Temas2);
            $stmt->bindParam(':Entrainv', $Entrainv);
            $stmt->bindParam(':actividad', $actividad);
    
            
            if (!$stmt->execute()) {
                
                $errorInfo = $stmt->errorInfo();
                error_log("Error en la consulta SQL: " . $errorInfo[2]); 
                $this->lastError = $errorInfo[2]; 
                return false;
            }
    
            return true; 
    
        } catch (Exception $e) {
           
            error_log("Excepción capturada: " . $e->getMessage());
            $this->lastError = $e->getMessage(); 
            return false;
        }
    }


    public function getLastError() {
        return $this->lastError;
    }

    


    function actualizarLibro($id, $IDLIB, $Codigo, $Titulo, $Existe, $autorID, $Autor, $Edicion, $Costo, $Fecha, $Estado, $TipoEstado, 
    $Origen, $TipoOrigen, $Editorial, $TipoEditorial, $Area, $TipoArea, $Medio, $NombreMedio, $Clase, $TipoClase, 
    $Observacion, $Seccion, $TipoSeccion, $Temas, $codinv, $npag, $Fimpresion, $Temas2, $Entrainv) {
    
    try {
 
        $conexion = new Conexion();
        

        $consulta = "UPDATE libros SET IDLIB = :IDLIB, Codigo = :Codigo, Titulo = :Titulo, Existe = :Existe, 
                     autorID = :autorID, Autor = :autor, Edicion = :Edicion, Costo = :Costo, Fecha = :Fecha, 
                     Estado = :Estado, TipoEstado = :TipoEstado, Origen = :Origen, TipoOrigen = :TipoOrigen, 
                     Editorial = :Editorial, TipoEditorial = :TipoEditorial, Area = :Area, TipoArea = :TipoArea, 
                     Medio = :Medio, NombreMedio = :NombreMedio, Clase = :Clase, TipoClase = :TipoClase, 
                     Observacion = :Observacion, Seccion = :Seccion, TipoSeccion = :TipoSeccion, 
                     Temas = :Temas, codinv = :codinv, npag = :npag, Fimpresion = :Fimpresion, 
                     Temas2 = :Temas2, Entrainv = :Entrainv WHERE ID = :ID";

        $stmt = $conexion->prepare($consulta);

   
        $stmt->bindParam(':ID', $id);
        $stmt->bindParam(':IDLIB', $IDLIB);
        $stmt->bindParam(':Codigo', $Codigo);
        $stmt->bindParam(':Titulo', $Titulo);
        $stmt->bindParam(':Existe', $Existe);
        $stmt->bindParam(':autorID', $autorID);
        $stmt->bindParam(':autor', $Autor);
        $stmt->bindParam(':Edicion', $Edicion);
        $stmt->bindParam(':Costo', $Costo);
        $stmt->bindParam(':Fecha', $Fecha);
        $stmt->bindParam(':Estado', $Estado);
        $stmt->bindParam(':TipoEstado', $TipoEstado);
        $stmt->bindParam(':Origen', $Origen);
        $stmt->bindParam(':TipoOrigen', $TipoOrigen);
        $stmt->bindParam(':Editorial', $Editorial);
        $stmt->bindParam(':TipoEditorial', $TipoEditorial);
        $stmt->bindParam(':Area', $Area);
        $stmt->bindParam(':TipoArea', $TipoArea);
        $stmt->bindParam(':Medio', $Medio);
        $stmt->bindParam(':NombreMedio', $NombreMedio);
        $stmt->bindParam(':Clase', $Clase);
        $stmt->bindParam(':TipoClase', $TipoClase);
        $stmt->bindParam(':Observacion', $Observacion);
        $stmt->bindParam(':Seccion', $Seccion);
        $stmt->bindParam(':TipoSeccion', $TipoSeccion);
        $stmt->bindParam(':Temas', $Temas);
        $stmt->bindParam(':codinv', $codinv);
        $stmt->bindParam(':npag', $npag);
        $stmt->bindParam(':Fimpresion', $Fimpresion);
        $stmt->bindParam(':Temas2', $Temas2);
        $stmt->bindParam(':Entrainv', $Entrainv);

     
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

   
    $sql = "SELECT libros.*, autor.autores as autorNombre, editorial.editoriales as editorialNombre 
            FROM libros 
            LEFT JOIN autor ON libros.autorID = idautor 
            LEFT JOIN editorial ON libros.Editorial = ideditorial 
            WHERE 1=1";

    $params = [];

    
    if (!empty($busqueda)) {
        $sql .= " AND (
            libros.ID LIKE :busqueda OR
            libros.IDLIB LIKE :busqueda OR
            libros.Codigo LIKE :busqueda OR
            libros.Titulo LIKE :busqueda OR
            autor.autores LIKE :busqueda OR
            editorial.editoriales LIKE :busqueda
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
}


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
            libros.IDLIB LIKE :busqueda OR
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
}