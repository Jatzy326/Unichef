<?php
include 'includes/config.php';
include 'includes/_header.php';

// Inicializar el mensaje como vacío
$message = '';

if (isset($_POST['updateCuatrimestreGrupo'])) {
    $grupo = $_POST['grupo'];
    $nuevoCuatrimestre = $_POST['nuevoCuatrimestre'];
    $nuevoGrupo = $_POST['nuevoGrupo'];

    if (!empty($grupo) && !empty($nuevoCuatrimestre) && !empty($nuevoGrupo)) {
        $stmt = $con->prepare("UPDATE admins SET cuatrimestre = ?, grupo = ? WHERE role = 'alumno' AND grupo = ?");
        $stmt->bind_param("sss", $nuevoCuatrimestre, $nuevoGrupo, $grupo);

        if ($stmt->execute()) {
            $message = "Cuatrimestre y grupo actualizados exitosamente para todos los alumnos del grupo $grupo.";
        } else {
            $message = "Error al actualizar cuatrimestre y grupo: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Debe seleccionar un grupo, cuatrimestre y nuevo grupo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Cuatrimestre y Grupo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        .form-container {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 50%;
            margin: 90px auto;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="form-container">
        <h2>Actualizar Cuatrimestre y Grupo</h2>

        <!-- Mensaje de confirmación -->
        <?php if ($message): ?>
            <div class="alert alert-info">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <!-- Selección de Grupo -->
            <div class="form-group">
                <label for="grupo">Seleccionar Grupo:</label>
                <select name="grupo" id="grupo" class="form-control" required onchange="filtrarGrupos()">
                    <option value="">Seleccione un grupo</option>
                    <?php
                    $grupos = $con->query("SELECT DISTINCT grupo FROM admins WHERE role = 'alumno'");
                    while ($row = $grupos->fetch_assoc()) {
                        echo "<option value='{$row['grupo']}'>{$row['grupo']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Selección de Cuatrimestre -->
            <div class="form-group">
                <label for="nuevoCuatrimestre">Cuatrimestre:</label>
                <select class="form-control" name="nuevoCuatrimestre" required>
                    <option value="" selected disabled>Seleccionar cuatrimestre...</option>
                    <?php
                    $currentYear = date("Y");
                    ?>
                    <option>SEP-DIC <?php echo $currentYear; ?></option>
                    <option>ENE-ABR <?php echo $currentYear + 1; ?></option>
                    <option>MAY-AGO <?php echo $currentYear + 1; ?></option>
                    <option>SEP-DIC <?php echo $currentYear + 1; ?></option>
                </select>
            </div>

            <!-- Selección de Nuevo Grupo -->
            <div class="form-group">
                <label for="nuevoGrupo">Nuevo Grupo:</label>
                <select name="nuevoGrupo" id="nuevoGrupo" class="form-control" required>
                    <option value="">Seleccione un nuevo grupo</option>
                </select>
            </div>

            <!-- Botón de envío -->
            <div class="form-group text-right">
                <button type="submit" name="updateCuatrimestreGrupo" class="btn btn-primary">Actualizar Cuatrimestre y Grupo</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Lista de grupos
    const grupos = [
        "GA1-1", "GA1-2", "GA1-3", "GA2-1", "GA2-2", "GA2-3", "GA3-1", "GA3-2", "GA3-3", "GA4-1",
        "GA4-2", "GA4-3", "GA5-1", "GA5-2", "GA5-3", "6to - Estadías", "GA7-1", "GA7-2", "GA8-1", "GA8-2",
        "GA9-1", "GA9-2", "GA10-1", "GA10-2", "11avo - Estadías"
    ];

    // Filtrar grupos según el grupo seleccionado
    function filtrarGrupos() {
        const grupoSeleccionado = document.getElementById('grupo').value;
        const nuevoGrupoSelect = document.getElementById('nuevoGrupo');
        nuevoGrupoSelect.innerHTML = "<option value=''>Seleccione un nuevo grupo</option>";

        let mostrarGrupos = false;

        grupos.forEach(grupo => {
            if (grupo === grupoSeleccionado) {
                mostrarGrupos = true;
            }
            if (mostrarGrupos) {
                const option = document.createElement('option');
                option.value = grupo;
                option.textContent = grupo;
                nuevoGrupoSelect.appendChild(option);
            }
        });
    }
</script>
</body>
</html>
