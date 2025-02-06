<?php
require_once 'Conexion.php';

class misAutores {
    private $lastError;
    function verAutores() {
        $conexion = new Conexion();
        $arreglo = array();

        $consulta = "SELECT idautor, autores FROM autor";
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

    function verAutorID($id = null) {
        $conexion = new Conexion();
        $arreglo = array();
        if ($id) {
            $consulta = "SELECT  idautor, autores FROM autor WHERE idautor = :id";
            $modules = $conexion->prepare($consulta);
            $modules->bindParam(":id", $id);
        } else {
            $consulta = "SELECT  idautor, autores FROM autor";
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
    function agregarAutor( $Autor) {
        try {
            $conexion = new Conexion();
            $consulta = "INSERT INTO autor (autores) 
                         VALUES (:autores)";
    
            $stmt = $conexion->prepare($consulta);
    
            $stmt->bindParam(':autores',  $Autor);
    

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
    
}