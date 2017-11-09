
function empieza(){

	var nombre=document.getElementById('campo_nombre').value;
	if(nombre==""){
		alert("Introduzca un nombre de cliente.")
	}
	els{
		document.getElementById('form_uno').classList.add('invisible');
		document.getElementById('diventas').classList.remove("invisible");
		var cod_html='<label style="font-size: 1.5em;">Venta para '+nombre+'.</label>'; 
		document.getElementById('venta_de').innerHTML=cod_html;
		document.getElementById('diventas').classList.add("visible"); 
		}
}	
