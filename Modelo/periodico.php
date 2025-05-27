<?php
require_once 'Conexion.php';

class miPeriodico {
    private $lastError;
    function verPeriodico() {
        $conexion = new Conexion();
        $arreglo = array();

        $consulta = "SELECT idperiodico, periodicos FROM periodico";
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

    function verPeriodicoID($id = null) {
        $conexion = new Conexion();
        $arreglo = array();
        if ($id) {
            $consulta = "SELECT  idperiodico, periodicos FROM periodico WHERE idperiodico = :id";
            $modules = $conexion->prepare($consulta);
            $modules->bindParam(":id", $id);
        } else {
            $consulta = "SELECT  idperiodico, periodicos FROM periodico";
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
    public function agregarPeriodico($TipoPeriodico) {
        try {
            $conexion = new Conexion();
            $consulta = "INSERT INTO periodico (periodicos) VALUES (:periodicos)";
        
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(':periodicos', $TipoPeriodico);
        
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