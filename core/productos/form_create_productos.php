
	<div class="modal-dialog">
		<div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Agregar Productos</h4>
            </div>
            <div class="modal-body">
                <form id="form_productos" name="form_productos">
                    <input type="hidden" name="action" value="insert">
                    <div class="input-field">
                        <label for="">Código de barras</label>
                        <input type="text" id="codigo" name="codigo" >
                    </div>
                    <div class="input-field">
                        <label for="">Descripción del producto</label>
                        <input type="text" id="descripcion" name="descripcion">
                    </div>
                    <div class="row">
                        <div class="col s10 m10 l10 input-field">
                             <select name="categoria" id="categoria">
                             </select>
                        </div>
                        <div class="col s2 m2 l2">
                            <a type="button" class="btn waves-effect waves-teal green" id="btn_addcatego"><span class="material-icons">add</span></a>
                        </div>
                    </div>
                    <div class="input-field">
                        <label for="">Precio</label>
                        <input type="text" id="precio" name="precio">
                    </div>
                    <div class="input-field">
                        <label for="">Mínimo</label>
                        <input type="text" id="minimo" name="minimo" tooltip="Mínimo de productos para alertar">
                    </div>
                </form>
            </div>  
            <div class="modal-footer">
                <a type="button" class="btn waves-effect waves-teal red modal-action modal-close">Cancelar</a>
                <a type="button" class="btn waves-effect waves-teal blue" id="btn_termina_form">Agregar</a>
            </div>
        </div>
    </div>
	
<script>
get_all_categos();
function get_all_categos(){
    $.post("core/categorias/controller_categos.php", {action:'get_all'}, function(res){
        var datos=JSON.parse(res);
        var cod_html="<option value='' disabled selected>Seleccione categoria</option>";
        for (var i=0;i<datos.length;i++) 
        {
            var info=datos[i];
            cod_html+="<option value='"+info["descripcion_c"]+"'>"+info["descripcion_c"]+"</option>";
            //se insertan los datos a la tabla
        }
        $("#categoria").html(cod_html);
        $('select').material_select();
    });
}

$('select').material_select();

$('#btn_addcatego').click(function(){
	$("#container_modal2").load("core/categorias/form_create_catego.php");
    $("#container_modal2").modal(); 
    $("#container_modal2").modal("open");
    get_all_categos();
});

$('#btn_termina_form').click(function(){
    $('#form_productos').submit();
});

$("#form_productos").validate({
		errorClass:"invalid",
		rules:{
			codigo:{required:true,
                    alphanumeric:true,
                    maxlength:45},
			descripcion:{required:true,
                    lettersonly:true},
			categoria:{required:true},
			precio:{required:true,
                    digits:true},
			minimo:{required:true,
                    digits:true},
		},
		messages:{
			codigo:{maxlength:"Introduzca menos de 45 caracteres"},
			categoria:{required:"Se debe asignar una categoria al producto"},
		},
		submitHandler: function(form){
			$.post("core/productos/controller_productos.php", $('#form_productos').serialize(), function(){
				$('#container_modal').modal("close");
                get_all_productos();
			});
	}
});	
</script>
