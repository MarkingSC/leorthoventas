<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span>
						<span class="sr-only">Close</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Editar Producto</h4>
				</div>
				<div class="modal-body">
					<form action="insert" style="background: none" method="post"  id="form_edit-productos" name="form_edit_prods">
						<input type="hidden" name="action" value="update">
						<input type="hidden" name="codigo" value='<?php echo $_GET["codigo"]?>'>
						<input id="descripcion" type="text" name="descripcion" placeholder="Nombre del producto" class="form-control">
						<select id="categoria" name="categoria" class="form-control" id="categoria">
						 </select>
						 <select id="talla" name="talla" class="form-control" id="talla">
						 </select>
						<input type="text" id="minimo" name="minimo" placeholder="Cantidad mínima para alertar" class="form-control">
						<input type="text" id="exi" name="exi" placeholder="existencias" class="form-control">
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
			});
		}

		get_all_tallas();
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
				$('#talla').html(cod_html);
			});
		}

		var codigo_prod="En el formulario:"+<?php echo $_GET["codigo"]?>;
		console.log(codigo_prod);
		$.post("core/productos/controller_productos.php", {action:"get_one", codigo:<?php echo $_GET["codigo"]?>}, function(res){
						var dat=JSON.parse(res);
						dat=dat[0];						
						console.log(dat);
						$("#descripcion").val(dat["descripcion_p"]);
						$("#categoria").val(dat["id_categoria"]);
						$("#talla").val(dat["id_categoria"]);
						$("#minimo").val(dat["minimo"]);      
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
					$('#modal_confirm_edit').modal();
					$('#btn_confirm_edit').click(function(event){
					$.post("core/productos/controller_productos.php", $('#form_edit-productos').serialize(), function(){
						get_all_productos();
						$("#modal_confirm_edit").modal("hide");
					});
				$('#myModal').modal("hide");
			});
			}
		});

	</script>

<div class="modal fade" id="modal_confirm_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span>
						<span class="sr-only">Close</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Guardar Cambios</h4>
				</div>
				<div class="modal-body">
				¿Desea guardar los cambios efectuados?
				</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<button type="button" class="btn btn-primary" id="btn_confirm_edit">Aceptar</button>
					</div>
			</div>
		</div>
	</div>