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
			Materialize.updateTextFields();
			$('.modal').modal();

			$("#content_table").on("click", "button.btn_quitar", function(){
				var id_venta=$(this).data("id");
				console.log("id de venta: "+id_venta);
				$('.modal').modal();
				$('#modal_confirm_quitar').modal("open");
				$('#btn_confirm_quit').click(function(event){
				console.log("entro a la funcion del boton");
				$.post("core/ventas/controller_ventas.php", {action:"delete", id_venta:id_venta}, function(){
					get_all_ventas();
					get_total();
				});
				$('#modal_confirm_quitar').modal("close");
				});
			});

			$("#content_table").on("click", "button.btn_editar", function(){
				var id_venta=$(this).data("id");
				console.log("id de venta: "+id_venta);
				$('#modal_editar').modal("open");
				$.post("core/ventas/controller_ventas.php", {action:"get_cantidad", id_venta:id_venta},function(res){
						var info;
						var datos=JSON.parse(res);
						info=datos[0][0];                
						cantidad=info;
						console.log(info);
						$('#txt_editcant').val(cantidad);
						get_all_ventas();
					});
				$('#txt_id_venta').val(id_venta);
				$('#btn_confirm_edit').click(function(){
					console.log("entro a la funcion del boton");
					$('#form_edit').submit();
				});
			});
		});
	</script>
</head>
<body>
<nav class="menu-bar">
	<?php 
	require_once("menu.php");
	?>
</nav>
	<div class="container">
		<div>
			<h3>VENTAS - LEORTHOPEDIC</h3>
		</div>
		<div class="card-panel">
			<div id="container_formuno">
				<div class="row">
					<a id="btn_newdev" href='#' class="btn waves-effect waves-teal" style="float: right;"><span class="material-icons">redo</span> Devolución</a>
				</div>
				<div class="row">
					<form action="" id="form_uno">
						<div class="input-field">
							<input type="text" id="campo_nombre" name="campo_nombre"/>
							<label for="campo_nombre">Nombre del cliente</label>
						</div>	
						<button class="btn waves-effect waves-teal align-center" id="btn_emp"><span class="material-icons">arrow_forward</span>Empezar</button>
					</form>
				</div>
			</div>
			<div id="diventas" class="invisible">
				<div id="venta_de">
				
				</div>
				<hr style="border: solid 0.5px grey;">
				<form action="insert" id="form_dos" style="padding: 2em;">
					<div class="row">
						<div class="col s4 m4 l4">
							<input value="insert_vta" name="action" id="action" type="hidden">
							<div class="input-field">
								<input class="campo_in" type="text" name="campo_codigo" id="campo_codigo"/>
								<label>Código</label>
							</div>
						</div>
						<div class="col s4 m4 l4">
							<div class="input-field">
								<input class="campo_in" type="text" name="campo_cantidad" id="campo_cantidad"/>
								<label>Cantidad</label>
							</div>
						</div>
						<div class="col offset-s1 offset-m1 offset-l1 s3 m3 l3">
							<label id="lblComprueba"></label>
							<button id="btn_agregar" class="btn waves-effect waves-teal" type="submit" style="position:relative; margin-top: 1.5em"><span class="material-icons">check</span>Agregar</button>
						</div>
					</div>
				</form>
				<hr style="border: solid 1px grey;">
				<table id="tabla_vta" class="table responsive-table bordered invisible">
					<tr>
						<th class="tit_col" style="text-align: center;">Código</th>
						<th class="tit_col" style="text-align: center;">Producto</th>
						<th class="tit_col">Precio U.</th>
						<th class="tit_col">Cantidad</th>
						<th class="tit_col">Subtotal</th>
						<th class="tit_col"  style="text-align: center;" colspan="2">Acciones</th>
					</tr>
						<tbody id="content_table"></tbody>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td><label>Total</label></td>
						<td><label id="lbl_total"></label></td>
					</tr>
				</table>
				<button id="btn_canc" class="btn btn-danger"><span class="icon-quitar"></span>Cancelar</button>
				<button id="btn_term" class="btn btn-success"><span class="icon-agregar"></span>Terminar</button>
			</div> 
		</div>
	</div>
	<aside id="container_modal" class="modal"></aside>
</body>
<script type="text/javascript">
	function get_total(){
		$.post("core/ventas/controller_ventas.php", {action:"get_total"}, function(res){
			var  datos=JSON.parse(res);
			var info=datos[0];
			$('#lbl_total').html(info['total']);
		});
	}

	function get_all_ventas()
	{
		$.post("core/ventas/controller_ventas.php", {action:"get_all"}, function(res){
				var datos=JSON.parse(res);
				var cod_html="";
				for(var i=0;i<datos.length;i++)
				{
					var info=datos[i];
					cod_html+="<tr><td class='campo'>"+info['codigo']+" </td><td class='campo'>"+info['producto']+" </td><td class='campo'>"+info['precio']+" </td><td class='campo'>"+info['cantidad']+"</td><td class='campo'>"+info['subtotal']+"</td><td class='botones'><button class='btn red btnSmallCircle btn_quitar' data-id="+info['id_venta']+"><span class='material-icons'>delete</span>Quitar</button></td><td class='botones'><button class='btn blue btnSmallCircle btn_editar' data-id="+info['id_venta']+"><span class='material-icons'>edit</span>Editar</button></td></tr>";
				}
				$("#content_table").html(cod_html);
			});	
	}
	$("#btn_newdev").click(function(){
		$('#container_modal').modal();
		$("#container_modal").load("core/devoluciones/form_create_devolucion.php");
		$('#container_modal').modal("open");
	});

	$('#btn_term').click(function(){
		$('#container_modal').modal();
		$('#container_modal').load("core/ventas/form_finish_venta.php?total="+$('#lbl_total').text());
		$('#container_modal').modal("open");
	});
    
    $('#btn_canc').click(function(){
		$('#modal_confirm_cancelar').modal("open");
        $('#btn_confirm_cancelar').click(function(){
            $.post("core/ventas/controller_ventas.php", {action:'cancel'}, function(){
                window.location="index.php";
            });
        });
    });
	$("#form_uno").validate({
		errorClass: "invalid",
		rules:{
			campo_nombre:{required:true,
							lettersonly:true,
							maxlength:45},
		},
		messages:{
			campo_nombre:{required:"Introduzca el nombre del cliente."},
		},
		submitHandler: function(form)
		{

				var nombre=$('#campo_nombre').val();
			$.post("core/ventas/controller_ventas.php", {action:"insert_tkt", nombre:nombre}, function(){
				document.getElementById('container_formuno').classList.add('invisible');
				document.getElementById('diventas').classList.remove("invisible");
				var cod_html='<h5>Venta para '+nombre+'.</h5>'; 
				document.getElementById('venta_de').innerHTML=cod_html;
				document.getElementById('diventas').classList.add("visible");
			});
		}
	});
	$("#form_dos").validate({
			errorClass:"invalid",
			rules:{
				campo_codigo:{required:true,
								alphanumeric:true,
								maxlength:45},
				campo_cantidad:{required:true,
								digits:true},
			},
			messages:{
				campo_codigo:{required:"Introduzca el código del producto.",
								alphanumeric:"Hay caracteres no válidos."},
				campo_cantidad:{required:"Especifique la cantidad de productos.",
								digits:"Sólo números."},
			},
			submitHandler: function(form){
				$.post("core/ventas/controller_ventas.php", $('#form_dos').serialize(), function(res){
					var datos=JSON.parse(res);
				var info=datos[0];
				if(info['tipo']=="ERROR")
				{
					document.getElementById('lblComprueba').classList.remove("ok");
					document.getElementById('lblComprueba').classList.add("error");
					$("#lblComprueba").html("ERROR: "+info['mensaje']);
				}
				else{
					document.getElementById('lblComprueba').classList.remove("error");
					document.getElementById('lblComprueba').classList.add("ok");
					$("#lblComprueba").html("PRODUCTO AGREGADO");
					document.getElementById('tabla_vta').classList.remove("invisible");
				}
						get_all_ventas();
						get_total();
				});
			}

	});
</script>
</html>

<aside class="modal" id="modal_editar">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel">Editar</h4>
				</div>
				<div class="modal-body">
				<form id="form_edit">
				<input type="hidden" id="txt_id_venta">
				<div class="input-field">
					<label>Cantidad de productos</label>
					<input type="text" placeholder="Cantidad" id="txt_editcant" name="txt_editcant">
				</div>
				</form>
				</div>
					<div class="modal-footer">
						<button type="button" class="btn waves-effect waves-teal red modal-action modal-close">Cancelar</button>
						<button type="button" class="btn waves-effect waves-teal blue" id="btn_confirm_edit">Aceptar</button>
					</div>
			</div>
		</div>
	</div>
</aside>


<aside class="modal" id="modal_confirm_quitar">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Quitar producto</h4>
			</div>
			<div class="modal-body">
			¿De verdad desea quitar el producto de la lista?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn waves-effect waves-teal red modal-action modal-close" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn waves-effect waves-teal blue" id="btn_confirm_quit">Aceptar</button>
			</div>
		</div>
	</div>   
</aside>

<script>
	$('#form_edit').validate({
		errorClass:"invalid",
			rules:{
				txt_editcant:{required:true,
								digits:true},
			},
			messages:{
				txt_editcant:{required:"Especifique la cantidad de productos.",
								digits:"Sólo números."},
			},
			submitHandler: function(form){
				var cantidad=$('#txt_editcant').val();
				var id_venta=$('#txt_id_venta').val();
			$.post("core/ventas/controller_ventas.php", {action:"update", id_venta:id_venta, cantidad:cantidad}, function(){
					$('#modal_editar').modal("close");
					get_all_ventas();
					get_total();
				});
			}
	});

</script>
<aside class="modal" id="modal_confirm_cancelar">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Cancelar venta</h4>
			</div>
			<div class="modal-body">
			¿De verdad desea cancelar toda la venta?
			</div>
				<div class="modal-footer">
					<button type="button" class="btn waves-effect waves-teal red modal-action modal-close">Cancelar</button>
					<button type="button" class="btn waves-effect waves-teal blue" id="btn_confirm_cancelar">Aceptar</button>
				</div>
		</div>
	</div>
</aside>