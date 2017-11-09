<?php 
	# $variable=0; SE CREAN VARIABLES CON $
	# echo $variable; SE IMPRIMEN DATOS CON ECHO Y SE REFERENCIA A LA VARIABLE CON $
	/*for ($i=0; $i<2000;$i++)
	{
		echo $i."<br>";
	}*/
	require_once("../conexion.php"); # NECESITA EN CONECTOR ANTES DE EMPEZAR
	switch ($_POST['action']) {
		case 'get_all':
			$sql="SELECT *from vista_entradas"; #MANDA LLAMAR LA EJECUCIÓN DE LA VISTA DE ENTRADAS
			$result=$conexion->query($sql); #OBTIENE EL RESULTADO DEL QUERY EN LA VARIABLE RESULT
			$datos=array();#SE CREA UN ARRAY QUE SE LLAMA DATOS
			while($row=$result->fetch_array()) #MIENTRAS SE CREA UNA VARIABLE LLAMADA ROW PARA CADA FILA
				$datos[]=$row; #ESTA  SE GUARDA EN EL ARREGLO DE DATOS
			print_r(json_encode($datos)); #CONTROL,IMPRIME EL ARREGLO EN CONSOLA
			break;
			
		case 'delete':
			$sql="call nva_cancel(".$_POST["id_venta"].");"; #LLAMA A EJECUTAR EL PROCEDIMEINTO QUE CANCELA LA VENTA
			$conexion->query($sql);

			break;
		case 'insert':
			$codigo=$_POST['codigo'];
			$cantidad=$_POST['cantidad'];
			$costo=$_POST['costo'];
			$observaciones=$_POST['observaciones'];
			$sql="call nva_ent(".$codigo.",".$cantidad.",".$costo.",'".$observaciones."');";
			$result=$conexion->query($sql)or trigger_error($conexion->error."[$sql]");
			break;
		/*
		case 'get_one':
			$sql='select *from alumnos where id_alumno="'.$_POST["id_alumno"].'";';
			break;*/
		default:
			# code...
		#sql prueba 
			break;
	}
	$conexion->close();
 ?>