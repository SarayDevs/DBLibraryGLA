<?php
require_once 'Conexion.php';

class misRevistas {
    private $lastError;
   
    function verTodasLasRevistas() {
        $conexion = new Conexion();
        $arreglo = array();
    
        $consulta = "SELECT r.*, rv.revistas AS titulo
                     FROM revistas r
                     JOIN revista rv ON r.titulo = rv.idrevista";  // Ahora "r" está definido correctamente
    
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
    

    function verRevistasID($id = null) {
        $conexion = new Conexion();
        $arreglo = array();
        if ($id) {
            $consulta = "SELECT * FROM revistas WHERE id = :id";
            $modules = $conexion->prepare($consulta);
            $modules->bindParam(":id", $id);
        } else {
            $consulta = "SELECT * FROM revistas";
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

   
    function agregarRevistas($issn, $titulo, $proveedor, $volumen, $edicion,  $numero, $anio, $resumen, $imagen,  $subject_index) {

        try {
            $conexion = new Conexion();
            $consulta = "INSERT INTO revistas (issn,	titulo,	proveedor,	volumen,	edicion,	numero,	anio,	resumen,	imagen,	subject_index) 

                         VALUES (:issn, :titulo, :proveedor, :volumen, :edicion,  :numero, :anio, :resumen, :imagen,  :subject_index)";
    
            $stmt = $conexion->prepare($consulta);
    

            $stmt->bindParam(':issn', $issn);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':proveedor', $proveedor);
            $stmt->bindParam(':volumen', $volumen);
            $stmt->bindParam(':edicion', $edicion);
            $stmt->bindParam(':numero', $numero);
            $stmt->bindParam(':anio', $anio);
            $stmt->bindParam(':resumen', $resumen);
            $stmt->bindParam(':imagen', $imagen);
            $stmt->bindParam(':subject_index', $subject_index);

               
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

    


    function actualizarRevistas($id, $issn, $titulo, $proveedor, $volumen, $edicion,  $numero, $anio, $resumen, $imagen,  $subject_index) {
    
    try {
 
        $conexion = new Conexion();
        

        $consulta = "UPDATE revistas SET issn = :issn, titulo = :titulo, proveedor = :proveedor, volumen = :volumen, 
                     edicion = :edicion,  numero = :numero, anio = :anio, resumen = :resumen, imagen = :imagen, subject_index = :subject_index 

                     WHERE id = :id";


        $stmt = $conexion->prepare($consulta);

   
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':issn', $issn);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':proveedor', $proveedor);
        $stmt->bindParam(':volumen', $volumen);
        $stmt->bindParam(':edicion', $edicion);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':anio', $anio);
        $stmt->bindParam(':resumen', $resumen);
        $stmt->bindParam(':imagen', $imagen);
        $stmt->bindParam(':subject_index', $subject_index);
        
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
public function verRevistas1($limite, $offset, $busqueda = '') {
    $conexion = new Conexion();

    $sql = "SELECT DISTINCT revistas.*, revista.revistas AS nombre_revista
            FROM revistas
            LEFT JOIN revista ON revistas.titulo = revista.idrevista
            WHERE 1=1";

    if (!empty($busqueda)) {
        $sql .= " AND (
            revistas.id LIKE :busqueda OR
            revistas.issn LIKE :busqueda OR
            revista.revistas LIKE :busqueda OR
            revistas.anio LIKE :busqueda OR
            revistas.resumen LIKE :busqueda OR
            revistas.subject_index LIKE :busqueda
        )";
    }

    $sql .= " LIMIT :offset, :limite";

    $stmt = $conexion->prepare($sql);

    if (!empty($busqueda)) {
        $busqueda_param = '%' . $busqueda . '%';
        $stmt->bindValue(':busqueda', $busqueda_param, PDO::PARAM_STR);
    }

    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function contarRevistas1($busqueda = '') {
    $conexion = new Conexion();

    $query = "SELECT COUNT(DISTINCT revistas.id) as total 
              FROM revistas
              LEFT JOIN revista ON revistas.titulo = revista.idrevista
              WHERE 1=1";

    $params = [];

    if (!empty($busqueda)) {
        $query .= " AND (
            revistas.id LIKE :busqueda OR
            revistas.issn LIKE :busqueda OR
            revista.revistas LIKE :busqueda OR
            revistas.anio LIKE :busqueda OR
            revistas.resumen LIKE :busqueda OR
            revistas.subject_index LIKE :busqueda
        )";
        $params[':busqueda'] = '%' . $busqueda . '%';
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
    $consulta = "SELECT imagen FROM revistas WHERE id = :ID";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':ID', $id);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultado ? $resultado['imagen'] : null;
}

function eliminarRevista($id) {
    try {
        $conexion = new Conexion();
        $conexion->beginTransaction();

        // Verificar si la revista existe
        $verificar = $conexion->prepare("SELECT COUNT(*) FROM revistas WHERE id = :id");
        $verificar->bindParam(':id', $id, PDO::PARAM_INT);
        $verificar->execute();

        if ($verificar->fetchColumn() == 0) {
            throw new Exception("La revista con ID $id no existe.");
        }

        // Intentar eliminar la revista
        $consulta = $conexion->prepare("DELETE FROM revistas WHERE id = :id");
        $consulta->bindParam(':id', $id, PDO::PARAM_INT);

        if (!$consulta->execute()) {
            throw new Exception("No se pudo eliminar la revista con ID: $id");
        }

        $conexion->commit();
        return true;

    } catch (Exception $e) {
        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }
        error_log("❌ Error en eliminarRevista: " . $e->getMessage());
        return false;
    }
}

function reorganizarIDsRevistas() {
    try {
        $conexion = new Conexion();

        // Iniciar una nueva transacción
        $conexion->beginTransaction();

        // Reasignar IDs en orden secuencial
        $reasignarConsulta = "
            SET @new_id = 0;
            UPDATE revistas
            SET id = (@new_id := @new_id + 1)
            ORDER BY id;
        ";
        $conexion->exec($reasignarConsulta);

        // Reiniciar AUTO_INCREMENT
        $reiniciarAutoIncrement = "ALTER TABLE revistas AUTO_INCREMENT = 1;";
        $conexion->exec($reiniciarAutoIncrement);

        $conexion->commit();
        return true;

    } catch (Exception $e) {
        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }
        error_log("❌ Error en reorganizarIDsRevistas: " . $e->getMessage());
        return false;
    }
}



    


}