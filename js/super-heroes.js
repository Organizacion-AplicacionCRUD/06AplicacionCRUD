//Constante

var READY_STATE_COMPLETE = 4;
var STATUS_OK = 200;

//Variables
var ajax = null;
var btnInsertar = document.querySelector("#insertar");
var precarga = document.querySelector("#precarga");
var respuesta = document.querySelector("#respuesta");

var btnsEliminar =  document.querySelectorAll(".eliminar");
var btnsEditar=  document.querySelectorAll(".editar");
var submit = document.querySelector("#insertar-btn");

//Funciones

function objetoAJAX()
{
	if (window.XMLHttpRequest)
	{
		return new XMLHttpRequest();
	}
	else if(window.ActiveXObject)
	{
		return new ActiveXObject("Microsoft.XMLHTTP");
	}
}

function enviarDatos()
{
	precarga.style.display = "block";
	precarga.innerHTML = "<img src='img/loader.gif' />";

	if (ajax.readyState == READY_STATE_COMPLETE)
	{	
		if(ajax.status == STATUS_OK)
		{			
			//alert("wiii");
			//alert(ajax.responseText);
			precarga.innerHTML = null;
			precarga.style.display = "none";
			respuesta.style.display = "block";
			respuesta.innerHTML = ajax.responseText;

			if(ajax.responseText.indexOf("data-insertar") > -1)
			{
				//Encontro la cadena
				document.querySelector("#alta-heroe").addEventListener("submit", insertarActualizarHeroe);
			}
			else if(ajax.responseText.indexOf("data-recargar") > -1)
			{				
				setTimeout(window.location.reload(),5000);
			}
			else if(ajax.responseText.indexOf("data-editar") > -1)
			{				
				document.querySelector("#editar-heroe").addEventListener("submit", insertarActualizarHeroe);
			}

		}
		else
		{			
			alert("El servidor no contestó\nError "+ ajax.status+": "+ ajax.statusText);
		}
		//console.log(ajax);
	}	
}

function ejecutarAJAX(datos)
{
	/*precarga.style.display = "block";
	precarga.innerHTML = "<img src='img/loader.gif' />";*/
	ajax = objetoAJAX();	
	ajax.onreadystatechange = enviarDatos;
	ajax.open("POST","controlador.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send(datos);
}

function insertarActualizarHeroe(evento)
{
	evento.preventDefault();
	/*console.log(evento);
	console.log(evento.target[0]);
	console.log(evento.target.length);*/

	var nombre = new Array();//"transaccion","nombre_txt","imagen_txt","descripcion");
	var valor = new Array();
	var datos = "";

	for (var i = 1; i < evento.target.length; i++) {
		nombre[i] = evento.target[i].name;
		valor[i] = evento.target[i].value;

		datos += nombre[i] + "=" + valor[i] + "&";
		//console.log(datos);
	}

	
	ejecutarAJAX(datos);
}

function altaHeroe(evento)
{
	evento.preventDefault();
	//alert("funciona");
	var datos = "transaccion=alta";
	ejecutarAJAX(datos);

}

function eliminarHeroe(evento)
{
	evento.preventDefault();
	//var idHeroe = evento.target;
	//alert(evento.target.dataset.id);

	var idHeroe = evento.target.dataset.id;
	var eliminar = confirm("Esta seguro que desea eliminar el Super Héroe con el id: " + idHeroe);

	if(eliminar)
	{
		var datos = "idHeroe="+idHeroe+"&transaccion=eliminar";
		ejecutarAJAX(datos);
	}

	
}

function modificarHeroe(evento)
{
	evento.preventDefault();

}

function editarHeroe(evento)
{
	evento.preventDefault();

	var idHeroe = evento.target.dataset.id;
	var datos = "transaccion=editar&idHeroe="+idHeroe;
	
	ejecutarAJAX(datos);
}

function alCargarDocumento()
{
	btnInsertar.addEventListener("click", altaHeroe);

	for (var i = 0; i < btnsEliminar.length; i++){
		btnsEliminar[i].addEventListener("click", eliminarHeroe);
	}
	
	for (var i = 0; i < btnsEditar.length; i++){
		btnsEditar[i].addEventListener("click", editarHeroe);
	}
}


//Eventos

window.addEventListener("load",alCargarDocumento);