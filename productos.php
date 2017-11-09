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

	<title>PRODUCTOS EN ALMACEN</title>
	<script>
		$(document).ready(function(){
			get_all_pagot();
		$('#btn_add').click(function(){
			$('#container_modal').load("core/productos/form_create_productos.php");
			$('#container_modal').modal(); 
			$('#container_modal').modal("open");
			get_all_productos();
		});
            //ACCIONES PARA EL BOTÓN DE EDITAR PRODUCTOS
			$("#tbody_prods").on("click", "a.btn_editprod", function(){
				var codigo=$(this).data("id");
				console.log("en el boton "+codigo);
				$('#container_modal').load("core/productos/form_edit_productos.php?codigo="+codigo);
			});
            //ACCIONES PARA EL BOTÓN DE DETALLES
            $("#tbody_prods").on("click", "a.btn_infoprod", function(){
				var codigo=$(this).data("id");
				console.log("en el boton "+codigo);
				$('#container_modal').load("core/productos/info_prod.php?id="+codigo);
			});
            //ACCIONES PARA EL BOTÓN DE ELIMINAR PRODUCTO
            $("#tbody_prods").on("click", "a.btn_bajaprod", function(){
				var id=$(this).data("id");
				console.log("en el boton "+id);
				$('#modal_confirm_quitar').modal();
                $('#btn_confirm_quit').click(function(event){
                    $.post("core/productos/controller_productos.php", {action:"delete", id_producto:id}, function(){
                        get_all_pagot();
                        get_all_productos();
                        $('#modal_confirm_quitar').modal('hide');
                    });
                });
			});
		});
	</script>
</head>
<body>
<nav>
	<?php
	require_once("menu.php");
	?>
</nav>
	<div style="padding: 2em">	
		<div>
			<h3>PRODUCTOS EN ALMACEN</h3>
		</div>
		<div class="card-panel">
			<div class="row">
				<div class="col s12 m8 l8">
					<div>
						<h5>Detalles de productos en la tienda<h5>
						<div class="input-search">
					        <input type="search" class="form-control" placeholder="Buscar producto..." id="btn_buscar">
					    </div>
					</div>
					<a id="btn_add" href='#' class="btn green" style="float: right; width: 3em; padding:0.2em"><span class="material-icons">add</span></a>
					<table id="tabla_productos" class="table responsive-table bordered">
						<tr>
							<th class="tit_col">Código</th>
							<th class="tit_col">Producto</th>
							<th class="tit_col">Talla <a id="btn_addtalla" href='#' class="btn green" style="width: 2em; height: 1.8em; padding:0em"><span class="material-icons">add</span></a></th>
							<th class="tit_col">Precio</th>
							<th class="tit_col">Disponibles</th>
							<th class="tit_col" style="width: 4em;" colspan="3">Acciones</th>
						</tr>
						<tbody id="tbody_prods">
							
						</tbody>
						
					</table>
				</div>
				<div class="col s12 m4 l4 card-panel" id="div_alerta">
					<div id="divprods2">
						<h5>Productos por agotarse</h5>
						<table id="tabla_productosw" class="table responsive-table">
							<tr>
								<th class="tit_col">Descripción</th>
								<th class="tit_col">Disponibles</th>
								<th class="tit_col" style="width: 5em;">Detalles</th>
							</tr>
							<tbody id="tbody_pagot">
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<aside id="container_modal" class="modal"></aside>
	<aside id="container_modal2" class="modal"></aside>
</body>
<div class="modal fade" id="modal_confirm_quitar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Dar de baja un producto</h4>
			</div>
			<div class="modal-body">
			¿De verdad desea dar de baja el producto?
			</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="button" class="btn btn-primary" id="btn_confirm_quit">Aceptar</button>
				</div>
		</div>
	</div>
</div>
<script>
	$('#btn_catalogo').click(function(){
		$('#container_modal').load("core/productos/catalogo.php");
	});
	$('a.btn_infoprod').click(function(){
		$('#container_modal').load("core/productos/info_productos.php");
	});
	$('#btn_addtalla').click(function(){
		$('#container_modal').load("core/tallas/form_create_talla.php");
	});
	get_all_productos();
	function get_all_productos(){
		$.post("core/productos/controller_productos.php", {action:"get_all"}, function(res){
			var datos=JSON.parse(res);
			var cod_html="";
			for (var i=0;i<datos.length;i++) 
			{
				var info=datos[i];
				cod_html+="<tr><td>"+info['codigo']+"</td><td>"+info['descripcion_p']+"</td><td>"+info['id_categoria']+"</td><td>"+info['precio_venta']+"</td><td>"+info['existencias']+"</td><td style='text-align: center; width: 50px;'><a href='#' class='btn blue btnSmallCircle' data-id="+info['codigo']+" tooltip='Editar producto'><span class='material-icons'>edit</span></a></td><td style='text-align: center; width: 50px;'><a href='#' class='btn orange btnSmallCircle' data-id="+info['id']+"><span class='material-icons'>info</span></a><td style='text-align: center; width: 50px;'><a href='#' class='btn red btnSmallCircle' data-id="+info['id']+"><span class='material-icons'>delete</span></a></td></tr>";
				//se insertan los datos a la tabla
			}
			$("#tbody_prods").html(cod_html);
		});
	}
	function get_all_pagot(){
		$.post("core/productos/controller_productos.php", {action:"get_all_pagot"}, function(res){
			var datos=JSON.parse(res);
			var cod_html="";
			for (var i=0;i<datos.length;i++) 
			{
				var info=datos[i];
				cod_html+="<tr><td class='camposwar'>"+info['descripcion']+"</td><td class='camposwar'>"+info['disponibles']+"</td><td class='camposwar'><a href='#' class='btn orange btnSmallCircle'><span class='material-icons'>info</span></a></td></tr>";
				//se insertan los datos a la tabla
			}
			$("#tbody_pagot").html(cod_html);
		});
	}
    


			
</script>
</html>