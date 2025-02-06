<?php
require_once 'Conexion.php';

class misClases {
    function verClases() {
        $conexion = new Conexion();
        $arreglo = array();

        $consulta = "SELECT idclase, clases FROM clase";
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

    function verClaseID($id = null) {
        $conexion = new Conexion();
        $arreglo = array();
        if ($id) {
            $consulta = "SELECT  idclase, clases FROM clase WHERE idclase = :id";
            $modules = $conexion->prepare($consulta);
            $modules->bindParam(":id", $id);
        } else {
            $consulta = "SELECT  idclase, clases FROM clase";
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

}