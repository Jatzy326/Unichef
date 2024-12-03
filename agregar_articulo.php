<?php

include 'includes/config.php';
include 'includes/_header.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['rutaImagen'])) {
    $error = $_FILES['rutaImagen']['error'];
    if ($error === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['rutaImagen']['tmp_name'];
        $fileName = $_FILES['rutaImagen']['name'];
        $fileSize = $_FILES['rutaImagen']['size'];
        $fileType = $_FILES['rutaImagen']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = ['jpeg', 'jpg', 'png'];
        $maxFileSize = 5 * 1024 * 1024; // 5MB

        if (in_array($fileExtension, $allowedfileExtensions) && $fileSize <= $maxFileSize) {
            $uploadFileDir = './uploaded_images/';
            $nombreArticulo = htmlspecialchars($_POST['nombreArticulo'], ENT_QUOTES, 'UTF-8');
            $descripcion = htmlspecialchars($_POST['descripcion'], ENT_QUOTES, 'UTF-8');
            $nombreCategoria = htmlspecialchars($_POST['nombreCategoria'], ENT_QUOTES, 'UTF-8');
            $cantidadExistente = intval($_POST['cantidadExistente']);
            $randomNumer = rand(1000, 9999);
            $nombreArticuloFormatted = preg_replace('/[^A-Za-z0-9\-]/', '_', $nombreArticulo);
            $newFileName = $nombreArticuloFormatted . '_' . $cantidadExistente . '_' . $randomNumer . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newFileName;

            if (!file_exists($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

          
            $sqlCheck = "SELECT COUNT(*) FROM articulos WHERE nombre = ? AND descripcion = ?";
            if ($stmtCheck = $con->prepare($sqlCheck)) {
                $stmtCheck->bind_param("ss", $nombreArticulo, $descripcion);
                $stmtCheck->execute();
                $stmtCheck->bind_result($count);
                $stmtCheck->fetch();
                $stmtCheck->close();

                if ($count > 0) {
                    $message = "El nombre del artículo ya existe. Por favor, elija un nombre diferente.";
                } else {
                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        // Insertar artículo con estado "Disponible"
                        $estatus = "Disponible"; // Aquí ajusté la variable a $estatus
                        $sql = "INSERT INTO articulos (nombre, descripcion, categoria, cantidad, imagen, estatus) VALUES (?, ?, ?, ?, ?, ?)";
                        if ($stmt = $con->prepare($sql)) {
                            $stmt->bind_param("ssssss", $nombreArticulo, $descripcion, $nombreCategoria, $cantidadExistente, $dest_path, $estatus);
                            if ($stmt->execute()) {
                                $message = "¡Nuevo registro creado con éxito!";
                            } else {
                                $message = "Error al insertar en la base de datos: " . htmlspecialchars($stmt->error, ENT_QUOTES, 'UTF-8');
                            }
                            $stmt->close();
                        } else {
                            $message = "Error al preparar la consulta de inserción.";
                        }
                    } else {
                        $message = 'Error al mover el archivo cargado.';
                    }
                }
            } else {
                $message = "Error al preparar la consulta de verificación.";
            }
        } else {
            if ($fileSize > $maxFileSize) {
                $message = 'El archivo excede el tamaño máximo permitido.';
            } else {
                $message = 'El tipo de archivo cargado no está permitido. Solo se admiten archivos .jpeg, .jpg, .png';
            }
        }
    } else {
        $message = 'Error al cargar el archivo. Código de error: ' . $error;
    }
}

$con->close();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Artículos</title>
    <link rel="stylesheet" href="css/diseño.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <style>
        .form-container {
            position: absolute;
            top: 55%;
            right: 10%;
            transform: translateY(-50%);
            width: 60%;
            padding: 30px;
            border: 2px solid #ddd;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
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

        /* Agrega un fondo para hacer el formulario más visible */
        body {
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2>Agregar Artículo</h2>
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="agregar_articulo.php" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nombreArticulo">Nombre del Artículo</label>
                        <input type="text" class="form-control" id="nombreArticulo" name="nombreArticulo" placeholder="Ingresa nombre del articulo" pattern="[a-zA-Z\s]+" title="El nombre del producto debe contener solo letras y espacios" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <input type="text" id="descripcion" placeholder="Ingresa la descripción del articulo" name="descripcion" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="nombreCategoria">Categoría</label>
                        <select class="custom-select" name="nombreCategoria" required>
                            <option selected disabled value="">Seleccionar Categoría...</option>
                            <option>Platos</option>
                            <option>Sartenes</option>
                            <option>Cubiertos</option>
                            <option>Vasos</option>
                            <option>Tasas de Medir</option>
                            <option>Utencilios para Hornear</option>
                            <option>Bowls para Mezclar</option>
                            <option>Electronicos</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cantidadExistente">Cantidad</label>
                        <input type="number" class="form-control" id="cantidadExistente" placeholder="Ingresa la cantidad " name="cantidadExistente" required min="1" max="500">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="rutaImagen">Carga Imagen (Admite solo .png, .jpeg y .jpg)</label>
                        <input type="file" class="form-control-file" id="rutaImagen" name="rutaImagen" accept="image/png, image/jpeg" required>
                    </div>
                    <div class="image-preview" id="imagePreview">
                        <img src="" alt="Vista Previa de la Imagen" id="previewImg">
                        <span id="previewText">Vista Previa de la Imagen</span>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Agregar</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

<script>
    document.getElementById("rutaImagen").addEventListener("change", function(){
        const reader = new FileReader();
        reader.onload = function(e) {
            const uploaded_image = e.target.result;
            document.getElementById("previewImg").src = uploaded_image;
            document.getElementById("previewImg").style.display = "block";
            document.getElementById("previewText").style.display = "none";
        };
        reader.readAsDataURL(this.files[0]);
    });
</script>

</body>
</html>
