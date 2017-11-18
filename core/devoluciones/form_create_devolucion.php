<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title" id="myModalLabel">Devolver un producto</h4>
		</div>
		<div class="modal-body">
			<form action="insert" method="post"  id="form_devol" name="form_devol">
				<div class="input-field">
					<input type="text" name="ticket">
					<label>Folio del ticket</label>
				</div>
				<div class="input-field">
					<input type="text" name="codigo">
					<label>Código del producto</label>
				</div>
				<div class="input-field">
					<input type="text" name="cantidad">
					<label>Cantidad a devolver</label>
				</div>
				<div class="input-field">
					<input type="text" name="causa">
					<label>¿Por qué lo devuelve?</label>
				</div>
			</form>
		</div>
			<div class="modal-footer">
				<label id="lblResDev" class="error"></label>
				<button class="btn waves-effect waves-teal red modal-action modal-close" data-dismiss="modal">Cancelar</button>
				<button class="btn waves-effect waves-teal blue" id="btn_aceptar">Aceptar</button>
			</div>
	</div>
</div>
	<script type="text/javascript">

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
					$.post("core/devoluciones/controller_devoluciones.php", {action:"insert"}, function(res){
						var datos=JSON.parse(res);
						var info=datos[0];
						if(info['tipo']=="ERROR")
						{
							document.getElementById('lblRes').classList.remove("ok");
							document.getElementById('lblRes').classList.add("error");
							$("#lblRes").html("ERROR: "+info['mensaje']);
						}
						else{
							document.getElementById('lblRes').classList.remove("error");
							document.getElementById('lblRes').classList.add("ok");
							$("#lblRes").html("ENTRADA AGREGADA.");
						}
						get_all();
						$('#container_modal').modal(info['mensaje']);
					});
			}
		});

	</script>

