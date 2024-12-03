<?php
include 'includes/config.php';
include 'includes/_header.php';


// Verificar si hay un mensaje en la sesión
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
// Limpiar el mensaje de la sesión después de mostrarlo
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total de Alumnos</title>
    <link rel="stylesheet" href="css/diseño.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">
</head>
<body>
    <div class="container caja mt-5">
        <h2 style="color: #000;">Total General de Alumnos</h2>

        <!-- Mensaje de confirmación -->
        <?php if ($message): ?>
            <div class="alert alert-info">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>


        <table id="tablaAlumnos" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
            <thead class="bg-dark text-white text-center">
                <tr>
                    <th>No</th>
                    <th>Matrícula</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Grupo</th>
                    <th>Cuatrimestre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php
                // Consulta a la base de datos
                $query = "SELECT id, matricula, username, email, grupo, cuatrimestre FROM admins WHERE role = 'alumno'";
                $result = $con->query($query);

                if ($result && $result->num_rows > 0) {
                    $no = 1; // Variable para el número de fila
                    // Mostrar los datos de cada fila
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$no}</td>
                                <td>{$row['matricula']}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['grupo']}</td>
                                <td>{$row['cuatrimestre']}</td>
                                <td>
                                    <a href='edit_almn.php?id={$row['id']}' class='btn btn-warning btn-sm'>Editar</a>
                                    <a href='elim_almn.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Está seguro de que desea eliminar este alumno?\");'>Eliminar</a>
                                    <a href='fpdf/fpdf186/requisicion.php?id={$row['id']}' class='btn btn-primary' role='button'>Imprimir <i class='fas fa-print'></i></a>
                                </td>
                            </tr>";
                        $no++; // Incrementar el número de fila
                    }
                } else {
                    echo "<tr><td colspan='7'> No se encontraron registros</td></tr>";
                }

                // Cerrar la conexión
                $con->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <!-- DataTables JS-->
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <!-- DataTables Responsive -->
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap.min.js"></script>

    <!-- Inicializar DataTables -->
    <script>
        $(document).ready(function() {
            $('#tablaAlumnos').DataTable({
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

    <!-- Popper.js y Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
