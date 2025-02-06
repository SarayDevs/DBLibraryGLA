<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="styles.css"> <!-- Vinculando el archivo CSS -->
</head>
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

/* Barra lateral */
.sidebar {
    width: 250px;
    background-color: #51d9ce;
    color: white;
    padding-top: 20px;
    position: fixed;
    height: 100%;
    top: 0;
    left: 0;
    transition: width 0.3s ease; /* Añadido para animar la transición al colapsar */
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
<body>

    <div class="sidebar">
        <br>
        <br>
        
        <h2>Panel de Control</h2>
        <ul>
            <li><a href="../Vista/VistaLibros.php">Lista de Libros</a></li>
            <li><a href="../Vista/Insertar.php">Insertar Libro</a></li>
            <li><a href="../Vista/VistaPrestamos.php">Libros Prestados</a></li>
            <br>
            <br>
            <br>
            <img src="../imagen/logo-blanco.png" alt="Logo de la Biblioteca" class="logo">
        </ul>
    </div>
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
    

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
