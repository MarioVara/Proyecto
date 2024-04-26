<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="estilo.css">
	<title>Formulario</title>
</head>

<body>

	<?php
	//Leemos la cookie para ver si el administrador se ha identificado.
	if($_COOKIE["administrador"] == "3369"){
		include "funciones.php";
		if (isset($_GET['hacer'])) {
			switch ($_GET['hacer']) {

	//Añadir un resultado de una carrera
				case 'anre':
					echo '<form method="post" action="consultas.php">
								<input type="hidden" name="hacer" value="anre">
								<p>Temporada: <select name="temporada" required>' . seleccionTemporadas() . '</select>
								<p>Circuito: <select name="circuito" required>' . seleccionCircuitos() . '</select>
								<p>Sprint?: <select name="sprint">
									<option value="FALSE">No</option>
									<option value="TRUE">Si</option>
								</select><br>
								<table><tr>
								<th>Piloto</th>
								<th>Posicion</th>
								<th>Puntos</th>
								<th>Pole</th>
								<th>Vuelta Rápida</th>
								<th>Puntos Sprint</th>
								<th>Tiempo de Sanción</th>';
					for ($i = 1; $i < 21; $i++) {
						echo '
								</tr>
								<tr>
								<td> <select value="" name="piloto_' . $i . '" >' . seleccionPilotos() . '</select></td>
								<td><input type="number" value="'.$i.'" name="posicion_' . $i . '" ></td>
								<td><input type="number" value="0" name="puntos_' . $i . '" ></td>
								<td><select  name="pole_' . $i . '" > <option value="FALSE">No</option><option value="TRUE">Si</option></select></td>
								<td><select name="vuelta_rapida_' . $i . '" ><option value="FALSE">No</option><option value="TRUE">Si</option></select></td>
								<td><input type="number" value="0" name="puntos_sprint_' . $i . '"></td>
								<td><input type="number" value="0" name="sancion_' . $i . '"></td>
								</tr>';
					}

					echo '</table>
									<p><input type="submit" value="Añadir"></p>
										</form>';
					if (isset($_GET['retorno'])) {
						echo "<p></p>
										<p>Se ha añadido el resultado";
					}
					echo '<p></p>
										<p><a href ="administracion.php">Volver Atras.</a>';

					break;
	//Añadir piloto
				case 'anpi':
					echo '<form method="post" action="consultas.php">
								<input type="hidden" name="hacer" value="anpi">
								<p>Nombre: <input type="text" name="nombre" required></p>
								<p>Nickname: <input type="text" name="nickname" required></p>
								</select>
								<p><input type="submit" value="Añadir"></p>
									</form>';
					if (isset($_GET['retorno'])) {
						echo "<p></p>
								<p>Se ha añadido el piloto";
					}
					echo '<p></p>
								<p><a href ="administracion.php">Volver Atras.</a>';

					break;
	//Añadir temporada
				case 'ante':
					echo '<p>¿Seguro que quieres añadir una temporada?</p>
								<form method="post" action="consultas.php">				
								<p>Temporada:<input type="number" name="temporada">
								<input type="hidden" name="hacer" value="ante">
								<p><input type="submit" value="Añadir"></p>
									</form>';
					if (isset($_GET['retorno'])) {
						echo "<p></p>
								<p>Se ha añadido la temporada";
					}
					echo '<p></p>
								<p><a href ="administracion.php">Volver Atras.</a>';

					break;
	//Añadir circuito
				case 'anci':
					echo '<form method="post" action="consultas.php">
								<input type="hidden" name="hacer" value="anci">
								<p>Circuito: <input type="text" name="circuito" required></p>
								<p>Pais: <input type="text" name="pais" required></p>
								<p><input type="submit" value="Añadir"></p>
									</form>';
					if (isset($_GET['retorno'])) {
						echo "<p></p>
								<p>Se ha añadido el circuito";
					}
					echo '<p></p>
								<p><a href ="administracion.php">Volver Atras.</a>';

					break;
	//Añadir equipo
				case 'aneq':
					echo '<form method="post" action="consultas.php">
								<input type="hidden" name="hacer" value="aneq">
								<p>Nombre del equipo: <input type="text" name="nombre" required></p>
								<p><input type="submit" value="Añadir"></p>
									</form>';
					if (isset($_GET['retorno'])) {
						echo "<p></p>
								<p>Se ha añadido el equipo";
					}
					echo '<p></p>
								<p><a href ="administracion.php">Volver Atras.</a>';

					break;
	//Seleccionar equipo piloto y temporada
				case 'equipar':
						echo '
						<p class="subrayar">Añadir piloto a equipo</p>
						<form method="POST" action="formulario.php" >
						<p>Temporada: <select name="temporada" required>'.seleccionTemporadas().'</select>
						<p>Equipo: <select name="equipo" required>'.seleccionEquipo().'</select>
						<p>Piloto: <select name="piloto" required>'.seleccionPilotos().'</select>
						<p><input type="hidden" name="hacer" value="equipar"></p>
						<p><input type="submit" name="aceptar" value="Aceptar"></p>
						</form>';
				if (isset($_GET['retorno'])) {
					echo "<p></p>
							<p>Se ha añadido el piloto al equipo";
				}
				echo '<p></p>
							<p><a href ="administracion.php">Volver Atras.</a>';
				break;
	//Editar pilotos
				case 'Pilotos':
					$dato = $_GET["piloto"];
					$piloto = recibirDatosPiloto($dato);
					echo
					'<form method="POST" action="consultas.php"> 
								<input type="hidden" name="hacer" value="edpi">
								<input type="hidden" name="id" value="' . (isset($piloto["id"]) ? ($piloto["id"]) : '') . '">
								<p>ID: <input type="text" name="id" value="' . (isset($piloto["id"]) ? ($piloto["id"]) : '') . '" disabled  ></p>
								<p>Nombre: <input type="text" name="nombre" value="' . (isset($piloto["nombre"]) ? ($piloto["nombre"]) : '') . '" ></p>
								<p>Nickname: <input type="text" name="nickname" value="' . (isset($piloto["nickname"]) ? ($piloto["nickname"]) : '') . '" ></p>
								<p><input type="submit" value="Aceptar"></p>
							</form>';
					//Mensaje de si se ha editado el piloto.
					if (isset($_GET['retorno'])) {
						echo "<p></p>
								<p>Se ha editado el piloto";
					}
					echo '<p></p>
								<p><a href ="administracion.php">Volver Atras.</a>';
					break;	
	//Editar el equipo en el que esta el piloto
				case 'Equipos':
					$id_piloto = $_GET["piloto"];
					$temporada = $_GET["temporada"];
					$piloto = recibirDatosPiloto($id_piloto);
					echo
					'<form method="POST" action="consultas.php" > 
								<input type="hidden" name="hacer" value="edeq">
								<input type="hidden" name="id_piloto" value="'.$id_piloto.'">
								<input type="hidden" name="id_temporada" value="'. $temporada .'">
								<p>Temporada: <input type="text" name="id_temporada" value="'. $temporada .'" disabled></p>
								<p>Nombre: <input type="text" name="nombre" value="' . (isset($piloto["nombre"]) ? ($piloto["nombre"]) : '') . '"disabled></p>
								<p>Equipo: <select name="id_equipo" required>'.seleccionEquipo().'</select>
								<p><input type="submit" value="Aceptar"></p>
							</form>';
					//Mensaje de si se ha editado el equipo.
					if (isset($_GET['retorno'])) {
						echo "<p></p>
										<p>Se ha editado el equipo";
					}
					echo '<p></p>
									<p><a href ="administracion.php">Volver Atras.</a>';
					break;
	//Editar circuito
				case 'edci':
					$dato = $_GET['id'];
					$circuito = recibirDatosCircuito($dato);
					echo '<form method="POST" action="consultas.php" >
								<input type="hidden" name="hacer" value="edci">
								<input type="hidden" name="id" value="'.$dato.'">
								<p>ID: <input type="text" name="id" value="' . (isset($circuito["id"]) ? ($circuito["id"]) : '') . '" disabled  ></p>
								<p>Circuito: <input type="text" name="circuito" value="' . (isset($circuito["circuito"]) ? ($circuito["circuito"]) : '') . '" ></p>
								<p>Pais: <input type="text" name="pais" value="' . (isset($circuito["pais"]) ? ($circuito["pais"]) : '') . '" ></p>
										
								<p><input type="submit" value="Aceptar"></p>
							</form>';
					//Mensaje de si se ha editado el circuito.
					if (isset($_GET['retorno'])) {
						echo "<p></p>
								<p>Se ha editado el circuito";
					}
					echo '<p></p>
								<p><a href ="administracion.php">Volver Atras.</a>';
					break;
	//Editar resultado
				case 'edre':
					$temporada= (isset($_GET["id_temporada"]) ? ($_GET["id_temporada"]) : '');
					$circuito = (isset($_GET["id_circuito"]) ? ($_GET["id_circuito"]) : '');

					

					$resultado = clasificacionCircuito($temporada, $circuito);
					echo '<form method="post" action="consultas.php">
					<input type="hidden" name="hacer" value="edre">
					<p>Temporada: <input name="" disabled value="'.$temporada.'">
					<input type="hidden" name="temporada" value="'. $temporada .'">
					<p>Circuito: <input name="" disabled value="'.seleccionNombreCircuito($circuito).'">
					<input type="hidden" name="circuito" value="'.$circuito.'">
					<p>Sprint?: <select name="sprint">
						<option value="FALSE">No</option>
						<option value="TRUE">Si</option>
					</select><br>
					<table><tr>
					<th>Piloto</th>
					<th>Posicion</th>
					<th>Puntos</th>
					<th>Pole</th>
					<th>Vuelta Rápida</th>
					<th>Puntos Sprint</th>
					<th>Tiempo de Sanción</th>';
		for ($i = 1; $i < 21; $i++) {
			echo '
					</tr>
					<tr>
					<td> <select value="" name="piloto_' .$i. '" >' . seleccionPilotos() . '</select></td>
					<td><input type="number" value="'.$i.'" name="posicion_' .$i. '" ></td>
					<td><input type="number" value="0" name="puntos_' .$i. '" ></td>
					<td><select  name="pole_' .$i. '" > <option value="FALSE">No</option><option value="TRUE">Si</option></select></td>
					<td><select name="vuelta_rapida_' .$i. '" ><option value="FALSE">No</option><option value="TRUE">Si</option></select></td>
					<td><input type="number" value="0" name="puntos_sprint_' .$i. '"></td>
					<td><input type="number" value="0" name="sancion_' .$i. '"></td>
					</tr>';
		}

		echo '</table>
						<p><input type="submit" value="Editar"></p>
							</form>';
					//Mensaje de si se ha editado el resultado.
					if (isset($_GET['retorno'])) {
						echo "<p></p>
								<p>Se ha editado el resultado";
					}
					echo '<p></p>
								<p><a href ="administracion.php">Volver Atras.</a>';
					break;

			}
		}
	}
	//Saldrá este mensaje si el administrador no se ha identificado
	else{
		echo "<p>El administrador no se ha identificado, por favor, vuelva a la <a href='inicio.php'>página principal</a> e identifíquese.</p>";
	}
	?>

</body>

</html>