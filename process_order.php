<?php

session_start();
include 'includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_requisicion'], $_POST['action'])) {
    $id_requisicion = $_POST['id_requisicion'];
    $action = $_POST['action'];

    if ($action == 'Entregado') {
        $estado = 'Entregado';
    } elseif ($action == 'Devuelto') {
        $estado = 'Devuelto';

        // Obtener detalles del pedido para devolver la cantidad al inventario
        $query = "SELECT id_articulo, cantidad FROM devolucion WHERE id_requisicion = ?";
        $stmt = $con->prepare($query);
        if ($stmt) {
            $stmt->bind_param("i", $id_requisicion);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($pedido = $result->fetch_assoc()) {
                $id_articulo = $pedido['id_articulo'];
                $cantidad = $pedido['cantidad'];

                // Devolver la cantidad al inventario
                $update_query = "UPDATE articulos SET cantidad = cantidad + ? WHERE id_articulo = ?";
                $update_stmt = $con->prepare($update_query);
                if ($update_stmt) {
                    $update_stmt->bind_param("ii", $cantidad, $id_articulo);
                    $update_stmt->execute();
                    $update_stmt->close();
                }
            }
            $stmt->close();
        }
    } else {
        // Acción no válida
        header("Location: notification.php");
        exit();
    }

    // Actualizar el estado del pedido
    $update_query = "UPDATE requisicion SET estado = ? WHERE id_requisicion = ?";
    $update_stmt = $con->prepare($update_query);
    if ($update_stmt) {
        $update_stmt->bind_param("si", $estado, $id_requisicion);
        $update_stmt->execute();
        $update_stmt->close();
    }

    header("Location: notification.php");
    exit();
}

$con->close();
?>
