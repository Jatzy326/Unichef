<?php
session_start(); // Asegúrate de que la sesión esté iniciada

include 'includes/config.php';
include 'includes/_header.php';

// Verifica si el usuario ha iniciado sesión y tiene el rol de 'encargado'
if (isset($_SESSION['role']) && $_SESSION['role'] === 'encargado') {
    header('Location: menu.php'); // Redirige a menu.php
    exit(); // Asegúrate de detener la ejecución después de redirigir
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista de Iconos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        /* Contenedor principal para alinear header y cuadros */
        .main-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .content {
            margin-top: 50;
            width: 100%;
            max-width: 800px; /* Limitar el ancho del contenedor */
            padding: 20px;

        }
        .icon-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* Tres columnas */
            gap: 20px;
            text-align: center;
        }
        .icon-tablet {
            width: 100%;
            height: 120px;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, background-color 0.3s ease;
            text-decoration: none;
            color: #333;
            font-size: 14px;
            font-weight: bold;
        }
        .icon-tablet:hover {
            transform: scale(1.05);
            background-color: #e0e0e0;
        }
        .cosa{
            font-family: 
        }
    </style>
</head>
<body>

<div class="main-container">
<div class="content">
    <div class="container-fluid bg-secondary text-white text-center py-2">
        <span><strong>Bienvenido:</strong> <?php echo $nombre; ?><strong>Fecha:</strong> <?php echo $date; ?> | <strong>Hora:</strong> <?php echo $time; ?> | <strong>Rol:</strong> <?php echo $role; ?></span>
    </div>
    
    <!-- Aquí puedes agregar el resto del contenido de cada sección -->
</div>    <!-- Cuadros de menú -->
    <div class="content">
        <div class="icon-grid">
            <a href="listamat.php" class="icon-tablet"> <img width="48" height="48" src="https://img.icons8.com/color/48/warehouse.png" alt="warehouse"/>Materiales</a>
            <a href="historial_al.php" class="icon-tablet"> <img width="70" height="70" src="https://img.icons8.com/bubbles/100/time-machine.png" alt="time-machine"/>Historial</a>
            <a href="profile.php" class="icon-tablet"><img width="60" height="60" src="https://img.icons8.com/3d-fluency/94/user-male-circle.png" alt="user-male-circle"/>Perfil</a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
