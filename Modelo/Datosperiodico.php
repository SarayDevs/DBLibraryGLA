<?php
require_once 'Conexion.php';

class misPeriodicos {
    private $lastError;
   
    function verTodosLosPeriodicos() {
        $conexion = new Conexion();
        $arreglo = array();

        $consulta = "SELECT p.*, pv.periodicos AS nombre
             FROM periodicos p
             JOIN periodico pv ON p.nombre = pv.idperiodico";

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


    function verPeriodicosID($id = null) {
        $conexion = new Conexion();
        $arreglo = array();
        if ($id) {
            $consulta = "SELECT * FROM periodicos WHERE id = :id";
            $modules = $conexion->prepare($consulta);
            $modules->bindParam(":id", $id);
        } else {
            $consulta = "SELECT * FROM periodicos";
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

   
    function agregarPeriodicos(	$nombre,	$proveedor,	$fecha,	$imagen ) {

        try {
            $conexion = new Conexion();
            $consulta = "INSERT INTO periodicos (	nombre,	proveedor,	fecha,	imagen) 

                         VALUES (	:nombre,	:proveedor,	:fecha,	:imagen)";
    
            $stmt = $conexion->prepare($consulta);
    

            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':proveedor', $proveedor);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':imagen', $imagen);

     
            
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

    


    function actualizarPeriodicos($id,	$nombre,	$proveedor,	$fecha,	$imagen) {
    
    try {
 
        $conexion = new Conexion();
        

        $consulta = "UPDATE periodicos SET nombre = :nombre, proveedor = :proveedor, fecha = :fecha, imagen = :imagen
                     WHERE id = :ID";

        $stmt = $conexion->prepare($consulta);

   
        $stmt->bindParam(':ID', $id);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':proveedor', $proveedor);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':imagen', $imagen);

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
public function verPeriodicos1($limite, $offset, $busqueda = '') {
    $conexion = new Conexion();

    $sql = "SELECT DISTINCT periodicos.*, periodico.periodicos AS nombre_periodico
            FROM periodicos
            LEFT JOIN articulos_periodico ON periodicos.id = articulos_periodico.periodico_id
            LEFT JOIN periodico ON periodicos.nombre = periodico.idperiodico
            WHERE 1=1";

    $params = [];

    if (!empty($busqueda)) {
        $sql .= " AND (
            periodicos.id LIKE :busqueda OR
            periodico.periodicos LIKE :busqueda OR
            periodicos.fecha LIKE :busqueda OR
            articulos_periodico.titulo_articulo LIKE :busqueda
        )";
        $params[':busqueda'] = '%' . $busqueda . '%';
    }

    $sql .= " LIMIT :offset, :limite";

    $stmt = $conexion->prepare($sql);

    if (!empty($busqueda)) {
        $stmt->bindValue(':busqueda', '%' . $busqueda . '%', PDO::PARAM_STR);
    }

    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function contarPeriodicos1($busqueda = '') {
    $conexion = new Conexion();

    $query = "SELECT COUNT(DISTINCT periodicos.id) as total 
              FROM periodicos
              LEFT JOIN articulos_periodico ON periodicos.id = articulos_periodico.periodico_id
              LEFT JOIN periodico ON periodicos.nombre = periodico.idperiodico
              WHERE 1=1";

    $params = [];

    if (!empty($busqueda)) {
        $query .= " AND (
            periodicos.id LIKE :busqueda OR
            periodico.periodicos LIKE :busqueda OR
            periodicos.fecha LIKE :busqueda OR
            articulos_periodico.titulo_articulo LIKE :busqueda
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
    $consulta = "SELECT imagen FROM periodicos WHERE id = :ID";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':ID', $id);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultado ? $resultado['imagen'] : null;
}

function eliminarPeriodico($id) {
    try {
        $conexion = new Conexion();
        $conexion->beginTransaction();

        // Verificar si la revista existe
        $verificar = $conexion->prepare("SELECT COUNT(*) FROM periodicos WHERE id = :id");
        $verificar->bindParam(':id', $id, PDO::PARAM_INT);
        $verificar->execute();

        if ($verificar->fetchColumn() == 0) {
            throw new Exception("El periodico con ID $id no existe.");
        }

        // Intentar eliminar la revista
        $consulta = $conexion->prepare("DELETE FROM periodicos WHERE id = :id");
        $consulta->bindParam(':id', $id, PDO::PARAM_INT);

        if (!$consulta->execute()) {
            throw new Exception("No se pudo eliminar el periodico con ID: $id");
        }

        $conexion->commit();
        return true;

    } catch (Exception $e) {
        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }
        error_log("❌ Error en eliminarPeriodico: " . $e->getMessage());
        return false;
    }
}

function reorganizarIDsPeriodicos() {
    try {
        $conexion = new Conexion();

        // Iniciar una nueva transacción
        $conexion->beginTransaction();

        // Reasignar IDs en orden secuencial
        $reasignarConsulta = "
            SET @new_id = 0;
            UPDATE periodicos
            SET id = (@new_id := @new_id + 1)
            ORDER BY id;
        ";
        $conexion->exec($reasignarConsulta);

        // Reiniciar AUTO_INCREMENT
        $reiniciarAutoIncrement = "ALTER TABLE periodicos AUTO_INCREMENT = 1;";
        $conexion->exec($reiniciarAutoIncrement);

        $conexion->commit();
        return true;

    } catch (Exception $e) {
        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }
        error_log("❌ Error en reorganizarIDsPeriodicos: " . $e->getMessage());
        return false;
    }
}

}