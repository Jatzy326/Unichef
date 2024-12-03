<?php 
include 'includes/_header.php'; 
include 'includes/config.php';

// Validar el estatus seleccionado
$selectedStatus = isset($_GET['estatus']) && in_array($_GET['estatus'], ['Donado', 'Perdido', 'Dañado']) ? $_GET['estatus'] : '';

// Construir la consulta SQL
$sql = "SELECT r.estatus, r.detalle, r.cantidad, a.id_articulo, a.nombre, a.categoria, a.descripcion, a.imagen 
        FROM reportar r
        JOIN articulos a ON r.id_articulo = a.id_articulo";

if ($selectedStatus) {
    $sql .= " WHERE r.estatus = ?";
}

$stmt = $con->prepare($sql);
if ($selectedStatus) {
    $stmt->bind_param("s", $selectedStatus);
}
$stmt->execute();
$result = $stmt->get_result();
$reportados = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$con->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artículos Reportados</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap.min.js"></script>

    <style>
    .container {
        margin-right: 0px;
        max-width: 80%;
    }

    .content {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .table-container {
        margin: 20px;
        margin-top: 10px;
    }
</style>

</head>
<body>
    <div class="container"><br><br>
        <div class="content">
            <?php
            $estatus = $selectedStatus ?: 'Disponible';
            $titulo = match ($estatus) {
                'Perdido' => 'Artículos Reportados como Perdidos',
                default => 'Artículos Reportados',
            };
            ?>
            
            <h2 class="text-center my-4"><?php echo $titulo; ?></h2>
            <a href="fpdf/fpdf186/print_per.php" class="btn btn-primary mb-3">Imprimir <i class='fas fa-print'></i></a>

            <div class="table-container">
                <table id="tablaReportados" class="table table-striped table-bordered dt-responsive nowrap">
                    <thead class="bg-dark text-white text-center">
                        <tr>
                            <th>No</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Cantidad</th>
                            <th>Descripción</th>
                            <th>Detalle</th>
                            <th>Estatus</th>
                            <th>Imagen</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php if ($reportados): ?>
                            <?php foreach ($reportados as $articulo): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($articulo['id_articulo'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($articulo['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($articulo['categoria'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($articulo['cantidad'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($articulo['descripcion'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($articulo['detalle'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($articulo['estatus'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><img src="<?php echo htmlspecialchars($articulo['imagen'], ENT_QUOTES, 'UTF-8'); ?>" alt="Imagen del Artículo" style="width: 100px; height: auto;"></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="8">No se encontraron registros de artículos reportados.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#tablaReportados').DataTable({
                responsive: true,
                language: {
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros por página",
                    zeroRecords: "No se encontraron registros",
                    info: "Mostrando página _PAGE_ de _PAGES_",
                    infoEmpty: "No hay registros disponibles",
                    infoFiltered: "(filtrados de un total de _MAX_ registros)",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                },
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]]
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
</body>
</html>
