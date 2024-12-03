<?php
ob_start();
include 'includes/_header.php'; 
include 'includes/config.php';

// Verificar si se proporcionó un ID válido a través de la URL
if (isset($_GET['id_articulo']) && is_numeric($_GET['id_articulo'])) {
    // Obtener y escapar el ID del artículo
    $id_articulo = mysqli_real_escape_string($con, $_GET['id_articulo']);

    // Preparar la consulta para eliminar el artículo
    $deleteQuery = "DELETE FROM articulos WHERE id_articulo = $id_articulo";

    // Ejecutar la consulta
    if (mysqli_query($con, $deleteQuery)) {
        // Redirigir después de eliminar el artículo
        header("Location: list_art.php");
        exit();
    } else {
        // Si hay un error al ejecutar la consulta, mostrar un mensaje de error
        echo "Error al eliminar el artículo: " . mysqli_error($con);
    }
} else {
    // Si no se proporcionó un ID válido, mostrar un mensaje de error
    echo "ID de artículo no válido.";
}
ob_end_flush();
?>
