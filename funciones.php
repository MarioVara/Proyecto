<?php 

	include "consultas.php";

//Función para seleccionar los pilotos
	function seleccionPilotos() {
		$opciones='<option value="" selected disabled>-Selecciona un piloto-</option>';
		$pilotos=recibirPilotos();
		while($fila = mysqli_fetch_assoc($pilotos)){
			$opciones.= '<option value="' . $fila["id"] . '">'. $fila["nombre"] .'</option>';
		}
		return $opciones;
	}
//Función para recibir los datos de un piloto
	function recibirDatosPiloto($id) {
		$piloto = mysqli_fetch_assoc(recibirDatoPiloto($id));
		return $piloto;
	}
//Función para recibir los datos de un circuito
	function recibirDatosCircuito($id) {
		$piloto = mysqli_fetch_assoc(recibirDatoCircuito($id));
		return $piloto;
	}
//Función para seleccionar los circuitos
	function seleccionCircuitos() {
		$opciones='<option value="" selected disabled>-Selecciona un circuito-</option>';
		$circuitos=recibirCircuitos();
		while($fila = mysqli_fetch_assoc($circuitos)){
			$opciones.= '<option value="' . $fila["id"] . '">'. $fila["circuito"] .'</option>';
		}
		return $opciones;
	}
//Funcion para ver el nombre de un circuito
	function seleccionNombreCircuito($idCircuito) {
		$circuitos=recibirCircuitosNombre($idCircuito);
		while($fila = mysqli_fetch_assoc($circuitos)){
			$nombre= $fila["circuito"];
		}
		return $nombre;
}
//Función para seleccionar el equipo
	function seleccionEquipo() {
		$opciones='<option value="" selected disabled>-Selecciona un equipo-</option>';
		$equipos=recibirEquipos();
		while($fila = mysqli_fetch_assoc($equipos)){
			$opciones.='<option value="' . $fila["id"] . '">'. $fila["nombre"] .'</option>';
		}
		return $opciones;
	}
//Función para seleccionar la temporada
	function seleccionTemporadas() {
		$opciones='<option value="" selected disabled>-Selecciona una temporada-</option>';
		$temporada=recibirTemporadas();
		while($fila = mysqli_fetch_assoc($temporada)){
			$opciones.='<option value="'. $fila["id"] . '">'. $fila["id"] . '</option>';
		}
		return $opciones;
	}

//Funcion ver resultado generales
	function pintarGeneral($temporada){
		$pos = 1;
		$pilotos = ClasificacionGeneral($temporada);
		if (is_string($pilotos)){
			echo $pilotos;
		}
		else{
			echo "<table class ='pintar'>\n
				<tr>\n
					<th>Pos</th>\n
					<th>Piloto</th>\n
					<th>Puntos</th>\n
					<th>Vueltas Rápidas</th>\n
					<th>Poles</th>\n
					<th>Equipo</th>\n

				</tr>\n ";
			while($fila = mysqli_fetch_assoc($pilotos)){
				
				echo
					"<tr>\n
					   <td>" . $pos . "</td>\n
					   <td>" . $fila["nombre_piloto"] . "</td>\n
					   <td>" . $fila["total_puntos"] . "</td>\n
					   <td>" . $fila["total_rapidas"] . "</td>\n
					   <td>" . $fila["total_poles"] . "</td>\n
					   <td>" . $fila["nombre_equipo"] . "</td>\n";
				$pos++;			
			}
			echo "</table>";
		}
	}
//Funcion ver resultado de una carrera
	function pintarCircuito($temporada, $circuito){
		$pilotos = ClasificacionCircuito($temporada, $circuito);
		if (is_string($pilotos)){
			echo $pilotos;
		}
		else{
			echo "<table class ='pintar'>\n
				<tr>\n
					<th>Posición</th>\n
					<th>Piloto</th>\n
					<th>Puntos</th>\n
					<th>Pole</th>\n
					<th>Vuelta Rápida</th>\n
					<th>Sprint</th>\n
					<th>Puntos Sprint</th>\n
					<th>Sanción</th>\n

				</tr>\n ";
			while($fila = mysqli_fetch_assoc($pilotos)){
				$nombre = mysqli_fetch_assoc(recibirDatoPiloto($fila["id_piloto"]));
				echo 
				
					"<tr>\n
					   <td>" . $fila["posicion"] . "</td>\n
					   <td>" . $nombre["nombre"] . "</td>\n
					   <td>" . $fila["puntos"] . "</td>\n
					   <td>" . $fila["pole"] . "</td>\n
					   <td>" . $fila["vuelta_rapida"] . "</td>\n
					   <td>" . $fila["sprint"] . "</td>\n
					   <td>" . $fila["puntos_sprint"] . "</td>\n
					   <td>" . $fila["sancion"] . "</td>\n
					   ";			
			}
			echo "</table>";
		}
	}
//Funcion ver clasificacion equipos
	function pintarEquipos($temporada){
		
		$equipo = clasificacionEquipos($temporada);
		if (is_string($equipo)){
			echo $equipo;
		}
		else{
			echo "<table class ='pintar'>\n
				<tr>\n
					<th>Pos</th>\n
					<th>Equipo</th>\n
					<th>Puntos</th>\n
					<th>Vueltas Rápidas</th>\n
					<th>Poles totales</th>\n

				</tr>\n ";
			$contador =1;
			while($fila = mysqli_fetch_assoc($equipo)){
				echo
					"<tr>\n
					   <td>" . $contador++ . "</td>\n
					   <td>" . $fila["nombre_equipo"] . "</td>\n
					   <td>" . $fila["puntos_totales"] . "</td>\n
					   <td>" . $fila["vueltas_rapidas"] . "</td>\n
					   <td>" . $fila["total_poles"] . "</td>\n";

					   			
			}
			echo "</table>";
		}
	}