<?php 
require "vistas.php";
require "modelo.php";

/*
Aplicacion CreateReadUpdateDelete (CRUD)
PHP tiene 2 métodos de envío de datos: POST y GET

Create 	Afecta BD 	   INSERT(SQL)   POST 	Modelo
Read 	No afecta BD   SELECT(SQL)   GET 	Vista
Update Afecta BD 		UPDATE(SQL)  POST   Modelo
Delete Afecta BD 	    DELETE(SQL)  POST   Modelo

*/

$transaccion = $_POST["transaccion"];

//echo $transaccion;

function ejecutarTransaccion($transaccion)
{
	if ($transaccion == "alta")
	{
			//Mostrar formulario de alta (vistas.php)
			altaHeroe();
	}
	else if($transaccion == "insertar")
	{
		//Procesar los datos del formulario de alta e insertarlos en mysql (modelo.php)
		insertarHeroe($_POST["nombre_txt"],$_POST["imagen_txt"],$_POST["descripcion_txa"],$_POST["editorial_slc"]);
	} 
	else if ($transaccion == "eliminar")
	{
		//eliminar de mysql el registro solicitado (modelo.php)
		eliminarHeroe($_POST["idHeroe"]);
	}
	else if ($transaccion == "editar")
	{
		//traer los datos del registro a modificar en un formulario(vistas.php)
		editarHeroe($_POST["idHeroe"]);
	}
	else if ($transaccion == "actualizar")
	{
		//modificar en mysql los datos del registro modificado (modelo.php)
		actualizarHeroe($_POST["idHeroe"],$_POST["nombre_txt"],$_POST["imagen_txt"],$_POST["descripcion_txa"],$_POST["editorial_slc"]);
	}
}

ejecutarTransaccion($transaccion);

?>