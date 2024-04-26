<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilo.css">
	<title>Area de Resultados</title>
</head>
<body>
<?php 
include 'funciones.php';
//Recibo los datos enviados
$temporada=($_POST['temporada']);
//Clasificación General dependiendo del número de temporada
		if(isset($_POST['clasificacion_general'])){
            echo '<div class = "tabla">';
            echo '<h1> Clasificacion General de la temporada '.$temporada.'</h1>';
            pintarGeneral($temporada);
            
        }
//Clasificación por carrera dependiendo del número de temporada y de circuito
        elseif(isset($_POST['clasificacion_circuito'])){
            $circuito=($_POST['circuito']);
            $circuitonombre=seleccionNombreCircuito($circuito);
            echo '<div class = "tabla">';
            echo '<h1> Clasificacion de '.$circuitonombre.' temporada '.$temporada.' </h1>';
            pintarCircuito($temporada, $circuito);
            

        }
//Clasificación de equipos en cada temporada
        elseif(isset($_POST['clasificacion_equipos'])){
            echo '<div class = "tabla">';
            echo '<h1> Clasificacion de los equipos en la temporada '.$temporada.'</h1>';
            pintarEquipos($temporada);
            
        }
	?>
<a href="Inicio.php" >Volver al inicio</a>
</div>

	
</body>
</html>