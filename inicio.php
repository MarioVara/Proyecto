<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilo.css">
	<title>Web Oficial Copa Rodamiento</title>
</head>
<body>
<?php 
include 'funciones.php';
?>
	<div class='titulo' >
		<img src='imagenes/titulo.png' alt ='Copa Rodamiento'>
	</div>
	<div class='parrafos'>
		
	<?php 
	$cookie = "no";
	setcookie('administrador', $cookie);
	
		echo '<form method="post" action="informacion.php">
				<p>Temporada: <select name="temporada" required>'.seleccionTemporadas().'</select></p>
				<p>Circuito: <select name="circuito">'.seleccionCircuitos().'</select></p>
				<p><input type="submit" name="clasificacion_general" value="Clasificación General"></p>
				<p><input type="submit" name="clasificacion_equipos" value="Clasificación Equipos"></p>
				<p><input type="submit" name="clasificacion_circuito" value="Clasificación Circuito"></p>
			</form>' 
	?>
		<p class="subtitulo">Área de administración</p>
	<form method="post">
		<label>Usuario:</label>
            <input type="text" id="Usuario" name="nombre" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ0-9\s]+" required/><p>
		<label>Contraseña:</label>
            <input type="password" id="Pass" name="pass" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ0-9\s]+" required/><p>
		<input type="submit" value="Enviar">
	</form>
	
	<?php

		if(isset($_POST['nombre'])&&
		isset($_POST['pass'])){
			$nombre = $_POST['nombre'];
			$pass = $_POST['pass'];
			$permitido = permisos($nombre, $pass);
				if($permitido == true){
					echo "Bienvenido ".$_POST['nombre'].". Pulsa <a href ='administracion.php'>AQUI</a> para entrar.";
					if ($_SERVER["REQUEST_METHOD"]=="POST"){
						if($permitido){
							$cookie = "3369";
							setcookie('administrador', $cookie);
						}
					}
				}
				else{
					echo "El usuario o la contraseña no esta en la base de datos.";

				}
		}
	?>
	</div>
</body>
</html>