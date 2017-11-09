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
			$sql="select *from show_ventas;"; #MANDA LLAMAR A LA VISTA QUE SE ALMACENA EN LA BASE DE DATOS
			$result=$conexion->query($sql)or trigger_error($conexion->error."[$sql]"); #OBTIENE EL RESULTADO DEL QUERY EN LA VARIABLE RESULT
			$datos=array();#SE CREA UN ARRAY QUE SE LLAMA DATOS
			while($row=$result->fetch_array()) #MIENTRAS SE CREA UNA VARIABLE LLAMADA ROW PARA CADA FILA
				$datos[]=$row; #ESTA  SE GUARDA EN EL ARREGLO DE DATOS
			print_r(json_encode($datos)); #CONTROL,IMPRIME EL ARREGLO EN CONSOLA
			break;


		case 'delete':
			$sql='delete from ventas where id_venta='.$_POST["id_venta"].';'; #LLAMA A EJECUTAR EL PROCEDIMEINTO QUE CANCELA LA VENTA
			$result=$conexion->query($sql)or trigger_error($conexion->error."[$sql]");

			break;
		/*
		case 'get_one':
			$sql='select *from alumnos where id_alumno="'.$_POST["id_alumno"].'";';
			break;*/
		case 'insert_tkt':
			$nombre=$_POST['nombre'];
			printf($nombre);
			$sql="call nvo_ticket('".$nombre."');";
			$result=$conexion->query($sql)or trigger_error($conexion->error."[$sql]");
			#inserta un nuevo ticket en la base de datos
			break;

		case 'insert_vta':
			$p_codigo=$_POST["campo_codigo"];
			$p_cantidad=$_POST["campo_cantidad"];
			$sql="call nva_venta('".$p_codigo."',".$p_cantidad.");";
			$result=$conexion->query($sql)or trigger_error($conexion->error."[$sql]");
			$datos=array();#SE CREA UN ARRAY QUE SE LLAMA DATOS
			while($row=$result->fetch_array()) #MIENTRAS SE CREA UNA VARIABLE LLAMADA ROW PARA CADA FILA
				$datos[]=$row; #ESTA  SE GUARDA EN EL ARREGLO DE DATOS
			print_r(json_encode($datos)); #CONTROL,IMPRIME EL ARREGLO EN CONSOLA
			break;

		case 'get_total':
			$sql_total="select sum(subtotal) as total from ventas where id_ticket=(select id_ticket from tickets order by id_ticket desc limit 1);";
			$result=$conexion->query($sql_total)or trigger_error($conexion->error);
			$datos=array();#SE CREA UN ARRAY QUE SE LLAMA DATOS
			while($row=$result->fetch_array()) #MIENTRAS SE CREA UNA VARIABLE LLAMADA ROW PARA CADA FILA
				$datos[]=$row; #ESTA  SE GUARDA EN EL ARREGLO DE DATOS
			print_r(json_encode($datos)); #CONTROL,IMPRIME EL ARREGLO EN CONSOLA
			break;
			
		case 'update':
			$id_venta=$_POST["id_venta"];
			$cantidad=$_POST["cantidad"];
			$sql="call mod_cantventa(".$id_venta.",".$cantidad.");";
			$result=$conexion->query($sql)or trigger_error($conexion->error."[$sql]");
			#actualiza la cantidad de productos en una venta
			break;

		case 'cancel':
			$sql="call nva_cancel();";
			$result=$conexion->query($sql)or trigger_error($conexion->error."[$sql]");
			#cancela una venta y la bitacora
			break;

		case 'finish':
			$sql="call ter_venta();";
			$result=$conexion->query($sql)or trigger_error($conexion->error."[$sql]");
			#termina la venta y calcula el ticket
			break;

		case 'get_cantidad':
			$pid_venta=$_POST['id_venta'];
			$sql="Select cantidad from ventas where id_venta=".$pid_venta.";";
			$result=$conexion->query($sql)or trigger_error($conexion->error);
			$datos=array();#SE CREA UN ARRAY QUE SE LLAMA DATOS
			while($row=$result->fetch_array()) #MIENTRAS SE CREA UNA VARIABLE LLAMADA ROW PARA CADA FILA
				$datos[]=$row; #ESTA  SE GUARDA EN EL ARREGLO DE DATOS
			print_r(json_encode($datos)); #CONTROL,IMPRIME EL ARREGLO EN CONSOLA
			break;

		default:
			# code...
			break;
	}
	$conexion->close();
 ?>