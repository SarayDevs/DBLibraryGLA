<?php
require_once 'Conexion.php';

class misActividades {
    private $lastError;
    function verActividades() {
        $conexion = new Conexion();
        $arreglo = array();

        $consulta = "SELECT IDposibles, disponibilidad FROM posibles";
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

	
    function verActividadID($id = null) {
        $conexion = new Conexion();
        $arreglo = array();
        if ($id) {
            $consulta = "SELECT IDposibles, disponibilidad FROM posibles WHERE IDposibles = :id";
            $modules = $conexion->prepare($consulta);
            $modules->bindParam(":id", $id);
        } else {
            $consulta = "SELECT IDposibles, disponibilidad FROM posibles";
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
    public function getLastError() {
        return $this->lastError;
    }
}