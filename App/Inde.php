<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="styles.css"> <!-- Vinculando el archivo CSS -->

<style>/* Estilos generales */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    height: 100vh;
    background-color: #f4f4f4;
    overflow-x: hidden; /* Evita el desbordamiento horizontal cuando el menú se contrae */
}


html.sidebar-initial-collapsed .sidebar {
    width: 0 !important;
    overflow: hidden;
    transition: none !important;
}

html.sidebar-initial-collapsed .content {
    margin-left: 0 !important;
    transition: none !important;
}

.sidebar {
    width: 250px;
    background-color: #174926;
    color: rgba(6, 36, 10, 0.877);
    position: fixed;
    height: 100%;
    top: 0;
    left: 0;
    padding-top: 68px;
    overflow: hidden;
    z-index: 1;
    transition: width 0.1s ease;
}

.sidebar.collapsed {
    width: 0;
    overflow: hidden;
}

.sidebar.collapsed+.content {
    margin-left: 0;
}

.content {
    transition: margin-left 0.1s ease;
    margin-left: 50px;
    padding: 10px;
    flex-grow: 1;
    min-height: 50vh;
    min-width: 10px;
}

.content.no-transition {
    transition: none;
}

.toggle-btn {
    position: fixed;
    top: 20px;
    left: 20px;
    background-color: #26773e;
    color: white;
    border: none;
    padding: 10px;
    font-size: 20px;
    cursor: pointer;
    z-index: 10;
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
    padding-top: 3px;
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

.sidebar ul li a.active {
    background-color: #206d31;
    color: #ffffff;
    font-weight: bold;
}

.sidebar ul li a {
    color: white;
    text-decoration: none;
    display: block;
    font-size: 18px;
}

.sidebar ul li a:hover {
    background-color: #1b5c21;
}

.content h1 {
    font-size: 24px;
    color: #333;
}

.sidebar.collapsed ul {
    display: none;
}

.sidebar.collapsed+.content {
    margin-left: 0;
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
            <li><a href="../Vista/VistaLibros.php">Libros</a></li>
            <li><a href="../Vista/VistaPeriodicos.php">Periodicos</a></li>
            <li><a href="../Vista/VistaRevistas.php">Revistas</a></li>
            <li><a href="../Vista/VistaPrestamos.php">Prestamos</a></li>
            <li><a href="../Vista/Insertar.php">Insertar Libro</a></li>
            <li><a href="../Vista/Insertrevista.php" >Insertar Revista</a></li>
            <li><a href="../Vista/Insertperiodico.php" >Insertar Periodico</a></li>
            <br>

            <img src="../imagen/logo-blanco.png" alt="Logo de la Biblioteca" class="logo">
        </ul>
    </div>
    <div class="content">
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

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
<script>
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('collapsed');
    }
</script>
<script>
    // Detecta la URL actual
    const currentPath = window.location.pathname;

    // Selecciona todos los enlaces del menú
    const menuLinks = document.querySelectorAll('.sidebar ul li a');

    // Itera y compara con la URL actual
    menuLinks.forEach(link => {
        if (link.getAttribute('href').includes(currentPath)) {
            link.classList.add('active');
        }
    });
</script>

</body>
</html>
