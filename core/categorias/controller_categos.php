<?php 
	require("../conexion.php");
	switch ($_POST['action']){
		case 'insert':
			$descripcion_c=$_POST['descripcion_c'];
			$sql="call nva_catego('".$descripcion_c."');";
			$result=$conexion->query($sql)or trigger_error($conexion->error."[$sql]");
			break;
		case 'get_all':
			$sql="SELECT *from categorias;";
			$result=$conexion->query($sql)or trigger_error($conexion->error."[$sql]");
			$datos=array();
			while($row=$result->fetch_array())
				$datos[]=$row;
			print_r(json_encode($datos));
			break;
		default:
			printf("entro al default");
			break;
	}
	$conexion->close();
 ?>