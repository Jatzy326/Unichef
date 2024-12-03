<?php
include 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_articulo = $_POST['id_articulo'];
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad'];
  

    $query = "UPDATE articulos SET nombre = ?, cantidad = ?, imagen = ? WHERE id_articulo = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sisi", $nombre, $cantidad, $id_articulo);
    if ($stmt->execute()) {
        echo "<script>alert('Artículo actualizado correctamente.'); window.location.href = 'rejilla.php';</script>";
    } else {
        echo "Error al actualizar el artículo.";
    }

    $stmt->close();
}
$con->close();
?>
