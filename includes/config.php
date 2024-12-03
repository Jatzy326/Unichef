<?php  
	// Configuración de la BD
	$hostname = "localhost";
	$username = "u592633296_saidc";
	$password = "G!sQSl|3";
	$dbname = "u592633296_unichef";

	// Crear la conexión a la BD
	$con = new mysqli($hostname, $username, $password, $dbname);


	//configura la codificacion de caracteres a UTF8
	//$conn->set_charset("utf8mb4");

	// Verificar la conexión
	if ($con->connect_error) {
		die("Conexión fallida: " . $con->connect_error);
	
	}
?>
