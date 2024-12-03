<?php
include 'includes/config.php';

if (isset($_GET['id_requisicion'])) {
    $id_pedido = $_GET['id_requisicion'];

    // Consulta para obtener los detalles del pedido
    $query = "SELECT a.nombre, r.cantidad 
              FROM requisicion r 
              INNER JOIN articulos a ON r.id_articulo = r.id_articulo 
              WHERE r.id_requisicion = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $id_requisicion);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h5>Artículos en el Pedido</h5>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($row['nombre']) . " (Cantidad: " . htmlspecialchars($row['cantidad']) . ")</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No se encontraron detalles para este pedido.</p>";
    }
} else {
    echo "<p>ID de pedido no proporcionado.</p>";
}

$con->close();
?>
