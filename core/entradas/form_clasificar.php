<div class="modal fade" id="modal_categorizar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span>
						<span class="sr-only">Close</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Clasificar la entrada</h4>
				</div>
				<div class="modal-body">
					<form action="insert" style="background: none" method="post"  id="form_categorizar" name="form_categorizar">
						<input type="hidden" value="2">
						<label for="">Tallas</label>
						<div class="panel panel-default" id="contiene_tallas" style="padding: 1em;">
						    
						</div>
						<hr>
						<label for="">Colores</label>
						<hr>
						<label for="">Izquierdo/Derecho</label>
						<br>
						<br>
						<div id="container_tallas" class="panel panel-default" style="padding: 1em;"></div>
					</form>
				</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<button type="button" class="btn btn-primary" id="btn_aceptar">Aceptar</button>
					</div>
			</div>
		</div>
	</div>
	
<script>
    $('#modal_categorizar').modal();
    get_tallas_one(); //OBTIENE LAS TALLAS DE ESTE PRODUCTO
    function get_tallas_one(){
    var codigo=<?php echo($_GET['codigo'])?>;      $.post("core/tallas/controller_tallas.php", {action:"get_tallas_one", codigo:codigo}, function(res){
           console.log(res);
            var datos=JSON.parse(res);
            var cod_html="";
            for(var i=0;i<datos.length;i++)
            {
                var info=datos[i];
                cod_html+="<label>"+info['desc_talla']+"</label><input data-id="+info['id_talla']+" type='text' placeholder='cantidad' class='form-control'>";
            }$('#contiene_tallas').html(cod_html); 
            });
        }
get_colores_one();
function get_colores_one(){ //OBTIENE LOS COLORES DE ESTE PRODUCTO
    var codigo=<?php echo($_GET['codigo'])?>;      $.post("core/colores/controller_colores.php", {action:"get_colores_one", codigo:codigo}, function(res){
           console.log(res);
            var datos=JSON.parse(res);
            var cod_html="";
            for(var i=0;i<datos.length;i++)
            {
                var info=datos[i];
                cod_html+="<label>"+info['desc_color']+"</label><input data-id="+info['id_color']+" type='text' placeholder='cantidad' class='form-control'>";
            }$('#contiene_colores').html(cod_html); 
            });
}
</script>