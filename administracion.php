<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="estilo.css">
	<title>Area de Administracion</title>
</head>
<body>
	<?php
		//Leemos la cookie para ver si el administrador se ha identificado.
	if($_COOKIE["administrador"] == "3369"){
		?>
<p class='titulin' >Añadir</p>
<div class="unicode">
<div class="pequeno"><a href='formulario.php?hacer=anre' > &#128366; </a><br>Añadir resultado<br></div>
<div class="pequeno"><a href='formulario.php?hacer=anpi' >&#128100;</a><br>Añadir piloto<br></div>
<div class="pequeno"><a href='formulario.php?hacer=anci' >&#9872;</a><br>Añadir Circuito<br></div>
<div class="pequeno"><a href='formulario.php?hacer=ante' >&#128467;</a><br>Añadir Temporada<br></div>
<div class="pequeno"><a href='formulario.php?hacer=aneq' >&#128101;</a><br>Añadir Equipo<br></div>
<div class="pequeno"><a href='formulario.php?hacer=equipar' class = "unicodito">&#127963;</a><br>Equipar<br></div>
</div>
<p class='titulin' >Ediciones</p>
<?php
include 'funciones.php';

//Editar un piloto
echo 
	'<div class="uno">
		<p class="subrayar">Editar pilotos</p>
		<form method="GET" action="formulario.php" >

		<p>Piloto: <select name="piloto" required>'.seleccionPilotos().'</select>
		
		<p><input type="hidden" name="hacer" value="Pilotos"></p>
		<p><input type="submit" name="aceptar" value="Aceptar"></p>
		</form>
	</div>'.
//Editar un equipo
	'<div class="dos">
		<p class="subrayar">Editar equipos</p>
		<form method="GET" action="formulario.php" >
		<p>Piloto: <select name="piloto" required>'.seleccionPilotos().'</select>
		<p>Temporada: <select name="temporada" required>'.seleccionTemporadas().'</select>
		<p><input type="hidden" name="hacer" value="Equipos"></p>
		<p><input type="submit" name="aceptar" value="Aceptar"></p>
		</form>
	</div>
	<br>'.
//Editar un circuito
	'<div class ="dos">
	<p class="subrayar"> Editar Circuito</p>
	<form method="GET" action="formulario.php" >

		<p>Selecciona el circuito: <select name="id" required>'.seleccionCircuitos().'</select>
		
		<p><input type="hidden" name="hacer" value="edci"></p>
		<p><input type="submit" name="aceptar" value="Aceptar"></p>
		</form>
	</div>'
//Editar un resultado
	.'<div class = "uno">
	<p class ="subrayar"> Editar Resultado </p>
	<form method ="GET" action="formulario.php">
		<p> Selecciona la temporada:<select name="id_temporada" required>'.seleccionTemporadas().'</select>
		<p> Selecciona el circuito:<select name ="id_circuito" required>'.seleccionCircuitos().'</select>

		<p><input type="hidden" name="hacer" value="edre"></p>
		<p><input type="submit" name="aceptar" value="Aceptar"></p>
	</form>
	</div>';
}
	//Saldrá este mensaje si el administrador no se ha identificado
	else{
		echo "<p>El administrador no se ha identificado, por favor, vuelva a la <a href='inicio.php'>página principal</a> e identifíquese.</p>";
	}
?>
<br>
<br>
<a href="Inicio.php" >Volver al inicio</a>
	<?php 
	?>

	
</body>
</html>