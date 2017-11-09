
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Agregar Categoría</h4>
			</div>
			<div class="modal-body">
				<form method="post"  id="form_categos" name="form_categos">
					<input value="insert" name="action" id="action" type="hidden">
					<div class="input-field">
					<input type="text" name="descripcion_c">
					<label>Descrición</label>
					</div>
				</form>
			</div>
				<div class="modal-footer">
					<button type="button" class="btn waves-effect waves-teal red modal-action modal-close" >Cancelar</button>
					<button type="button" class="btn waves-effect waves-teal blue" id="btn_aceptar_catego">Aceptar</button>
				</div>
		</div>
	</div>

<script type="text/javascript">

$("#btn_aceptar_catego").click(function(){
	$('#form_categos').submit();
});
		$('#form_categos').validate({
				errorClass:"invalid",
				rules:{
					descripcion_c:{required:true},
				},
				messages:{
					descripcion_c:{required:"Se necesita un nombre para la nueva categoría."},
				},
				submitHandler: function(form){
					$.post("core/categorias/controller_categos.php", $('#form_categos').serialize(), function(){
						get_all_categos();
					});
				$('#container_modal2').modal('close');
				}
		});

	</script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
