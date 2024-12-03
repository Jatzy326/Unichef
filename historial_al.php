<?php
session_start();
include 'includes/config.php';
include 'includes/_header.php';

// Consultar el historial de pedidos
$query = "SELECT id_articulo, nombre_articulo, cantidad, fecha_orden, regreso_fecha FROM historial_pedidos ORDER BY fecha_orden DESC";
$result = $con->query($query);

if (!$result) {
    die("Error en la consulta: " . $con->error);
}
?>
<style>
.hol {
    position: fixed;
    top: 20px; /* Ajusta según la altura de tu header */
    right: 110px; /* Ajusta el espacio desde el borde derecho */
    width: 1200px; /* Ajusta el ancho del carrito */
    padding: 50px;
    border-radius: 8px;
    z-index: 1000;
    overflow-y: auto;
    max-height: calc(200vh - 200px); /* Altura máxima para no cubrir todo el contenido */
}
</style>

<div class="hol container-fluid mt-5">
    <div class="row">
        <div class="col-3"></div> <!-- Columna vacía para dejar espacio a la derecha -->
        <div class="col-9"> <!-- Ajusta el contenido hacia la izquierda -->
            <h2>Historial de Pedidos</h2>
            <?php if ($result->num_rows > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID Artículo</th>
                            <th>Nombre del Artículo</th>
                            <th>Cantidad</th>
                            <th>Fecha de Orden</th>
                            <th title="Esta es la fecha límite para devolver el material">Fecha de Regreso</th> <!-- Tooltip aquí -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id_articulo']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre_articulo']); ?></td>
                                <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_orden']); ?></td>
                                <td><?php echo htmlspecialchars($row['regreso_fecha']); ?></td> <!-- Mostrar regreso_fecha -->
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay pedidos registrados.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$con->close();
?>
