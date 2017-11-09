<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span>
						<span class="sr-only">Close</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Generar corte de caja</h4>
				</div>
				<div class="modal-body">
					<form action="insert" style="background: none" method="post"  id="form_cortes" name="form_cortes">
						<input type="text" name="efectivo" placeholder="Efectivo en caja" class="form-control">
					</form>
				</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<button type="button" class="btn btn-primary" id="btn_aceptar">Aceptar</button>
					</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	$('#myModal').modal();	


		$("#form_cortes").validate({
				errorClass:"invalid",
				rules:{
					efectivo:{required:true},
				},
				messages:{
					efectivo:{required:"Introduzca el efectivo en caja para compararlo con los datos del sistema."},
				},
				submitHandler: function(form)
				{
					$.post("core/cortes/controller_cortes.php", {action:"insert"}, function(){
						get_all();
						$("myModal").modal("hide");
					});
				}
		});

	</script>

