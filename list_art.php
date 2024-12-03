<?php 
    include 'includes/_header.php'; 
    include 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articulos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">

    <!-- DataTables Responsive CSS-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">

    <!-- DataTables JS-->
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <!-- DataTables Responsive -->
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap.min.js"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<style>
    body {
        display: flex;
        justify-content: flex-end;
        align-items: flex-start;
        margin: 0;
        padding: 0;
    }

    .container {
        margin-right: 55px; /* Reduce el margen lateral derecho */
        max-width: 95%; /* Incrementa el ancho del contenedor */
    }

    h2 {
        margin-bottom: 20px;
    }

    table {
        width: 90%; /* Asegúrate de que la tabla ocupe todo el espacio disponible */
        table-layout: auto; /* Permite que las columnas se ajusten dinámicamente */
    }

    th, td {
        word-wrap: break-word; /* Asegura que el texto no desborde las columnas */
    }

    img {
        max-width: 100px; /* Mantén un tamaño consistente para las imágenes */
        height: auto;
    }
</style>
</head>

<body>
    <div class="container">
        <div class="content">
            <h2>Total General de Productos</h2>        
            <a href="fpdf/fpdf186/print_art.php" class="btn btn-primary mb-3">Imprimir PDF <i class='fas fa-print'></i></a>
            <a href="exp_inventario.php" class="btn btn-primary mb-3">Descargar CSV <i class="bi bi-box-arrow-in-down"></i></a>

            <table id="tablaArticulos" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead class="bg-dark text-white text-center">
                    <tr>
                        <th>No</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Cantidad</th> 
                        <th>Descripción</th>
                        <th>Imagen</th>
                        <th>Acciones</th>     
                    </tr>                   
                </thead>
                <tbody class="text-center">
                    <?php
                        include_once("includes/config.php");

                        $query = "SELECT id_articulo, nombre, categoria, cantidad, descripcion, imagen FROM articulos";
                        $result = $con->query($query);

                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                <td>{$row['id_articulo']}</td>
                                <td>{$row['nombre']}</td>
                                <td>{$row['categoria']}</td>
                                <td>{$row['cantidad']}</td>
                                <td>{$row['descripcion']}</td>
                                <td><img src='{$row['imagen']}' alt='{$row['nombre']}' style='width:100px; height:auto;'></td>
                                <td>
                                    <a href='edit_art.php?id_articulo={$row['id_articulo']}' class='btn btn-warning btn-sm'>Editar</a>
                                    <a href='elim_art.php?id_articulo={$row['id_articulo']}' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Está seguro de que desea eliminar este artículo?\");'>Eliminar</a>
                                    <a href='report_art.php?id_articulo={$row['id_articulo']}' class='btn btn-info btn-sm'>Reportar</a>
                                </td>
                            </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'> No se encontraron registros</td> </tr>";
                        }

                        $con->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('#tablaArticulos').DataTable({
                responsive: true,
                "language": {
                    "search": "Buscar:",
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
</body>
</html>
