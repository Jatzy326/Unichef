<?php
// Incluye el archivo de configuración de la base de datos
include_once 'includes/config.php';
include_once 'includes/_header.php';

// Verifica si la sesión está iniciada
if (!isset($_SESSION['ID_ADMINS'])) {
    header("Location: index.php");
    exit();
}

$errorMsg = "";
$successMsg = "";

// Verifica si se envió el formulario de cambio de contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $con->real_escape_string($_POST['current_password']);
    $newPassword = $con->real_escape_string($_POST['new_password']);
    $confirmPassword = $con->real_escape_string($_POST['confirm_password']);

    // Consulta para obtener la contraseña actual del usuario
    $stmt = $con->prepare("SELECT password FROM admins WHERE id_admins = ?");
    $stmt->bind_param("i", $_SESSION['ID_ADMINS']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verifica la contraseña actual
    if (password_verify($currentPassword, $user['password'])) {
        if ($newPassword === $confirmPassword) {
            // Cifra la nueva contraseña
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Actualiza la contraseña en la base de datos
            $updateStmt = $con->prepare("UPDATE admins SET password = ? WHERE id_admins = ?");
            $updateStmt->bind_param("si", $hashedPassword, $_SESSION['ID_ADMINS']);
            if ($updateStmt->execute()) {
                $successMsg = "Contraseña actualizada correctamente.";
            } else {
                $errorMsg = "Hubo un error al actualizar la contraseña.";
            }
        } else {
            $errorMsg = "La nueva contraseña y la confirmación no coinciden.";
        }
    } else {
        $errorMsg = "La contraseña actual es incorrecta.";
    }
}

// Consulta SQL para obtener los datos del usuario actual
$sql = "SELECT * FROM admins WHERE id_admins = " . $_SESSION['ID_ADMINS'];
$result = $con->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/diseño.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link href="img/logo.jpg" rel="icon">
    <title>Unichef</title>
</head>
<body>
    <div class="container caja mover">
        <div class="main"> 
            <div class="row">        
                <div class="col-md-8 nt-1">
                    <div class="card mb-3 content">
                        <h1 class="m-3 pt-3">Perfil</h1>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <h5>Nombre</h5>
                                </div>
                                <div class="col-md-9 text-secondary">
                                    <?php echo $row['username']; ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <h5>Email</h5>
                                </div>
                                <div class="col-md-9 text-secondary">
                                    <?php echo $row['email']; ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <h5>Rol</h5>
                                </div>
                                <div class="col-md-9 text-secondary">
                                    <?php echo $row['role']; ?>
                                </div>
                            </div>
                            <hr>
                            <h3>Cambiar contraseña</h3>
                            <?php if ($errorMsg) : ?>
                                <div class="alert alert-danger"><?php echo $errorMsg; ?></div>
                            <?php endif; ?>
                            <?php if ($successMsg) : ?>
                                <div class="alert alert-success"><?php echo $successMsg; ?></div>
                            <?php endif; ?>
                            <form method="POST">
                                <div class="form-group">
                                    <label for="current_password">Contraseña actual</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                </div>
                                <div class="form-group">
                                    <label for="new_password">Nueva contraseña</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Confirmar nueva contraseña</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Cambiar contraseña</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
