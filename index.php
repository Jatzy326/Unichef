<?php
session_start();

// Incluimos la conexión a la BD
include_once('includes/config.php'); 

if (isset($_POST['matricula']) && isset($_POST['password'])) { 
    $errorMsg = ""; 
    $matricula = $con->real_escape_string($_POST['matricula']); 
    $password = $con->real_escape_string($_POST['password']); 

    if (!empty($matricula) && !empty($password)) { 
        // Consulta SQL actualizada para obtener todos los campos necesarios
        $stmt = $con->prepare("SELECT id_admins, role, matricula, username, grupo, cuatrimestre, estatus, password FROM admins WHERE matricula = ?"); 
        $stmt->bind_param("s", $matricula); 
        $stmt->execute(); 
        $result = $stmt->get_result(); 

        if ($result->num_rows > 0) { 
            $row = $result->fetch_assoc(); 

            if (password_verify($password, $row['password'])) { 
                // Guardamos todos los datos en la sesión
                $_SESSION['ID_ADMINS'] = $row['id_admins']; 
                $_SESSION['ROLE'] = $row['role']; 
                $_SESSION['MATRICULA'] = $row['matricula']; 
                $_SESSION['USERNAME'] = $row['username']; 
                $_SESSION['GRUPO'] = $row['grupo']; 
                $_SESSION['CUATRIMESTRE'] = $row['cuatrimestre']; 
                $_SESSION['ESTATUS'] = $row['estatus']; 

                // Redirigir al menú principal
                header("Location: menu.php");
                exit();
            } else { 
                $errorMsg = "La contraseña no es válida."; 
            } 
        } else { 
            $errorMsg = "Su matrícula no se encuentra en nuestro sistema."; 
        } 
    } else { 
        $errorMsg = "El campo de matrícula y contraseña son obligatorios."; 
    } 
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="img/logo.jpg" rel="icon">
    <title>UniChef</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .form {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn {
            background-color: #660000;
            color: white;
            border: none;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #28a745;
        }
        .container {
            max-width: 400px;
            margin: auto;
            margin-top: 100px;
        }
        h1 {
            font-size: 28px;
            color: #660000;
            text-align: center;
        }
        label {
            font-weight: bold;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php if (isset($errorMsg)) { ?>
            <div class="alert alert-danger">
                <?php echo $errorMsg; ?>
            </div>
        <?php } ?>

        <form action="" method="POST" class="form">
            <h1>Iniciar sesión</h1>
            <div class="form-group">
                <label for="matricula">Usuario</label>
                <input type="text" class="form-control" name="matricula" placeholder="Ingresar el Usuario" required>
            </div>
            <br>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" name="password" placeholder="Ingresar la contraseña" required>
            </div>
                        <br>

            <button type="submit" class="btn mt-3">¡Entrar!</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
