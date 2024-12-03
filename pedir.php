<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Préstamo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
<body> 
    <?php 
    include 'includes/_header.php'; 
    ?>

    <div class="container my-5">
        <h2>Solicitud de Préstamo</h2>
        <form action="process_loan_request.php" method="POST">
            <div class="form-group">
                <label for="article">Artículo</label>
                <select class="form-control" id="article" name="article" required>
                    <option value="" selected disabled>Selecciona un artículo</option>
                    <?php
                    include 'includes/config.php';
                    $query = "SELECT id_articulo, nombre FROM articulos";
                    $result = $con->query($query);
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            echo "<option value='" . $row['id_articulo'] . "'>" . $row['nombre'] . "</option>";
                        }
                    }
                    $con->close();
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Cantidad</label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Solicitar Préstamo</button>
        </form>
    </div>

</body>
</html>
