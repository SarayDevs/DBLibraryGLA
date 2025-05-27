<?php
require_once 'Conexion.php';

class misIndices {
    private $lastError;
    function verIndices() {
        $conexion = new Conexion();
        $arreglo = array();

        $consulta = "SELECT idindice, idlibro, seccionnum, titulo, pagina  FROM indice";
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

    function verIndiceID($id = null) {
        $conexion = new Conexion();
        $arreglo = array();
        if ($id) {
            $consulta = "SELECT idindice, idlibro, seccionnum, titulo, pagina, imagen  FROM indice WHERE idlibro = :id";
            $modules = $conexion->prepare($consulta);
            $modules->bindParam(":id", $id);
        } else {
            $consulta = "SELECT idindice, idlibro, seccionnum, titulo, pagina, imagen  FROM indice";
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
        }
        return $arreglo;
    }
    function agregarIndice( $idlibro, $seccionnum=null, $titulo=null, $pagina =null, $imagen = null ) {
        try {
            $conexion = new Conexion();
            $consulta = "INSERT INTO indice (idlibro, seccionnum, titulo, pagina, imagen) 
                         VALUES (:idlibro, :seccionnum, :titulo, :pagina, :imagen)";
    
            $stmt = $conexion->prepare($consulta);
    
            $stmt->bindParam(':idlibro',  $idlibro);
            $stmt->bindParam(':seccionnum',  $seccionnum);
            $stmt->bindParam(':titulo',  $titulo);
            $stmt->bindParam(':pagina',  $pagina);    
            $stmt->bindParam(':imagen', $imagen);


            if (!$stmt->execute()) {
               
                $this->lastError = $stmt->errorInfo()[2]; 
                return false;
            }
            return true;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage(); 
            return false;
        }
    }
    public function getLastError() {
        return $this->lastError;
    }
       function actualizarIndice($idindice, $seccionnum, $titulo, $pagina, $imagen = null) {
        try {
     
            $conexion = new Conexion();
    
            $consulta = "UPDATE indice SET seccionnum = :seccionnum, titulo = :titulo, pagina = :pagina, imagen = :imagen
                        WHERE idindice=:idindice";
    
            $stmt = $conexion->prepare($consulta);
    
            $stmt->bindParam(':idindice',  $idindice);
            $stmt->bindParam(':seccionnum',  $seccionnum);
            $stmt->bindParam(':titulo',  $titulo);
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
    
}