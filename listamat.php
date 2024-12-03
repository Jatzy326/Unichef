<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'includes/_header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista de Artículos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style>
        .container2 {
            margin-top: 10%;
            margin-left: 120px; /* Mueve el contenido hacia la derecha */
        }
        .articles-container {
            margin-left: 20px; /* Ajusta el espacio interior para separación adicional */
        }
    </style>
</head>
<body>
    <div class="container2 my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 articles-container">
                 <!-- Clase personalizada -->
                <h2 class="text-center mb-4" style="color:#000;">Lista de Artículos</h2>
                <div class="row">
                    <?php
                    include 'includes/config.php';
                    $query = "SELECT id_articulo, nombre, categoria, cantidad, imagen FROM articulos WHERE estatus IN ('disponible', 'donado') AND cantidad > 0";
                    $result = $con->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='col-md-3 mb-4'>";
                            echo "<div class='card'>";
                            echo "<img src='" . htmlspecialchars($row['imagen']) . "' class='card-img-top' alt='Imagen' style='width: 90px; height: 90px; margin: auto;'>";
                            echo "<div class='card-body'>";
                            echo "<h5 class='card-title'>" . htmlspecialchars($row['nombre']) . "</h5>";
                            echo "<p class='card-text'>Categoría: " . htmlspecialchars($row['categoria']) . "</p>";
                            echo "<p class='card-text'>Disponibles: " . htmlspecialchars($row['cantidad']) . "</p>";
                            echo "<form action='add_to_cart.php' method='POST'>";
                            echo "<input type='hidden' name='id_articulo' value='" . $row['id_articulo'] . "'>";
                            echo "<label for='quantity'>Cantidad:</label>";
                            echo "<input type='number' id='quantity' name='quantity' value='1' min='1' max='" . htmlspecialchars($row['cantidad']) . "' required>";
                            echo "<button type='submit' class='btn btn-primary' name='add_to_cart'>Agregar a la requisición</button>";
                            echo "</form>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<div class='col-12 text-center'>No se encontraron artículos.</div>";
                    }
                    $con->close();
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
