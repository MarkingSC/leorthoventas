
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Editar Producto</h4>
			</div>
			<div class="modal-body">
				<form action="insert" style="background: none" method="post"  id="form_edit-productos" name="form_edit_prods">
					<input type="hidden" name="action" value="update">
					<input type="hidden" name="codigo" value='<?php echo $_GET["codigo"]?>'>
					<div class="input-field">
						<input id="descripcion" type="text" name="descripcion">
						<label>Descripción</label>
					</div>
					<div class="input-field">
						<select id="categoria" name="categoria">
						 </select>
					</div>
					<div class="input-field">
						<input type="text" id="minimo" name="minimo" tooltip="Cantidad mínima para alertar">
						<label>Mínimo</label>
					</div>
				</form>
			</div>
				<div class="modal-footer">
					<button type="button" class="btn waves-effect waves-teal red modal-action modal-close" data-dismiss="modal">Cancelar</button>
					<button type="button" class="btn waves-effect waves-teal blue" id="btn_aceptar">Aceptar</button>
				</div>
		</div>
	</div>

	<script type="text/javascript">
		$('#container_modal2').html($("#modal_confirm_edit"));
		$('#container_modal2').modal();
		get_all_categos();
		function get_all_categos(){
			$.post("core/categorias/controller_categos.php", {action:'get_all'}, function(res){
				console.log(res);
				var datos=JSON.parse(res);
				var cod_html="<option disabled='true'>Seleccione categoria</option>";
				for (var i=0;i<datos.length;i++) 
				{
					var info=datos[i];
					cod_html+="<option>"+info['descripcion_c']+"</option>";
					//se insertan los datos a la tabla
				}
				$('#categoria').html(cod_html);
        		$('select').material_select();
			});
		}

		/*get_all_tallas();
		function get_all_tallas(){
			$.post("core/tallas/controller_tallas.php", {action:'get_all'}, function(res){
				console.log(res);
				var datos=JSON.parse(res);
				var cod_html="<option disabled='true'>Seleccione talla</option>";
				for(var i=0;i<datos.length;i++)
				{
					var info=datos[i];
					cod_html+="<option value="+info['desc_talla']+">"+info['desc_talla']+"</option>";
				}
			});
		}*/

		$.post("core/productos/controller_productos.php", {action:"get_one", codigo:<?php echo $_GET["codigo"]?>}, function(res){
						var dat=JSON.parse(res);
						dat=dat[0];						
						console.log(dat);
						$("#descripcion").val(dat["producto"]);
						$("#categoria").val(dat["categoria"]);
						$("#minimo").val(dat["minimo"]);   
						Materialize.updateTextFields(); 
						$('select').material_select();  
		});

		$("#btn_aceptar").click(function(){
			$('#form_edit-productos').submit();
		});

		$("#form_edit-productos").validate({
				errorClass:"invalid",
				rules:{
					descripcion:{required:true},
					categoria:{required:true},
					talla:{required:true},
					minimo:{required:true},
				},
				messages:{
					descripcion:{required:"El producto debe tener un nombre"},
					categoria:{required:"Se debe asignar una categoria al producto"},
					talla:{required:"Asigne una talla al producto"},
					minimo:{required:"Asigne el valor minimo que debe haber en stock"},
				},
				submitHandler: function(form){
					$('#container_modal2').modal("open");
					$('#btn_confirm_edit').click(function(event){
					$.post("core/productos/controller_productos.php", $('#form_edit-productos').serialize(), function(){
						get_all_pagot();
						get_all_productos();
						$("#container_modal2").modal("close");
					});
				$('#container_modal').modal("close");
			});
			}
		});

	</script>

<aside id="modal_confirm_edit">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Guardar Cambios</h4>
			</div>
			<div class="modal-body">
			¿Desea guardar los cambios efectuados?
			</div>
				<div class="modal-footer">
					<button type="button" class="btn waves-effect waves-teal red modal-action modal-close" data-dismiss="modal">Cancelar</button>
					<button type="button" class="btn waves-effect waves-teal blue" id="btn_confirm_edit">Aceptar</button>
				</div>
		</div>
	</div>
</aside>