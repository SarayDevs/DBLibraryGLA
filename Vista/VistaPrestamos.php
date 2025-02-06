<?php
require_once '../Modelo/Datosprestamos.php';  
require_once '../Modelo/Datoslibros.php';  

$prestamos = new misPrestamos();
$libros = new misLibros();

$limite = 15; 
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $limite;

$listaPrestamos = $prestamos->verPrestamoss($limite, $offset);

$totalPrestamos = $prestamos->contarPrestamos();
$totalPaginas = ceil($totalPrestamos / $limite);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Libros Prestados</title>
    <?php include '../Libreria/libreriacss.php'; ?>
    <?php include '../Libreria/libreriajs.php'; ?>
    <link rel="stylesheet" href="../Libreria/Modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f8fa;
            color: #333;
            margin: 0;
            padding: 51px;
            background-color: #e5e5f7;
background-image:  linear-gradient(#6fdeab 3px, transparent 3px), linear-gradient(90deg, #6fdeab 3px, transparent 3px), linear-gradient(#6fdeab 1.5px, transparent 1.5px), linear-gradient(90deg, #6fdeab 1.5px, #e5e5f7 1.5px);
background-size: 75px 75px, 75px 75px, 15px 15px, 15px 15px;
background-position: -3px -3px, -3px -3px, -1.5px -1.5px, -1.5px -1.5px;
            
        }

        .container {
            
            max-width: 1000px;
            margin: auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            
        }

        h1 {
            font-size: 2em;
            color: #444;
            text-align: center;
            margin-bottom: 1em;
        }

        p {
            font-size: 1.1em;
            margin: 8px 0;
            padding: 10px;
            border-bottom: 1px solid #e1e1e1;
        }

        p strong {
            color: #555;
            font-weight: bold;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .back-link:hover {
            background-color: #0056b3;
        }
        /* Contenedor de paginación */
.pagination {
    display: flex;
    justify-content: center; /* Centra los botones de paginación */
    gap: 8px; /* Espacio entre cada botón */
    margin: 20px 0;
}

/* Estilo de los enlaces de paginación */
.pagination a {
    display: inline-block;
    padding: 8px 16px;
    background-color: #51d9ce; /* Color de fondo azul */
    color: #fff; /* Texto en blanco */
    border-radius: 5px; /* Bordes redondeados */
    text-decoration: none;
    font-size: 1em;
    transition: background-color 0.3s ease; /* Transición para el efecto hover */
}
.pagination a.pagina-actual {
    background-color: #3db2a9; /* Un color diferente */
    font-weight: bold;
    cursor: default; /* No clickeable */
    pointer-events: none; /* Desactiva el enlace */
}
/* Efecto hover para los enlaces */
.pagination a:hover {
    background-color: #0056b3; /* Azul más oscuro al pasar el mouse */
}

/* Estilo para la página activa */
.pagination a.active {
    background-color: #0056b3; /* Azul más oscuro para indicar la página actual */
    font-weight: bold;
    cursor: default; /* Cursor fijo en la página activa */
}

/* Opcional: Si usas botones "Siguiente" y "Anterior" */
.pagination a.prev,
.pagination a.next {
    font-weight: bold;
    padding: 8px 12px;
}
.sidebar {
    width: 250px;
    background-color: #51d9ce;
    color: white;
    padding-top: 20px;
    position: fixed;
    height: 100%;
    top: 0;
    left: 0;
    transition: width 0.1s ease; /* Añadido para animar la transición al colapsar */
}

.sidebar h2 {
    text-align: center;
    color: #fff;
    padding: 20px 0;
    margin: 0;
}
.logo {
            width: 160px;
            height: auto;
            margin-bottom: 20px;
            margin: 35px;
        }
.sidebar ul {
    list-style-type: none;
    padding: 0;
}

.sidebar ul li {
    padding: 15px;
    text-align: center;
}

.sidebar ul li a {
    color: white;
    text-decoration: none;
    display: block;
    font-size: 18px;
}
.sidebar ul li a.active {
    background-color: #3db2a9; /* Un color ligeramente diferente */
    color: #ffffff; /* Asegúrate de mantener el texto legible */
    font-weight: bold; /* Opcional: para destacar */
}


.sidebar ul li a:hover {
    background-color: #49c4ba;
}

/* Contenido principal */
.content {
    margin-left: 250px;
    padding: 20px;
    flex-grow: 1;
    background-color: #fff;
    min-height: 100vh;
    transition: margin-left 0.3s ease; /* Animación para que el contenido se ajuste */
}

.content h1 {
    font-size: 24px;
    color: #333;
}

/* Estilos para el botón */
.toggle-btn {
    position: fixed;
    top: 20px;
    left: 20px;
    background-color: #5fe9de;
    color: white;
    border: none;
    padding: 10px;
    font-size: 20px;
    cursor: pointer;
    z-index: 10; /* Asegura que el botón esté por encima de otros elementos */
}

.sidebar.collapsed {
    width: 0;
    padding-top: 0;
    overflow: hidden;
}

.sidebar.collapsed ul {
    display: none;
}

/* Ajustes cuando el menú está colapsado */
.sidebar.collapsed + .content {
    margin-left: 0; /* El contenido ocupa todo el espacio */
}

    </style>
    <script>
        (function () {
            const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            if (isCollapsed) {
                document.documentElement.classList.add('sidebar-initial-collapsed');
            }
        })();
    </script>
</head>                
<body>
<div class="sidebar">
        <br>
        <br>
        
        <h2>Panel de Control</h2>
        <ul>
            <li><a href="../Vista/VistaLibros.php">Lista de Libros</a></li>
            <li><a href="../Vista/Insertar.php" >Insertar Libro</a></li>
            <li><a href="../Vista/VistaPrestamos.php" class="active">Libros Prestados</a></li>
            <br>
            <br>
            <br>
            <img src="../imagen/logo-blanco.png" alt="Logo de la Biblioteca" class="logo">
        </ul>
    </div>
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
<div class="container">
    <h1>Lista de Libros Prestados</h1>
    <div class="pagination">

    <?php if ($paginaActual > 1): ?>
        <a href="?pagina=1">Primero</a>
        <a href="?pagina=<?php echo $paginaActual - 1; ?>">Anterior</a>
    <?php endif; ?>

    <?php
    $rangoInicio = max(1, $paginaActual - 2); 
    $rangoFin = min($totalPaginas, $paginaActual + 2); 

    if ($rangoFin - $rangoInicio < 4) {
        if ($rangoInicio > 1) {
            $rangoInicio = max(1, $rangoInicio - (4 - ($rangoFin - $rangoInicio)));
        } else {
            $rangoFin = min($totalPaginas, $rangoFin + (4 - ($rangoFin - $rangoInicio)));
        }
    }

    for ($i = $rangoInicio; $i <= $rangoFin; $i++): ?>
        <a href="?pagina=<?php echo $i; ?>" <?php if ($i == $paginaActual) echo 'class="pagina-actual"'; ?>>
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>

    
    <?php if ($paginaActual < $totalPaginas): ?>
        <a href="?pagina=<?php echo $paginaActual + 1; ?>">Siguiente</a>
        <a href="?pagina=<?php echo $totalPaginas; ?>">Último</a>
    <?php endif; ?>
</div>
    <table class="table table-bordered">
        <tr>
            <th>Número de Préstamo</th>
            <th>ID Libro </th>
            <th>Libro</th>
            <th>Estado</th>
            <th>Nombre de la Persona</th>
            <th>Tipo de Persona</th>
            <th>Fecha de Préstamo</th>
            <th>Fecha de devolución</th>
        </tr>
        <?php foreach ($listaPrestamos as $prestamo): ?>
            <tr>
                <td><?php echo $prestamo['IDPREST']; ?></td>
                <td><?php echo $prestamo['libprest']?></td>
                <td>
                    <a href="VistaDetalleLibro.php?id=<?php echo $prestamo['libprest']; ?>">
                        <?php echo $libros->obtenerTituloLibro($prestamo['libprest']); ?>
                    </a>
                </td>
                <td><?php echo $prestamo['estadoprest'] == 1 ? 'Prestado' : 'Devuelto'; ?>
                </td>
                <td><?php echo $prestamo['nombrep']; ?></td>
                <td>
                <?php
                    echo $prestamo['tipoperson'] == 1 ? 'Docente' :
                    ($prestamo['tipoperson'] == 2 ? 'Estudiante' :
                    ($prestamo['tipoperson'] == 3 ? 'Directivo' : 
                    ($prestamo['tipoperson'] == 4 ? 'Oficios Varios' :
                    ($prestamo['tipoperson'] == 5 ? 'Invitado' : 'Otros')
                    )));
                ?>
                </td>
                <td><?php echo $prestamo['FECHA']; ?></td>
                <td><?php echo $prestamo['DEVOLUCION'];?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div class="pagination">
    
    <?php if ($paginaActual > 1): ?>
        <a href="?pagina=1">Primero</a>
        <a href="?pagina=<?php echo $paginaActual - 1; ?>">Anterior</a>
    <?php endif; ?>

    <?php
    $rangoInicio = max(1, $paginaActual - 2); 
    $rangoFin = min($totalPaginas, $paginaActual + 2); 

    if ($rangoFin - $rangoInicio < 4) {
        if ($rangoInicio > 1) {
            $rangoInicio = max(1, $rangoInicio - (4 - ($rangoFin - $rangoInicio)));
        } else {
            $rangoFin = min($totalPaginas, $rangoFin + (4 - ($rangoFin - $rangoInicio)));
        }
    }

    for ($i = $rangoInicio; $i <= $rangoFin; $i++): ?>
        <a href="?pagina=<?php echo $i; ?>" <?php if ($i == $paginaActual) echo 'class="pagina-actual"'; ?>>
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>

    <?php if ($paginaActual < $totalPaginas): ?>
        <a href="?pagina=<?php echo $paginaActual + 1; ?>">Siguiente</a>
        <a href="?pagina=<?php echo $totalPaginas; ?>">Último</a>
    <?php endif; ?>
</div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.querySelector('.toggle-btn');

    // Configura el estado inicial del menú desde localStorage
    const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
    if (isCollapsed) {
        sidebar.classList.add('collapsed');
    }

    // Elimina la clase inicial una vez que el documento está cargado
    document.documentElement.classList.remove('sidebar-initial-collapsed');

    // Alternar el menú al hacer clic en el botón
    toggleBtn.addEventListener('click', function () {
        sidebar.classList.toggle('collapsed');
        const isNowCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebar-collapsed', isNowCollapsed);
    });
});
</script>
</body>
</html>