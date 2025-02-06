<?php
require_once 'Conexion.php';

class misSecciones {
    function verSecciones() {
        $conexion = new Conexion();
        $arreglo = array();

        $consulta = "SELECT idseccion, secciones FROM seccion";
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

    function verSeccionID($id = null) {
        $conexion = new Conexion();
        $arreglo = array();
        if ($id) {
            $consulta = "SELECT  idseccion, secciones FROM seccion WHERE idseccion = :id";
            $modules = $conexion->prepare($consulta);
            $modules->bindParam(":id", $id);
        } else {
            $consulta = "SELECT  idseccion, secciones FROM seccion";
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