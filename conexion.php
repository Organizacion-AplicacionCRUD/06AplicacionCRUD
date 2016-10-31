<?php 
//include("config.php");
//require "configuracion.php";

//Carga una sola vez los archivos invocados:

//include_once("config.php");
require_once "config.php";

function conexionMySQL()
{
	//echo "Hola, no usar echo's para imprimir en pantalla";
	$conexion = new mysqli(SERVER,USER,PASS,DB);

	if ($conexion->connect_error)
	{
		$error = "<div class='error'>";
		$error .= "Error de Conexion Nº<b>".$conexion->connect_errno."</b> Mensaje del error: <mark>".$conexion->connect_error."</mark>";
		$error .= "</div>";

		die($error);
	}
	else
	{
		/*$formato = "<div class='mensaje'>Conexion exitosa: <b>".$conexion->host_info."</b></div>";
		echo $formato;*/

		//Con printf debo poner los argumentos en el mismo orden en el que se deben mostrar.
		/*$formato = "<div class='mensaje'>Conexion exitosa: <b>%s</b></div>";
		printf($formato,$conexion->host_info);*/
	}

	$conexion->query("SET CHARACTER SET UTF8");
	return $conexion;
}

//conexionMySQL();
?>