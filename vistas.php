<?php
require_once "conexion.php";

function listaEditoriales()
{
	$mysql =  conexionMySQL();
	$consulta = "SELECT * FROM editorial";

	if($resultado = $mysql->query($consulta))
	{
		$comboBox = "<select id='editorial' name='editorial_slc' required>";
		$comboBox .= "<option value=''>- - -</option>";
		while($fila = $resultado->fetch_assoc())
		{			
			/*$comboBox .= "<option value='".$fila["id_editorial"]."'>".$fila["editorial"]."</option>";*/
			$comboBox .= sprintf("<option value='%d'>%s</option>",$fila["id_editorial"],$fila["editorial"]);			
		}
		$comboBox .="</select>";

		$resultado->free();
		$mysql->close();

		return ($comboBox);
	}
	else
	{
		echo("Error al consultar editoriales");
		return null;
	}
}

function listaEditorialesEditada($idEditorial)
{
	$mysql = conexionMySQL();
	$sql = "SELECT * FROM editorial";

	if($resultado = $mysql->query($sql))
	{
		$comboBox = "<select id='editorial' name='editorial_slc' required>";
			$comboBox .= "<option value=''>- - -</option>";
			while($fila = $resultado->fetch_assoc())
			{
				$selected = ($idEditorial == $fila["id_editorial"])?"selected":"";
				$comboBox .= sprintf("<option value='%d' $selected>%s</option>",$fila["id_editorial"],$fila["editorial"]);			
			}
		$comboBox .= "</select>";
		$resultado->free();
		$mysql->close();
		return($comboBox);
	}	
	else
	{
		echo("Error al consultar editoriales");
		return null;
	}
}

function altaHeroe()
{
	$form = "<form id='alta-heroe' class='formulario' data-insertar>";
		$form .= "<fieldset>";
			$form .= "<legend>Alta de Super Héroe:</legend>";
			$form .= "<div>";
				$form .= "<label for='nombre'>Nombre: </label>";
				$form .= "<input type='text' id='nombre' name='nombre_txt' required />";
			$form .= "</div>";
			$form .= "<div>";
				$form .= "<label for='imagen'>Imagen: </label>";
				$form .= "<input type='text' id='imagen' name='imagen_txt' required />";
			$form .= "</div>";
			$form .= "<div>";
				$form .= "<label for='descripcion'>Descripcion: </label>";
				$form .= "<textarea id='descripcion' name='descripcion_txa' required ></textarea>";
			$form .= "</div>";
			$form .= "<div>";
				$form .= "<label for='editorial'>Editorial: </label>";
				$form .= listaEditoriales();
			$form .= "</div>";
			$form .= "<div>";
				$form .= "<input type='submit' id='insertar-btn' name='insertar_btn' value='Insertar' />";
				$form .= "<input type='hidden' id='transaccion' name='transaccion' value='insertar' />";
			$form .= "</div>";
		$form .= "</fieldset>";
	$form .= "</form>";

	return printf($form);

}

function editarHeroe($idHeroe)
{
	$mysql = conexionMySQL();
	$sql = "SELECT * FROM Heroes WHERE id_heroe = $idHeroe";
	
	if($resultado = $mysql->query($sql))
	{
		$fila = $resultado->fetch_assoc();
		
		$form = "<form id='editar-heroe' class='formulario' data-editar>";
			$form .= "<fieldset>";
				$form .= "<legend>Edición de Super Héroe:</legend>";
				$form .= "<div>";
					$form .= "<label for='nombre'>Nombre: </label>";
					$form .= "<input type='text' id='nombre' name='nombre_txt' value='".$fila["nombre"]."' required />";
				$form .= "</div>";
				$form .= "<div>";
					$form .= "<label for='imagen'>Imagen: </label>";
					$form .= "<input type='text' id='imagen' name='imagen_txt' value='".$fila["imagen"]."'  required />";
				$form .= "</div>";
				$form .= "<div>";
					$form .= "<label for='descripcion'>Descripcion: </label>";
					$form .= "<textarea id='descripcion' name='descripcion_txa' required >".$fila["descripcion"]."</textarea>";
				$form .= "</div>";
				$form .= "<div>";
					$form .= "<label for='editorial'>Editorial: </label>";
					$form .= listaEditorialesEditada($fila["editorial"]);
				$form .= "</div>";
				$form .= "<div>";
					$form .= "<input type='submit' id='actualizar-btn' name='actualizar_btn' value='Actualizar' />";
					$form .= "<input type='hidden' id='transaccion' name='transaccion' value='actualizar' />";
					$form .= "<input type='hidden' id='idHeroe' name='idHeroe' value='".$fila["id_heroe"]."' />";
				$form .= "</div>";
			$form .= "</fieldset>";
		$form .= "</form>";			
		
		$resultado->free();
	}
	else
	{
		$form = "<div class='error'>Error: No se ejecutó la consulta  a la BD</div>";
	}

	$mysql->close();
	return printf($form);
}

function catalogoEditorales()
{
	$editoriales = Array();

	$mysql = conexionMySQL();
	$sql = "SELECT * FROM editorial";

	if($resultado = $mysql->query($sql))
	{
		while($fila = $resultado->fetch_assoc())
		{
			$editoriales[$fila["id_editorial"]] = $fila["editorial"];
		}
		$resultado->free();
	}
	//print_r($editoriales);
	$mysql->close();	
	return $editoriales;
}

//catalogoEditorales();

function mostrarHeroes()
{
	$editorial = catalogoEditorales();
	$mysql = conexionMySQL();
	$sql = "SELECT * FROM heroes ORDER BY id_heroe DESC";

	if($resultado = $mysql->query($sql))
	{
		//echo "siiiiii";
		$totalRegistros = mysqli_num_rows($resultado);
		if($totalRegistros == 0)
		{
			$respuesta = "<div class='error'>No existe registros de Super héroes, la BD está vacía</div>";
		}
		else
		{
			/* INICIA PAGINACION */
				//Limitar mi consulta SQL
				$regXPag = 2;
				$pagina = false;

				//Examinar la pagina a mostrar y el inicio del registro a mostrar
				if (isset($_GET["p"])) 
				{
					$pagina = $_GET["p"];
				}

				if(!$pagina)
				{
					$inicio = 0;
					$pagina = 1;
				}
				else
				{
					$inicio = ($pagina - 1) * $regXPag;
				}

				//calculo el total de paginas
				$totalPaginas = ceil($totalRegistros / $regXPag);
		
				$sql = $sql." LIMIT $inicio, $regXPag";
				//echo $sql."<br/>".$totalPaginas;

				$resultado = $mysql->query($sql);

				//Despliegue de la paginacion
				$paginacion = "<div class='paginacion'>";
					$paginacion .= "<p>";
						$paginacion .= "Número de resultados: <b>$totalRegistros</b>. ";
						$paginacion .= "Mostrando <b>$regXPag</b> resultados por página. ";
						$paginacion .= "Página <b>$pagina</b> de <b>$totalPaginas</b>.";
					$paginacion .= "</p>";

					if($totalPaginas > 1)
					{
						$paginacion .= "<p>";
							$paginacion .= ($pagina != 1)?"<a href='?p=".($pagina-1)."'>&laquo</a>":"";

							for ($i=1; $i <= $totalPaginas; $i++)
							{ 
								$actual = "<span class='actual'>$pagina</span>";
								$enlace = "<a href='?p=$i'>$i</a>";
								$paginacion .= ($pagina == $i)?$actual:$enlace;
							}

							$paginacion .= ($pagina != $totalPaginas)?"<a href='?p=".($pagina+1)."'>&raquo</a>":"";
						$paginacion .= "</p>";
					}

				$paginacion .="</div>";

			/* TERMINA PAGINACION */
			
			$tabla = "<table id='tabla-heroes' class='tabla'>";
				$tabla .= "<thead>";
					$tabla .= "<tr>";
						$tabla .= "<th>Id Héroe</th>";
						$tabla .= "<th>Nombre</th>";
						$tabla .= "<th>Imagen</th>";
						$tabla .= "<th>Descripción</th>";
						$tabla .= "<th>Editorial</th>";
						$tabla .= "<th></th>";
						$tabla .= "<th></th>";
					$tabla .= "</tr>";
				$tabla .= "</thead>";

				$tabla .= "<tbody>";
				while ($fila = $resultado->fetch_assoc())
				{
					$tabla .= "<tr>";
						$tabla .= "<td>".$fila["id_heroe"]."</td>";
						$tabla .= "<td><h2>".$fila["nombre"]."</h2></td>";
						$tabla .= "<td><img src='img/".$fila["imagen"]."' /></td>";
						$tabla .= "<td>".$fila["descripcion"]."</td>";
						$tabla .= "<td><h3>".$editorial[$fila["editorial"]]."</h3></td>";
						$tabla .= "<td><a href='#' class='editar' data-id='".$fila["id_heroe"]."'>Editar</a></td>";
						$tabla .= "<td><a href='#' class='eliminar' data-id='".$fila["id_heroe"]."'>Eliminar</a></td>";
					$tabla .= "</tr>";
				}
				$resultado->free();
				$tabla .= "</tbody>";
			$tabla .= "</table>";

		$respuesta = $tabla.$paginacion;
		}
	}
	else
	{
		//echo "noooooo";
		$respuesta = "<div class='error'>Error: No se ejecutó la consulta a la BD</div>";
	}

	$mysql->close();

	return printf($respuesta);
}


?>