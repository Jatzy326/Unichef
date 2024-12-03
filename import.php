<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Archivo Excel</title>
</head>
<body>
<h1>Exportar e importar datos desde CSV</h1>
<h2>Importar datos</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        Seleccione el archivo Excel
        <input type="file" name="file" accept=".xlsx">
        <input type="submit" value="Subir Archivo" name="submit">
    </form>

    <h2>Exportar datos desde la bd en formato CSV</h2>
    <form action="exportar.php">
        <button type="submit">Exportar datos</button>
    </form>
</body>
</html>