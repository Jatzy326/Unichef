<?php
// Incluir la configuración de la conexión a la base de datos
include 'includes/config.php';

// Nombre de la tabla en la que se importan los datos crudos
$tablaName = "articulos";

// Obtener los datos de la tabla
$query = "SELECT nombre, descripcion, categoria, cantidad, imagen FROM $tablaName";
if (!$resul = $con->query($query)) {
    die("Error en la consulta SQL: " . $con->error);
}

// Verificar los resultados
if ($resul->num_rows > 0) {
    // Nombre del archivo CSV en el modo escritura
    $filename = "inventario.csv";

    // Abrir el archivo CSV en el modo escritura
    $file = fopen($filename, 'w');

    // Escribir las líneas de encabezado en el archivo CSV
    fputcsv($file, array('nombre', 'descripcion', 'categoria', 'cantidad', 'imagen'));

    // Iterar sobre los resultados y escribir cada fila en el archivo CSV
    while ($row = $resul->fetch_assoc()) {
        fputcsv($file, $row);
    }

    // Cerrar el archivo CSV
    fclose($file);

    // Establecer los encabezados para la descarga del archivo
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Leer y enviar el archivo CSV al navegador
    readfile($filename);

    // Eliminar el archivo CSV
    unlink($filename);
} else {
    echo "No hay datos para descargar";
}

// Cerrar la conexión
$con->close();
?>
