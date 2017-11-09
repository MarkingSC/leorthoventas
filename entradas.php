<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/materialize.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="fonts/icons/material-icons.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery.validate.js"></script>
	<script type="text/javascript" src="js/additional-methods.js"></script>
	<script type="text/javascript" src="js/materialize.js"></script>
	<title>VENTAS LEORTHOPEDIC</title>
	<script>
		$(document).ready(function(){
			get_all_entradas();
			});

			$("#content_table").on("click", "a.btn_deta", function(){
				alert("Detalles");
			});
	</script>
</head>
<body>
<nav>
	<?php
	require_once("menu.php");
	?>
</nav>
<div class="container">
	<div>
		<h3>ENTRADAS - LEORTHOPEDIC</h3>
	</div>
	<div class="card-panel">
		<form action="insert" id="form_ent">
			<div class="row">
				<input value="insert" name="action" id="action" type="hidden">
					
				<div class="col input-field">
					<input type="text" class="campo_in" name="codigo" id="codigo">
					<label>Código</label>
				</div>

		        <div class="col input-field">
					<input type="text" class="campo_in" name="observaciones" id="observaciones">
					<label>Observaciones</label>
				</div>

				<div class="col input-field">
					<input type="text" class="campo_in" name="cantidad" id="cantidad">
					<label>Cantidad</label>
				</div>
				<div class="col input-field">
					<input type="text" class="campo_in" name="costo" id="costo">
					<label>Costo</label>
				</div>
				<div class="align-text right">
					<button class="btn waves-effect waves-teal" type="submit"><span class="material-icons">check</span>Aceptar</button>
				</div>
			</div>
		</form>

		<hr style="border: solid 0.5px grey;">
		<div id="divent">
			<table id="tabla_entradas">
			<tr>
				<th class="tit_col">Producto</th>
				<th class="tit_col">Cantidad</th>
				<th class="tit_col">Fecha</th>
				<th class="tit_col">Observaciones</th>
				<th class="tit_col" style="width: 5em;">Detalles</th>
			</tr>
			<tbody id="content_table"></tbody>
			</table>
		</div>
	</div>
</div>
</body>
<aside id="container_modal"></aside>
<aside id="container_modal_2"></aside>
<script>
get_all_entradas();
		function get_all_entradas(){
			$.post("core/entradas/controller_entradas.php", {action:"get_all"}, function(res){
				var datos=JSON.parse(res);
				var cod_html;
				for(var i=0;i<datos.length;i++)
				{
					var info=datos[i];
					cod_html+="<tr><td> "+info["producto"]+" </td><td> "+info["cantidad"]+" </td><td> "+info["fecha"]+" </td><td>"+info["observaciones"]+"</td><td><div class='btn orange btnSmallCircle'><span class='material-icons'>info</span></div></td></tr>";
					$("#content_table").html(cod_html);
				}
			});	
		}

	$("#form_ent").validate({
			errorClass:"invalid",
			rules:{
				codigo:{required:true},
				cantidad:{required:true},
				costo:{required:true},
			},
			messages:{
				codigo:{required:"Introduzca un código válido."},
				cantidad:{required:"Especifique una cantidad."},
				costo:{required:"Defina el costo."},
			},
			submitHandler: function(form){

			$.post("core/entradas/controller_entradas.php", $('#form_ent').serialize(), function(){
				get_all_entradas();
			});
		}
	});
    
	</script>

</html>