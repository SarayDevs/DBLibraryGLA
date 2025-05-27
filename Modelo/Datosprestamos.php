<?php
require_once 'Conexion.php';

class misPrestamos {
    private $lastError;
    function verPrestamos() {
        $conexion = new Conexion();
        $arreglo = array();

        $consulta = "SELECT IDPREST, libprest, estadoprest, Tipoprest, nombrep, tipoperson,FECHA FROM  prestamos";
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

    function verPrestamosID($id = null) {
        $conexion = new Conexion();
        $arreglo = array();
        if ($id) {
            $consulta = "SELECT IDPREST, libprest, estadoprest, Tipoprest, nombrep, tipoperson, FECHA FROM  prestamos WHERE IDPREST = :id";
            $modules = $conexion->prepare($consulta);
            $modules->bindParam(":id", $id);
        } else {
            $consulta = "SELECT IDPREST, libprest, estadoprest, Tipoprest, nombrep, tipoperson, FECHA FROM  prestamos";
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
    function agregarPrestamos($libprest, $estadoprest, $Tipoprest, $nombrep, $tipoperson, $fecha, $DEVOLUCION=NULL) {
        try {
            $conexion = new Conexion();
            $pdo = $conexion;
            $consulta = "INSERT INTO prestamos (libprest, estadoprest, Tipoprest, nombrep, tipoperson, FECHA, DEVOLUCION)
                         VALUES (:libprest, :estadoprest,:Tipoprest, :nombrep, :tipoperson,  :fecha, :DEVOLUCION)";
            
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(':libprest', $libprest);
            $stmt->bindParam(':estadoprest', $estadoprest);
            $stmt->bindParam(':Tipoprest', $Tipoprest);
            $stmt->bindParam(':nombrep', $nombrep);
            $stmt->bindParam(':tipoperson', $tipoperson);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':DEVOLUCION', $DEVOLUCION);
    
            if ($stmt->execute()) {
                return $pdo->lastInsertId();
            } else {
                error_log("Error al ejecutar la consulta: " . implode(", ", $stmt->errorInfo()));
                return false;
            }
        } catch (Exception $e) {
            error_log("Error en agregarPrestamos: " . $e->getMessage());
            return false;
        }
    }
    
    function actualizarPrestamos($IDPREST, $estadoprest) {
    try {
 
        $conexion = new Conexion();
        $fechaDevolucion = ($estadoprest == 0) ? date('Y-m-d') : null;

        $consulta = "UPDATE prestamos SET estadoprest = :estadoprest, DEVOLUCION = :DEVOLUCION 
                    WHERE IDPREST = :IDPREST";

        $stmt = $conexion->prepare($consulta);

        $stmt->bindParam(':IDPREST', $IDPREST);
        $stmt->bindParam(':estadoprest', $estadoprest);
        $stmt->bindParam(':DEVOLUCION', $fechaDevolucion);
     
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
public function getLastError() {
    return $this->lastError;
}

public function contarPrestamos() {
    try {
        $conexion = new Conexion();
        $sql = "SELECT COUNT(*) as total FROM prestamos";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'];
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return 0;
    }
}

public function verPrestamoss($limite, $offset) {
    try {
        $conexion = new Conexion(); 
        $sql = "SELECT * FROM prestamos LIMIT :limite OFFSET :offset";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}
function VerPrestamosExternos(){
    $conexion = new Conexion();
    $arreglo = array();

    $consulta = "SELECT ID,	idprestamo,	tipoprest,	idlibro, nombre, persona, contacto,	tiempo,	extension FROM  externoprest";
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

function verPrestamosExternosID($id = null) {
    $conexion = new Conexion();
    $arreglo = array();
    if ($id) {
        $consulta = "SELECT ID,	idprestamo,	tipoprest,	idlibro, nombre, persona, contacto,	tiempo,	extension, fecha_inicio FROM  externoprest WHERE ID = :id";
        $modules = $conexion->prepare($consulta);
        $modules->bindParam(":id", $id);
    } else {
        $consulta = "SELECT ID,	idprestamo,	tipoprest,	idlibro, nombre, persona, contacto,	tiempo,	extension, fecha_inicio FROM  externoprest";
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

function agregarPrestamosExternos($ID,	$idprestamo,	$tipoprest,	$idlibro, $nombre, $persona, $contacto,	$tiempo, $fecha_inicio ) {
    try {
        $conexion = new Conexion();
        $consulta = "INSERT INTO externoprest (ID,	idprestamo,	tipoprest,	idlibro, nombre, persona, contacto,	tiempo,	fecha_inicio,  )
                     VALUES (:ID,	:idprestamo,	:tipoprest,	:idlibro, :nombre, :persona, :contacto,	:tiempo,:fecha_inicio,	) ";
        
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':ID', $ID);
        $stmt->bindParam(':idprestamo', $idprestamo);
        $stmt->bindParam(':tipoprest', $tipoprest);
        $stmt->bindParam(':idlibro', $idlibro);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':persona', $persona);
        $stmt->bindParam(':contacto', $contacto);
        $stmt->bindParam(':tiempo', $tiempo);       
        $stmt->bindParam(':fecha_inicio', $fecha_inicio);

 

        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Error al ejecutar la consulta: " . implode(", ", $stmt->errorInfo()));
            return false;
        }
    } catch (Exception $e) {
        error_log("Error en agregarPrestamos: " . $e->getMessage());
        return false;
    }
}
function actualizarPrestamosExternos($ID, $tiempoActual, $extension) {
    try {
        $conexion = new Conexion();

        // Calcular el nuevo tiempo total sumando la extensión
        $nuevoTiempo = $tiempoActual + $extension;

        $consulta = "UPDATE externoprest 
                     SET tiempo = :nuevoTiempo, extension = :extension
                     WHERE idprestamo = :ID";

        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':ID', $ID, PDO::PARAM_INT);
        $stmt->bindParam(':nuevoTiempo', $nuevoTiempo, PDO::PARAM_INT);
        $stmt->bindParam(':extension', $extension, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            error_log("Error en la actualización: " . implode(", ", $stmt->errorInfo()));
            return false;
        }

        return true;
    } catch (PDOException $e) {
        error_log("Error en actualizarPrestamosExternos: " . $e->getMessage());
        return false;
    }
}
public function verExternPrestamoss($limite, $offset) {
    try {
        $conexion = new Conexion(); 
        $sql = "SELECT e.*, p.estadoprest, p.DEVOLUCION 
                FROM externoprest e
                LEFT JOIN prestamos p ON e.idprestamo = p.IDPREST
                LIMIT :limite OFFSET :offset";
                
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}


public function contarExternPrestamos() {
    try {
        $conexion = new Conexion();
        $sql = "SELECT COUNT(*) as total FROM externoprest";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'];
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return 0;
    }
}



}
