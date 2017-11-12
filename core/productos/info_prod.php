	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Información de producto</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid" id="contenedor_info">
				  <!-- 	AQUI SE INSERTAN LOS RESULTADOS DE LA CONSULTA EN LA BASE DE DATOS -->
				</div>
				<div class="modal-footer">
					<button type="button" class="btn waves-effect waves-teal red modal-action modal-close">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
<script>
	get_one();
	function get_one(){
		var id=(<?php echo($_GET['id']) ?>);
		$.post("core/productos/controller_productos.php", {action:"get_one_info", id:id}, function(res){
				var datos=JSON.parse(res);
				var cod_html="";
				for (var i=0;i<datos.length;i++) 
				{
					var info=datos[i];
					cod_html+='<hr style="border: solid 0.5px grey"><br><div class="row"><h5>'+info["producto"]+'</h5></div><div class="row"><div class="col"><label>Código: </label>'+info["codigo"]+'</div><div class="col"><label>Categoría: </label>'+info["categoria"]+'</div><div class="col"><label>Mínimo: </label>'+info["minimo"]+'</div><div class="col"><label>Existencias: </label>'+info["existencias"]+'</div></div>';
					//se insertan los datos a la tabla
				}
				$("#contenedor_info").html(cod_html);
		});
	}
</script>