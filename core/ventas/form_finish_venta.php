<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span>
						<span class="sr-only">Close</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Terminar venta</h4>
				</div>
				<div class="modal-body">
					<form action="insert" style="background: none" method="post"  id="form_finish_venta" name="form_finish_venta">
						<input type="text" name="efectivo" id="efectivo" placeholder="Efectivo" class="form-control">
						<label id="lbl_cambio"></label>
					</form>
				</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<button type="button" class="btn btn-primary" id="btn_term_vta">Aceptar</button>
					</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	$('#myModal').modal();	

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
					$('#myModal').modal("hide");
				}
			});
		}
	});

	</script>
