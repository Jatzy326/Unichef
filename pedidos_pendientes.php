<?php
include 'includes/config.php';
include 'includes/_header.php';

$query = "SELECT * FROM requisicion WHERE estado = 'pendiente'";
$result = $con->query($query);

echo "<div class='container mt-5'>";
echo "<h2>Requisiciones Pendientes</h2>";

if ($result->num_rows > 0) {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>Requisicion ID: " . htmlspecialchars($row['id_requisicion']) . " - Fecha: " . htmlspecialchars($row['fecha']);
        echo " <a href='notification.php?order_id=" . $row['id_requisicion'] . "' class='btn btn-primary'>Procesar</a></li>";
    }
    echo "</ul>";
} else {
    echo "<p>No hay requisiciones pendientes.</p>";
}

echo "</div>";

$con->close();
?>
