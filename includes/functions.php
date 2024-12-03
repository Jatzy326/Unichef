<?php 
include '_header.php'; 
include 'config.php';

// Check if a valid ID and role have been sent
if (isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['role']) && !empty($_GET['role'])) {
    $id = intval($_GET['id']); // Sanitize the input to ensure it is an integer
    $role = $_GET['role'];

    // Verify if the ID is valid
    if ($id > 0) {
        // Prepare the query to delete the record from the database
        $delete_query = "DELETE FROM admins WHERE id = ?";
        if ($stmt = $con->prepare($delete_query)) {
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                echo "<script>alert('Registro eliminado correctamente');</script>";
                echo "<script>window.location.href = 'list_almn.php';</script>";
                $stmt->close();
                $con->close();
                exit;
            } else {
                echo "<script>alert('Error al eliminar el registro: " . $stmt->error . "');</script>";
                echo "<script>window.location.href = 'list_almn.php';</script>";
                $stmt->close();
                $con->close();
                exit;
            }
        } else {
            echo "<script>alert('Error en la preparación de la consulta: " . $con->error . "');</script>";
            echo "<script>window.location.href = 'list_almn.php';</script>";
            $con->close();
            exit;
        }
    } else {
        echo "<script>alert('ID no válido');</script>";
        echo "<script>window.location.href = 'list_almn.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('ID o rol no especificado');</script>";
    echo "<script>window.location.href = 'list_almn.php';</script>";
    exit;
}
?>
