<?php 
    include 'includes/_header.php'; 
    include 'includes/config.php';


    $selectedStatus = isset($_GET['estatus']) ? $_GET['estatus'] : '';

    $sql = "SELECT id_articulo, nombre, categoria, cantidad, descripcion, imagen, detalle FROM articulos";
    if ($selectedStatus) {
        $sql .= " WHERE estatus = ?";
    }

    $stmt = $con->prepare($sql);
    if ($selectedStatus) {
        $stmt->bind_param("s", $selectedStatus);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $articulos = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $con->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/categoria.css">
    <title>Artículos Perdidos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <?php
     
        $estatus = isset($_GET['estatus']) ? $_GET['estatus'] : '';

    
        switch ($estatus) {
            case 'Perdido':
                $titulo = 'Artículos Perdidos';
                $pdfLink = 'fpdf/fpdf186/print_per.php';
                break;
            default:
                $titulo = 'Artículos Dañados';
                $pdfLink = 'fpdf/fpdf186/print_per.php'; 
                break;
        }
        ?>

        <h2><?php echo $titulo; ?></h2>

        <a href="<?php echo htmlspecialchars($pdfLink, ENT_QUOTES, 'UTF-8'); ?>?estatus=<?php echo htmlspecialchars($selectedStatus, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-primary mb-3">Imprimir PDF <i class='fas fa-print'></i></a>

        <table id="tablaArticulos" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
            <thead class="bg-dark text-white text-center">
                <tr>
                    <th>No</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Cantidad</th> 
                    <th>Descripción</th>
                    <th>Imagen</th>
                    <th>Detalle</th>
                    <th>Acciones</th>     
                </tr>                   
            </thead>
            <tbody class="text-center">
                <?php if ($articulos): ?>
                    <?php foreach ($articulos as $articulo): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($articulo['id_articulo'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($articulo['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($articulo['categoria'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($articulo['cantidad'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($articulo['descripcion'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><img src="<?php echo htmlspecialchars($articulo['imagen'], ENT_QUOTES, 'UTF-8'); ?>" alt="Imagen del Artículo" style="width: 100px; height: auto;"></td>
                            <td><?php echo htmlspecialchars($articulo['detalle'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <a href='edit_art.php?id_articulo=<?php echo htmlspecialchars($articulo['id_articulo'], ENT_QUOTES, 'UTF-8'); ?>' class='btn btn-warning btn-sm'>Editar</a>
                                <a href='elim_art.php?id_articulo=<?php echo htmlspecialchars($articulo['id_articulo'], ENT_QUOTES, 'UTF-8'); ?>' class='btn btn-danger btn-sm' onclick='return confirm("¿Está seguro de que desea eliminar este artículo?");'>Eliminar</a>
                                <a href='report_art.php?id_articulo=<?php echo htmlspecialchars($articulo['id_articulo'], ENT_QUOTES, 'UTF-8'); ?>' class='btn btn-info btn-sm'>Reportar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan='8'> No se encontraron registros de artículos perdidos</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>  
    <div class="p-3"></div>

    <script>
        $(document).ready(function(){
            $('#tablaArticulos').DataTable({
                responsive: true,
                "language": {
                    "search": "Buscar:",
                    "searchPlaceholder": "Filtrar por columna...",
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontraron registros",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrados de un total de _MAX_ registros)",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]]
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JmNf29wTxL4CxuWJ1XbOeQn8p3sQxaChP9Ql+gT10APOUZlDOV+QFCbbk3Po" crossorigin="anonymous"></script>
</body>
</html>
