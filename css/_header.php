<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Incluye la conexión a la BD.
include_once('config.php');

// Verifica si la sesión está iniciada.
if (!isset($_SESSION['ID'])) {
    header("Location: ../index.php");
    exit();
}
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
</head>
<body class="fondo">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="border border-danger me-3" href="index.php">
                <img src="img/Gast.jpg" width="140" height="60" alt="">
            </a>
            <div class="navbar-text text-center mx-auto btn-" style="color: black;">
                <strong>Hola, Bienvenido:</strong> <?php echo ucwords($_SESSION['ROLE']) . ' ' . $_SESSION['MATRICULA']; ?>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-auto">
                    <?php if ($_SESSION['ROLE'] == 'administrador') { ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdownAdmin" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Alumnos</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownAdmin">
                                <a class="dropdown-item" href="r_alm.php">Registrar alumno</a>
                                <a class="dropdown-item" href="list_almn.php">Ver alumno</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdownAdmin" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Encargados</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownAdmin">
                                <a class="dropdown-item" href="r_encg.php">Registrar Encargado</a>
                                <a class="dropdown-item" href="list_encg.php">Ver Encargado</a>
                            </div>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link" href="profile.php">perfil</a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link" href="logout.php">Cerrar sesión</a>
                        </li>
                    <?php } ?>
                    <?php if ($_SESSION['ROLE'] == 'encargado') { ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdownEncargadoAlumnos" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Alumnos</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownEncargadoAlumnos">
                                <a class="dropdown-item" href="r_alm.php">Registrar alumno</a>
                                <a class="dropdown-item" href="list_almn.php">Ver alumno</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdownEncargadoInventario" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Inventario</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownEncargadoInventario">
                                <a class="dropdown-item" href="agregar_articulo.php">Registrar Artículo</a>
                                <a class="dropdown-item" href="list_art.php">Inventario</a>
                            </div>
                        </li>
                        <!-- <li class="nav-item me-3">
                            <a class="nav-link" href="pretamo.php">Historial</a>
                        </li> -->
                        <li class="nav-item me-3">
                            <a class="nav-link" href="profile.php">perfil</a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link" href="logout.php">Cerrar sesión</a>
                        </li>
                    <?php } ?>
                    <?php if ($_SESSION['ROLE'] == 'alumno') { ?>
                        <li class="nav-item me-3">
                            <a class="nav-link" href="listamat.php">Solicitar material</a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link" href="cart.php">Carrito</a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link" href="profile.php">perfil</a>
                        </li>
                        <!-- <li class="nav-item me-3">
                            <a class="nav-link" href="#">Historial</a>
                        </li> -->
                        <li class="nav-item me-3">
                            <a class="nav-link" href="logout.php">Cerrar sesión</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Asegúrate de incluir jQuery y Bootstrap antes de este script
        $(document).ready(function () {
            $('.navbar-toggler').click(function () {
                $('#navbarSupportedContent').toggleClass('collapse');
            });
        });
    </script>
</body>
</html>
