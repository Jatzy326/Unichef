<?php
include 'includes/config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $nombreArticulo = htmlspecialchars($_POST['nombreArticulo'], ENT_QUOTES, 'UTF-8');
    $cantidad = intval($_POST['cantidad']);
    $imagen = htmlspecialchars($_POST['imagen'], ENT_QUOTES, 'UTF-8');

    // Verificar si el nombre ya existe excluyendo el artículo actual
    $sqlCheck = "SELECT COUNT(*) FROM articulos WHERE nombre = ? AND id_articulo != ?";
    if ($stmtCheck = $con->prepare($sqlCheck)) {
        $stmtCheck->bind_param("si", $nombreArticulo, $id);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();

        if ($count > 0) {
            $message = "El nombre del artículo ya existe. Por favor, elija un nombre diferente.";
        } else {
            // Proceder con la actualización
            $sql = "UPDATE articulos SET nombre = ?, cantidad = ?, imagen = ? WHERE id_articulo = ?";
            if ($stmt = $con->prepare($sql)) {
                $stmt->bind_param("sisi", $nombreArticulo, $cantidad, $imagen, $id);
                if ($stmt->execute()) {
                    $message = "Artículo actualizado con éxito.";
                } else {
                    $message = "Error al actualizar el artículo: " . htmlspecialchars($stmt->error, ENT_QUOTES, 'UTF-8');
                }
                $stmt->close();
            } else {
                $message = "Error al preparar la consulta de actualización.";
            }
        }
    } else {
        $message = "Error al preparar la consulta de verificación.";
    }
} else if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM articulos WHERE id_articulo = ?";
    if ($stmt = $con->prepare($query)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $nombre = $row['nombre'];
            $cantidad = $row['cantidad'];
            $imagen = $row['imagen'];
        } else {
            echo "Artículo no encontrado.";
            exit;
        }
        $stmt->close();
    } else {
        echo "Error al preparar la consulta.";
        exit;
    }
} else {
    echo "ID de artículo no proporcionado.";
    exit;
}

$con->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Artículo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <style>
        .form-container {
            float: right; /* Mueve el formulario a la derecha */
            max-width: 600px;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-left: 20px; /* Espacio izquierdo para no pegar el formulario al borde */
            margin-top: 20px;  /* Espacio superior */
        }

        .form-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-wrapper {
            margin: 20px; /* Ajusta el espacio alrededor del formulario */
        }
    </style>
</head>
<body>
    <?php include 'includes/_header.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4 form-title">Editar Artículo</h2>
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <div class="form-wrapper">
            <div class="form-container">
                <form action="editar_articulo.php" method="POST">
                    <div class="form-group">
                        <label for="nombreArticulo">Nombre del Artículo:</label>
                        <input type="text" class="form-control" id="nombreArticulo" name="nombreArticulo" pattern="[a-zA-Z\s]+" placeholder="Nombre del Artículo" value="<?php echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="cantidad">Cantidad:</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="Cantidad" value="<?php echo htmlspecialchars($cantidad, ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="imagen">URL de la Imagen:</label>
                        <input type="text" class="form-control" id="imagen" name="imagen" placeholder="URL de la Imagen" value="<?php echo htmlspecialchars($imagen, ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <a href="rejilla.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
