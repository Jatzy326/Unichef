<?php 
	include 'includes/config.php';
	include 'includes/_header.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Vista de Articulos</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
<body> 
<div class="container mt-5">
	<h2>Lista de Articulos</h2>
	<div class="row">
		<?php
		include 'includes/config.php';

		$query = "SELECT id_articulo, nombre, cantidad, imagen FROM articulos";
		$result = $con->query($query);

		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				echo "<div class='col-md-3 mb-4'>";
				echo "<div class='card'>";
				// Se elimina el prefijo redundante 'uploaded_images/'
				echo "<img src='" . htmlspecialchars($row['imagen']) . "' class='card-img-top' alt='Imagen' style='width: 90px; height: 90px; margin: auto;'>";
				echo "<div class='card-body'>";
				echo "<h5 class='card-title'>" . htmlspecialchars($row['nombre']) . "</h5>";
				echo "<p class='card-text'>Cantidad:" .htmlspecialchars($row['cantidad']) . "</p>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
			}
		} else {
			echo "<div class='col-12'>No se encontraron articulos.</div>";
		} 
		$con->close();
		?>
	</div>
</div>
</body>
</html>