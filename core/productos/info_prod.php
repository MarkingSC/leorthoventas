<div class="modal fade" id="modal_infoprod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span>
						<span class="sr-only">Close</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Información de producto</h4>
				</div>
				<div class="modal-body">
					<div class="container-fluid" id="contenedor_info">
					  <!-- 	AQUI SE INSERTAN LOS RESULTADOS DE LA CONSULTA EN LA BASE DE DATOS -->
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					</div>
			</div>
		</div>
	</div>
	<script>
		$('#modal_infoprod').modal();

		get_one();
		function get_one(){
			var id=(<?php echo($_GET['id']) ?>);
			$.post("core/productos/controller_productos.php", {action:"get_one_catalogo", id:id}, function(res){
					var datos=JSON.parse(res);
					var cod_html="";
					for (var i=0;i<datos.length;i++) 
					{
						var info=datos[i];
						cod_html+='<div class="row content"><div class="col-sm-9"><div class="panel-body"><img src="'+info['ruta']+'" class="img-responsive" style="width:100%" alt="Image"></div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt numquam, quas voluptatibus animi pariatur. Numquam voluptates totam vitae, a. Ea dolorum repellat consequatur odit vero quibusdam, blanditiis at quasi voluptatem.</div><div class="col-sm-3 sidenav"><h4>'+info['nombre']+'</h4></div></div>';
						//se insertan los datos a la tabla
					}
					$("#contenedor_info").html(cod_html);
			});
		}
	</script>