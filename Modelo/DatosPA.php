<?php
require_once 'Conexion.php';

class misArticulos {
    private $lastError;
    function verArticulosP() {
        $conexion = new Conexion();
        $arreglo = array();

        $consulta = "SELECT id, periodico_id, autor, titulo_articulo, pagina, imagen  FROM articulos_periodico";
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

    function verArticulosPID($periodico_id = null) {
        $conexion = new Conexion();
        $arreglo = array();
    
        if ($periodico_id) {
            $consulta = "SELECT id, periodico_id, autor, titulo_articulo, pagina, imagen FROM articulos_periodico WHERE periodico_id = :periodico_id";
            $modules = $conexion->prepare($consulta);
            $modules->bindParam(":periodico_id", $periodico_id, PDO::PARAM_INT);
        } else {
            $consulta = "SELECT id, periodico_id, autor, titulo_articulo, pagina, imagen FROM articulos_periodico";
            $modules = $conexion->prepare($consulta);
        }
    
        $modules->execute();
        $arreglo = $modules->fetchAll(PDO::FETCH_ASSOC); // Se obtiene todo de una vez
    
        // 🔍 Debug para verificar si hay datos
        error_log("Resultados de verArticulosRID: " . print_r($arreglo, true));
    
        return $arreglo;
    }
    
    function agregarArticuloP($periodico_id, $autor = null, $titulo_articulo = null, $pagina=null, $imagen = null) {
        try {
            $conexion = new Conexion();
            $consulta = "INSERT INTO articulos_periodico (periodico_id, autor, titulo_articulo, pagina, imagen) 
                         VALUES (:periodico_id, :autor, :titulo_articulo, :pagina, :imagen)";
    
            $stmt = $conexion->prepare($consulta);

    
            $stmt->bindParam(':periodico_id', $periodico_id);
            $stmt->bindParam(':autor', $autor);
            $stmt->bindParam(':titulo_articulo', $titulo_articulo);
            $stmt->bindParam(':pagina', $pagina);
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
       function actualizarArticuloP($id,  $autor, $titulo_articulo=null, $pagina=null, $imagen = null) {
        try {
     
            $conexion = new Conexion();
    
            $consulta = "UPDATE articulos_periodico SET  autor = :autor, titulo_articulo = :titulo_articulo, pagina = :pagina, imagen = :imagen
                        WHERE id=:id";
    
            $stmt = $conexion->prepare($consulta);
    
            $stmt->bindParam(':id',  $id);
            $stmt->bindParam(':autor',  $autor);
            $stmt->bindParam(':titulo_articulo',  $titulo_articulo);
            $stmt->bindParam(':pagina',  $pagina);
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
        $consulta = "SELECT imagen FROM articulos_periodico WHERE id = :ID";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':ID', $id);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['imagen'] : null;
    }
}