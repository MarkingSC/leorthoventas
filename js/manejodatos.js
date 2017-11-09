$(document).ready(function()
		{
			get_all();
			$("#add_alimnentos").click(function()
			{
				$("#container_modal").load("core/Alimentos/form_create_alimentos.php");
			});

			$("#content_table").on("click","a.btn_eliminar",function(){
				var id_alumno=$(this).data("id");
				$.post("core/alumnos/controller_alumno.php",{action:"delete",id_alumno:id_alumno},
					function(){
						get_all();
					});
			});

			$("#content_table").on("click","a.btn_modificar",function(){
				var id_alimento=$(this).data("id");
				$("#container_modal").load("core/Alimentos/form_create_alimentos.php?id_alimento="+id_alimento);
			});
		});
		function get_all()
		{
			$.post("core/Alimentos/controller_alimentos.php",{action:"get_all"},
			function(res)
			{
				var datos=JSON.parse(res);
				var cod_html="";
				for (var i=0;i<datos.length;i++) 
				{
					var info=datos[i];
					cod_html+="<tr><td>"+info['id_alimento'];
					//se insertan los datos a la tabla
				}
				$("#content_table").html(cod_html);
				
			});
		}