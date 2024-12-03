<?php
include 'includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $id_requisicion = $_POST['id_requisicion'];
    $estado = $_POST['estado'];

    // Validar entradas
    if (!isset($id_requisicion) || !isset($estado)) {
        echo "Datos incompletos.";
        exit();
    }

    // Actualizar el estado del pedido
    $query = "UPDATE requisicion SET estado = ? WHERE id_requisicion = ?";
    if ($stmt = $con->prepare($query)) {
        $stmt->bind_param("si", $estado, $id_requisicion);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Error al preparar la consulta de actualización: " . $con->error;
        exit();
    }

    // Si el estado es "Devuelto", restablecer la cantidad de productos en la base de datos
    if ($estado == 'Devuelto') {
        $query = "SELECT id_articulo, cantidad FROM requisiciones_detalles WHERE id_requisicion = ?";
        if ($stmt = $con->prepare($query)) {
            $stmt->bind_param("i", $id_requisicion);
            $stmt->execute();
            $result = $stmt->get_result();

            // Actualizar cantidad de productos
            while ($row = $result->fetch_assoc()) {
                $id_articulo = $row['id_articulo'];
                $cantidad = $row['cantidad'];

                $update_query = "UPDATE articulos SET cantidad = cantidad + ? WHERE id_articulo = ?";
                if ($update_stmt = $con->prepare($update_query)) {
                    $update_stmt->bind_param("ii", $cantidad, $id_articulo);
                    $update_stmt->execute();
                    $update_stmt->close();
                } else {
                    echo "Error al preparar la consulta de actualización de productos: " . $con->error;
                    exit();
                }
            }
            $stmt->close();
        } else {
            echo "Error al preparar la consulta de devolución: " . $con->error;
            exit();
        }
    }

    // Redirigir a la página de historial de pedidos
    header("Location: historial_pedidos.php");
    exit();
}
?>
