<?php

    function crearConexion(){
        $host = "localhost";
        $user = "root";
        $pass = "1234";
        $baseDatos = "coparodamiento";

        $conexion = mysqli_connect($host,$user,$pass,$baseDatos);
        if(!$conexion){
			die("<br>ERROR de conexión con la base de datos:" . mysqli_connect_error());
		}
		return $conexion;
    }

    function cerrarConexion($conexion) {
        mysqli_close($conexion);
    }

?>