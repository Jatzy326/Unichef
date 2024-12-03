<?php
ob_start();
include 'includes/config.php';
include 'includes/_header.php';

if (isset($_GET['id_admins'])) {
    $id_admins = $_GET['id_admins'];

    $consulta = "SELECT * FROM admins WHERE id_admins = $id_admins";
    $resultado = mysqli_query($con, $consulta);
    $encargado = mysqli_fetch_assoc($resultado);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = mysqli_real_escape_string($con, $_POST['username']);
        $matricula = mysqli_real_escape_string($con, $_POST['matricula']);
        $email = mysqli_real_escape_string($con, $_POST['email']);

        // Validaciones
        $checkUsername = "SELECT * FROM admins WHERE username = '$username' AND id_admins != $id_admins";
        $resultUsername = mysqli_query($con, $checkUsername);
        $countUsername = mysqli_num_rows($resultUsername);

        $checkMatricula = "SELECT * FROM admins WHERE matricula = '$matricula' AND id_admins != $id_admins";
        $resultMatricula = mysqli_query($con, $checkMatricula);
        $countMatricula = mysqli_num_rows($resultMatricula);

        if ($countUsername > 0) {
            echo "<script>alert('Ese nombre ya está en nuestra base de datos.'); window.location.href = 'r_encg.php';</script>";
        } elseif ($countMatricula > 0) {
            echo "<script>alert('Ese número de trabajador ya está en nuestra base de datos.'); window.location.href = 'r_encg.php';</script>";
        } else {
            $updateQuery = "UPDATE admins SET username = '$username', matricula = '$matricula', email = '$email' WHERE id_admins = $id_admins";

            if (mysqli_query($con, $updateQuery)) {
                header("Location: list_encg.php");
                exit();
            } else {
                echo "Error al actualizar el encargado: " . mysqli_error($con);
            }
        }
    }
} else {
    echo "ID de encargado no proporcionado.";
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Encargado</title>
    <link rel="stylesheet" href="css/diseño.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.0/css/all.min.css">
    <style>
        .form-container {
            position: absolute;
            top: 40%;
            left: 60%;
            transform: translate(-50%, -50%);
            width: 700px;
            max-width: 90%;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(51, 51, 51, 0.8);
        }
        @media (max-width: 768px) {
            .form-container {
                top: 70px;
                width: 90%;
                left: 50%;
                transform: translateX(-50%);
            }
        }
    </style>
</head>
<body>
    <div class="container caja mt-5">
        <br>
        <div class="form-container">
        <h2 style="color: #000;">Editar Encargado</h2>
        <form action="" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="username">Nombre del Encargado:</label>
                        <input type="text" class="form-control" id="username" name="username" 
                            value="<?php echo htmlspecialchars($encargado['username']); ?>" 
                            pattern="[a-zA-Z\s]+" title="El nombre debe contener solo letras y espacios" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="matricula">Número de trabajador:</label>
                        <input type="number" class="form-control" id="matricula" name="matricula" 
                            value="<?php echo htmlspecialchars($encargado['matricula']); ?>" 
                            min="1" required>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control font-weight-bold" id="email" name="email" 
                            value="<?php echo htmlspecialchars($encargado['email']); ?>" required>
                    </div>
                    <br>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>
        </div>
    </div>    

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var inputMatricula = document.getElementById('matricula');
        var inputEmail = document.getElementById('email');

        inputMatricula.addEventListener('input', function () {
            var matriculaValue = this.value.trim();

            if (matriculaValue) {
                inputEmail.value = matriculaValue + '@utcgg.edu.mx';
            } else {
                inputEmail.value = '';
            }
        });
    });
    </script>
</body>
</html>
