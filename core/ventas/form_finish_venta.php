	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Terminar venta</h4>
			</div>
			<div class="modal-body">
				<form action="insert" style="background: none" method="post"  id="form_finish_venta" name="form_finish_venta">
					<div class="input-field">
						<input type="text" name="efectivo" id="efectivo">
						<label>Efectivo</label>
					</div>
					<label id="lbl_cambio"></label>
				</form>
			</div>
				<div class="modal-footer">
					<button type="button" class="btn waves-effect waves-teal red modal-action modal-close">Cancelar</button>
					<button type="button" class="btn waves-effect waves-teal blue" id="btn_term_vta">Aceptar</button>
				</div>
		</div>
	</div>
	<script type="text/javascript">

	$('#btn_term_vta').click(function(){
		$('#form_finish_venta').submit();
	});

	$("#form_finish_venta").validate({
		errorClass:"invalid",
		rules:{
			efectivo:{required:true,
						digits:true},
		},
		messages:{
			efectivo:{required:"Introduzca una cantidad.",
						digits:"Sólo dígitos."},
		},
		submitHandler: function(form){
			$.post("core/ventas/controller_ventas.php", {action:"finish"}, function(){
				var efectivo=parseFloat($('#efectivo').val());
				console.log(efectivo);
				var total=<?php echo($_GET['total']); ?>;
				console.log(total);
				if(total>efectivo)
				{
					alert("El efectivo no es suficiente.");
				}
				else
				{
					var cambio=efectivo-total;
					console.log(cambio);
					alert("Cambio: "+cambio);
					window.location="index.php";
					$('#container_modal').modal("close");
				}
			});
		}
	});

	</script>
