<?php
include 'includes/config.php';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_requisicion = $_POST['id_requisicion'];
    $estado = $_POST['estado'];

    // Actualizar el estado de la requisición
    $query = "UPDATE requisicion SET estado = ? WHERE id_requisicion = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('si', $estado, $id_requisicion);
    $stmt->execute();

    // Si el estado es 'Entregado', crear una notificación para el alumno
    if ($estado == 'Entregado') {
        // Suponemos que el alumno está relacionado con la requisición, se puede hacer una consulta
        // para obtener el ID del alumno relacionado con la requisición
        $query = "SELECT id_alumno FROM requisicion WHERE id_requisicion = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $id_requisicion);
        $stmt->execute();
        $stmt->bind_result($id_alumno);
        $stmt->fetch();

        // Insertar la notificación para el alumno
        $mensaje = "Tu requisición con ID $id_requisicion ha sido entregada.";
        $query = "INSERT INTO notificaciones (id_alumno, mensaje, leida) VALUES (?, ?, 0)";
        $stmt = $con->prepare($query);
        $stmt->bind_param('is', $id_alumno, $mensaje);
        $stmt->execute();
    }

    // Redirigir de vuelta a la página anterior
    header('Location: historial_pedidos.php');
}
?>
