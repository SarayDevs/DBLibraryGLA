<?php
require_once 'Conexion.php';

class miRevista {
    private $lastError;
    function verRevista() {
        $conexion = new Conexion();
        $arreglo = array();

        $consulta = "SELECT idrevista, revistas FROM revista";
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

    function verRevistaID($id = null) {
        $conexion = new Conexion();
        $arreglo = array();
        if ($id) {
            $consulta = "SELECT  idrevista, revistas FROM revista WHERE idrevista = :id";
            $modules = $conexion->prepare($consulta);
            $modules->bindParam(":id", $id);
        } else {
            $consulta = "SELECT  idrevista, revistas FROM revista";
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
    public function agregarRevista($TipoRevista) {
        try {
            $conexion = new Conexion();
            $consulta = "INSERT INTO revista (revistas) VALUES (:revistas)";
        
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(':revistas', $TipoRevista);
        
            if (!$stmt->execute()) {
                $this->lastError = $stmt->errorInfo()[2]; 
                return false;
            }
            
            return $conexion->lastInsertId(); // Devuelve el ID de la nueva editorial
        } catch (Exception $e) {
            $this->lastError = $e->getMessage(); 
            return false;
        }
    }
    
    public function getLastError() {
        return $this->lastError;
    }
}