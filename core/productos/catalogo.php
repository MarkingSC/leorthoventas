<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" >
      <div class="modal-content" style="width:200%; margin-left:-50%;">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Catálogo de productos</h4>
        </div>
        <div class="modal-body">
          <div class="container">    
            <div class="row" id="contenedor">
            <!-- EMPIEZA A GENERAR EL CUADRO POR PRODUCTO -->
            </div>
          </div><br><br>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
  </div>
</div>
<script>

  $("#contenedor").on("click", "div.div_hip", function(){
      var id=$(this).data("id");
      console.log(id);
      $('#container_modal2').load("core/productos/info_prod.php?id="+id);
    });

  $('#myModal').modal();
  get_all_prods();
  

  function get_all_prods(){
    $.post("core/productos/controller_productos.php", {action:"get_catalogo"}, function(res){
      var datos=JSON.parse(res);
        var cod_html;
        for(var i=0;i<datos.length;i++)
        {
          var info=datos[i];
          cod_html+='<div class="col-sm-4" style="width: 20%"><div class="panel panel-primary"><div class="panel-heading">'+info['nombre']+' <input type="checkbox" data-id="'+info['id']+'" style="float:right;"></div><div class="panel-body div_hip" data-id='+info['id']+'><img src="'+info['ruta']+'" class="img-responsive" style="width:100%" alt="Image"></div><div class="panel-footer">Descripcion del producto</div></div>';
          $("#contenedor").html(cod_html);
        }
    });
  }
</script>