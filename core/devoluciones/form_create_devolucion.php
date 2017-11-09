<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span>
						<span class="sr-only">Close</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Devolver un producto</h4>
				</div>
				<div class="modal-body">
					<form action="insert" style="background: none" method="post"  id="form_devol" name="form_cortes">
					<input type="text" name="ticket" placeholder="Folio del ticket" class="form-control">
					<input type="text" name="codigo" placeholder="Código del producto a devolver" class="form-control">
					<input type="text" name="cantidad" placeholder="Cantidad a devolver" class="form-control">
					<input type="text" name="causa" placeholder="¿Por qué se devolvió?" class="form-control">
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

	$('#btn_aceptar').click(function(){
		$('#form_devol').submit();
	});

		$("#form_devol").validate({
				errorClass:"invalid",
				rules:{
					ticket:{required:true,
							digits:true},
					codigo:{required:true,
							alphanumeric:true,
							maxlength:45},
					cantidad:{required:true,
							digits:true},
					causa:{required:true,
							lettersonly:true}
				},
				messages:{
					ticket:{required:"Ingrese el folio del ticket",
							digits:"Sólo dígitos"},
					codigo:{required:"Ingrese el código del producto"},
					cantidad:{required:"Introduzca una cantidad",
							digits:"Sólo dígitos"},
					causa:{required:"Introduzca una causa"}
				},
				submitHandler: function(form){
					$.post("core/devoluciones/controller_devoluciones.php", {action:"insert"}, function(){
						get_all();
						$("#myModal").modal("hide");
					});
			}
		});

	</script>

