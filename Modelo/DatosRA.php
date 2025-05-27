<?php
require_once 'Conexion.php';

class misArticulosR {
    private $lastError;
    function verArticulosR() {
        $conexion = new Conexion();
        $arreglo = array();

        $consulta = "SELECT id, revista_id, autor, titulo_articulo, imagen  FROM articulos_revista";
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

    function verArticulosRID($revista_id = null) {
        $conexion = new Conexion();
        $arreglo = array();
    
        if ($revista_id) {
            $consulta = "SELECT id, revista_id, autor, titulo_articulo, imagen FROM articulos_revista WHERE revista_id = :revista_id";
            $modules = $conexion->prepare($consulta);
            $modules->bindParam(":revista_id", $revista_id, PDO::PARAM_INT);
        } else {
            $consulta = "SELECT id, revista_id, autor, titulo_articulo, imagen FROM articulos_revista";
            $modules = $conexion->prepare($consulta);
        }
    
        $modules->execute();
        $arreglo = $modules->fetchAll(PDO::FETCH_ASSOC); // Se obtiene todo de una vez
    
        // 🔍 Debug para verificar si hay datos
        error_log("Resultados de verArticulosRID: " . print_r($arreglo, true));
    
        return $arreglo;
    }
    
    function agregarArticuloR($revista_id, $autor = null, $titulo_articulo = null, $imagen = null) {
        try {
            $conexion = new Conexion();
            $consulta = "INSERT INTO articulos_revista (revista_id, autor, titulo_articulo, imagen) 
                         VALUES (:revista_id, :autor, :titulo_articulo, :imagen)";
    
            $stmt = $conexion->prepare($consulta);

    
            $stmt->bindParam(':revista_id', $revista_id);
            $stmt->bindParam(':autor', $autor);
            $stmt->bindParam(':titulo_articulo', $titulo_articulo);
            $stmt->bindParam(':imagen', $imagen);
    
            if (!$stmt->execute()) {
                error_log("❌ ERROR al ejecutar la consulta: " . implode(" | ", $stmt->errorInfo()));
                return false;
            }
    
            return true;
        } catch (Exception $e) {
            error_log("❌ ERROR en la inserción: " . $e->getMessage());
            return false;
        }
    }
    
    public function getLastError() {
        return $this->lastError;
    }
       function actualizarArticuloR($id,  $autor, $titulo_articulo=null, $imagen = null) {
        try {
     
            $conexion = new Conexion();
    
            $consulta = "UPDATE articulos_revista SET  autor = :autor, titulo_articulo = :titulo_articulo, imagen = :imagen
                        WHERE id=:id";
    
            $stmt = $conexion->prepare($consulta);
    
            $stmt->bindParam(':id',  $id);
            $stmt->bindParam(':autor',  $autor);
            $stmt->bindParam(':titulo_articulo',  $titulo_articulo);
            $stmt->bindParam(':imagen',  $imagen);
            
         
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
    function ObtenerImagenActual($id) {
        $conexion = new Conexion();
        $consulta = "SELECT imagen FROM articulos_revista WHERE id = :ID";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':ID', $id);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['imagen'] : null;
    }
}