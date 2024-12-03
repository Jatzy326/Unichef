<?php
ob_start();
include 'includes/_header.php';
include 'includes/config.php';

// Verificar que se haya recibido el parámetro id_admins y que sea válido
if (!isset($_GET['id_admins']) || !is_numeric($_GET['id_admins'])) {
    echo "<script>
        alert('ID inválido.');
        window.location.href = 'list_almn.php';
    </script>";
    exit();
}

$id_admins = intval($_GET['id_admins']);

// Preparar la consulta para eliminar el registro
$stmt = $con->prepare("DELETE FROM admins WHERE id_admins = ?");
$stmt->bind_param("i", $id_admins);

if ($stmt->execute()) {
    echo "<script>
        alert('Alumno eliminado correctamente.');
        window.location.href = 'list_almn.php';
    </script>";
} else {
    echo "<script>
        alert('Error al eliminar el alumno: " . $stmt->error . "');
        window.location.href = 'list_almn.php';
    </script>";
}

// Cerrar la declaración y la conexión
$stmt->close();
$con->close();

ob_end_flush();
?>
