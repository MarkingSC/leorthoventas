<?php 
	$conexion= new mysqli("localhost", "root", "", "leorthopedic");
	if($conexion->connect_errno){
		printf("Falló la conexion: %s\n", $conexion->connect_error);
		exit();
	}

	/*control para saber si se conecta a la base de datos.
	else
		printf("OK");
		*/
 ?> 
