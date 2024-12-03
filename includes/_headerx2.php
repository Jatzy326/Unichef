<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluye la conexión a la BD.
include('config.php');

// Configura la zona horaria de México.
date_default_timezone_set('America/Mexico_City');

// Verifica si la sesión está iniciada.
if (!isset($_SESSION['ID_ADMINS'])) {
    header("Location: ../index.php");
    exit();
}

// Obtiene el usuario actual desde la tabla `admins`.
$nombre = isset($_SESSION['USERNAME']) ? $_SESSION['USERNAME'] : '';
$role = isset($_SESSION['ROLE']) ? ucwords($_SESSION['ROLE']) : '';
$date = date("d/m/Y");
$time = date("H:i:s");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniChef</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.0/css/all.min.css">
    <link href="img/Gast.jpg" rel="icon">
    <link rel="stylesheet" href="css/diseño.css">
    <style>
        /* Estilos para la barra lateral */
        .sidebar {
            height: calc(100vh - 56px); /* Ajusta la altura para que no cubra el navbar */
            width: 250px;
            position: fixed;
            top: 56px; /* Colócala justo debajo del navbar */
            left: 0;
            background-color: #343a40;
            padding-top: 20px;
            color: #fff;
            overflow-y: auto; /* Agrega scroll si el contenido es largo */
        }

        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: #d1d1d1;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #575757;
            color: #ffffff;
        }

        /* Ajustes para el contenido */
        .content {
            margin-left: 250px;
            padding: 20px;
            margin-top: 56px; /* Alinea el contenido debajo del navbar */
        }
    </style>
</head>
<body class="fondo">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container d-flex align-items-center justify-content-between">
        <!-- Logo -->
        <a class="border border-danger me-3" href="menu.php">
            <img src="img/Gast.jpg" width="120" height="60" alt="Logo">
        </a>

        <!-- Usuario -->
        <div class="navbar-text text-center mx-3" style="color: black;">
            <strong>Usuario:</strong> <?php echo $role . ' ' . $nombre; ?>
        </div>

        <!-- Título -->
        <div class="text-center mx-3">
            <h6 class="m-0">UNIVERSIDAD TECNOLÓGICA DE LA COSTA GRANDE DE GUERRERO</h6>
        </div>
        <!-- Botón de cierre de sesión -->
        <div>    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
        </div>
    </div>
</nav>

<!-- Barra lateral -->
<div class="sidebar">
    <a href="menu.php"><i class="fas fa-home"></i> Menú</a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
