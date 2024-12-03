<?php
session_start();
include 'includes/config.php';
include 'includes/_header.php';

$message = '';

// Verificar si se proporcionó un ID válido
if (isset($_GET['id_articulo'])) {
    $id_articulo = intval($_GET['id_articulo']);
    
    // Obtener los datos del artículo para prellenar el formulario
    $sqlGetArticulo = "SELECT * FROM articulos WHERE id_articulo = ?";
    if ($stmtGet = $con->prepare($sqlGetArticulo)) {
        $stmtGet->bind_param("i", $id_articulo);
        $stmtGet->execute();
        $result = $stmtGet->get_result();
        $articulo = $result->fetch_assoc();
        $stmtGet->close();
    } else {
        $message = "Error al obtener los datos del artículo.";
    }
}

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($id_articulo)) {
    $nombreArticulo = htmlspecialchars($_POST['nombreArticulo'], ENT_QUOTES, 'UTF-8');
    $cantidadReportada = intval($_POST['cantidadReportada']);
    $estatus = htmlspecialchars($_POST['estatus'], ENT_QUOTES, 'UTF-8');
    $detalle = htmlspecialchars(trim($_POST['detalle']), ENT_QUOTES, 'UTF-8');
    

    // Validar cantidad
    if (!isset($articulo['cantidad']) || !is_numeric($articulo['cantidad'])) {
        $message = "Error al obtener la cantidad existente del artículo.";
    } elseif ($cantidadReportada <= 0) {
        $message = "La cantidad reportada no es válida.";
    } else {
        // Iniciar transacción para asegurarse de que ambas operaciones se realicen correctamente
        $con->begin_transaction();

        try {
            // Usar ON DUPLICATE KEY UPDATE para insertar o actualizar la tabla reportar
            $sqlInsertOrUpdateReport = "
                INSERT INTO reportar (id_articulo, estatus, cantidad, detalle)
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                    cantidad = cantidad + VALUES(cantidad), 
                    detalle = VALUES(detalle)";
            
            if ($stmtReport = $con->prepare($sqlInsertOrUpdateReport)) {
                $stmtReport->bind_param("isis", $id_articulo, $estatus, $cantidadReportada, $detalle);
                if (!$stmtReport->execute()) {
                    throw new Exception("Error al registrar o actualizar el reporte.");
                }
                $stmtReport->close();
            }

            // Restar la cantidad reportada de la tabla `articulos`
            $newCantidadArticulo = $articulo['cantidad'] - $cantidadReportada;
            if ($newCantidadArticulo < 0) {
                throw new Exception("No hay suficiente cantidad de este artículo en stock.");
            }

            $sqlUpdateArticulo = "UPDATE articulos SET cantidad = ? WHERE id_articulo = ?";
            if ($stmtUpdateArticulo = $con->prepare($sqlUpdateArticulo)) {
                $stmtUpdateArticulo->bind_param("ii", $newCantidadArticulo, $id_articulo);
                if (!$stmtUpdateArticulo->execute()) {
                    throw new Exception("Error al actualizar la cantidad del artículo.");
                }
                $stmtUpdateArticulo->close();
            }

            // Si todo es correcto, realizar commit de la transacción
            $con->commit();

            $message = "¡Reporte registrado y cantidad actualizada con éxito!";
            $redirectTo = ($estatus == 'Perdido') ? 'list_art.php' : 'list_art.php';
            echo "<script>window.location.href = '$redirectTo';</script>";
            exit;

        } catch (Exception $e) {
            // En caso de error, realizar rollback
            $con->rollback();
            $message = "Error: " . $e->getMessage();
        }
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportar Artículo</title>
    <link rel="stylesheet" href="css/diseño.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <style>
        .caja {
            margin-right: 150px;
        }
        .image-preview {
            width: 200px;
            height: 200px;
            border: 2px dashed #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #ccc;
            margin-top: 15px; 
            overflow: hidden;
        }
        .image-preview img {
            display: none;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>
<br><br>
<div class="container mt-5"> 
    <div class="row justify-content-end">
        <div class="col-md-6 caja">
            <div class="card p-4 shadow-sm">
                <h2 class="mb-4">Reportar Artículo</h2>

                <?php if ($message): ?>
                    <div class="alert alert-info"><?php echo $message; ?></div>
                <?php endif; ?>

                <form action="report_art.php?id_articulo=<?php echo $id_articulo; ?>" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombreArticulo">Nombre del Artículo</label>
                                <input type="text" class="form-control" id="nombreArticulo" name="nombreArticulo" value="<?php echo htmlspecialchars($articulo['nombre'], ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="cantidadExistente">Cantidad Existente</label>
                                <input type="number" class="form-control" id="cantidadExistente" name="cantidadExistente" value="<?php echo htmlspecialchars($articulo['cantidad'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="cantidadReportada">Cantidad Reportada</label>
                                <input type="number" class="form-control" id="cantidadReportada" name="cantidadReportada" value="" required min="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="estatus">Estatus</label>
                                <select class="custom-select" id="estatus" name="estatus"  required>
                                    <option value="Dañado" <?php if ($articulo['estatus'] == 'Dañado') echo 'selected'; ?>>Dañado</option>
                                    <option value="Perdido" <?php if ($articulo['estatus'] == 'Perdido') echo 'selected'; ?>>Perdido</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="detalle">Detalle</label>
                                <textarea class="form-control" id="detalle" name="detalle" rows="4" required><?php echo isset($articulo['detalle']) ? htmlspecialchars($articulo['detalle'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                            </div>

                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Reportar</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
