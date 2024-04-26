<?php
	include "conexion.php";

//Función para que los admininistradores puedan administrar
    function permisos ($nombre, $pass){
		$conectar = crearConexion();
		$sql1="SELECT pass FROM administradores WHERE administrador = '".$nombre."';";
		$ejecutar = mysqli_query($conectar,$sql1);
        if (mysqli_num_rows($ejecutar)>0){
            $cadena = mysqli_fetch_assoc($ejecutar);
            $contrasenia = $cadena['pass'];
            if($pass == $contrasenia){
                return true;
            }
        }
        else{return false;}
        cerrarConexion($conectar);
    }
// Consulta para recibir todos los pilotos
    function recibirPilotos (){
        $conectar = crearConexion();
        $sql = "SELECT * FROM piloto ORDER BY nombre ASC";
        $vuelta = mysqli_query($conectar, $sql);
        cerrarConexion($conectar);
        return $vuelta;
    }
// Consulta para recibir los datos de un piloto
    function recibirDatoPiloto ($id){
        $conectar = crearConexion();
        $sql = "SELECT * FROM piloto WHERE id = $id";
        $vuelta = mysqli_query($conectar, $sql);
        cerrarConexion($conectar);
        return $vuelta;
    }    
// Consulta para recibir todos los circuitos
    function recibirCircuitos (){
        $conectar = crearConexion();
        $sql = "SELECT * FROM carrera ORDER BY circuito ASC";
        $vuelta = mysqli_query($conectar, $sql);
        cerrarConexion($conectar);
        return $vuelta;
    }
// Consulta para recibir los datos de un circuito
    function recibirDatoCircuito($id){
        $conectar = crearConexion();
        $sql = "SELECT * FROM carrera WHERE id = '$id'";
        $vuelta = mysqli_query($conectar, $sql);
        cerrarConexion($conectar);
        return $vuelta;
    }
// Consulta para recibir el nombre del equipo
    function recibirCircuitosNombre($idCircuito){
        $conectar = crearConexion();
        $sql = "SELECT circuito FROM carrera WHERE id = $idCircuito";
        $vuelta = mysqli_query($conectar, $sql);
        cerrarConexion($conectar);
        return $vuelta;

    }
// Consulta para recibir todos los equipos
    function recibirEquipos (){
        $conectar = crearConexion();
        $sql = "SELECT * FROM equipo ORDER BY nombre ASC";
        $vuelta = mysqli_query($conectar, $sql);
        cerrarConexion($conectar);
        return $vuelta;
    }
// Consulta para recibir todas las temporadas
    function recibirTemporadas (){
        $conectar = crearConexion();
        $sql = "SELECT * FROM temporada ORDER BY id DESC";
        $vuelta = mysqli_query($conectar, $sql);
        cerrarConexion($conectar);
        return $vuelta;
    }
// Consultar datos clasificación general
    function ClasificacionGeneral($temporada){
        
        $conectar = crearConexion();
        $sql = "SELECT 
                    piloto.nombre AS nombre_piloto, 
                    clasificacion_piloto.total_puntos, 
                    clasificacion_piloto.total_rapidas,
                    clasificacion_piloto.total_poles, 
                    equipo.nombre AS nombre_equipo
                FROM 
                    piloto
                LEFT JOIN 
                    clasificacion_piloto ON clasificacion_piloto.id_piloto = piloto.id
                LEFT JOIN 
                    compite ON clasificacion_piloto.id_piloto = compite.id_piloto AND clasificacion_piloto.id_temporada = compite.id_temporada
                LEFT JOIN 
                    equipo ON compite.id_equipo = equipo.id
                WHERE 
                    clasificacion_piloto.id_temporada = $temporada OR clasificacion_piloto.id_temporada IS NULL
                ORDER BY 
                    clasificacion_piloto.total_puntos DESC;";
        $vuelta = mysqli_query($conectar, $sql);
        cerrarConexion($conectar);
        return $vuelta;
    }
// Consultar datos clasificación circuito
    function ClasificacionCircuito($temporada, $circuito){
        $conectar = crearConexion();
        $sql = "SELECT * 
        FROM clasificacion_carrera 
        WHERE temporada = $temporada AND id_carrera = $circuito
        ORDER BY posicion ASC";
        $vuelta = mysqli_query($conectar, $sql);
        cerrarConexion($conectar);
        return $vuelta;
    }
// Consulta para recibir la clasificacion de los equipos
    function clasificacionEquipos($temporada){
        $conectar = crearConexion();
        $sql = "SELECT equipo.nombre AS nombre_equipo,
                    clasifica_equipos.puntos_totales,
                    clasifica_equipos.vueltas_rapidas,
                    clasifica_equipos.total_poles
                    FROM equipo
                    LEFT JOIN clasifica_equipos ON clasifica_equipos.id_equipo = equipo.id
                    WHERE clasifica_equipos.id_temporada = $temporada OR clasifica_equipos.id_temporada IS NULL
                    ORDER BY puntos_totales DESC";
        $vuelta = mysqli_query($conectar, $sql);
        cerrarConexion($conectar);
        return $vuelta;
    }
// Comprueba si recibo datos y trabajo con ellos en función de lo que reciba en 'hacer'
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hacer'])){
        $hacer = $_POST['hacer'];
                    
        switch($hacer){

//Añade un resultado de una carrera
        case 'anre':
            $temporada = $_POST["temporada"];
            $circuito = $_POST["circuito"];
            $sprint =$_POST["sprint"];

        for ($i = 1; $i < 21; $i++) {
            $piloto = $_POST["piloto_" . $i];
            $llegada = $_POST["posicion_" . $i];
            $puntos = $_POST["puntos_" . $i];
            $pole = $_POST["pole_" . $i];
            $vuelta_rapida = $_POST["vuelta_rapida_" . $i];
            $puntos_sprint = $_POST["puntos_sprint_" . $i];
            $sancion = $_POST["sancion_" . $i];
            if($piloto){
            aniadirResultado($temporada, $circuito, $piloto, $llegada, $puntos, $pole, $vuelta_rapida, $sprint, $puntos_sprint, $sancion);
            }
        }
        header("Location: formulario.php?hacer=anre&retorno=si");

       break;
//Añade un piloto
       case 'anpi':
            $nombre = $_POST["nombre"];
            $nickname = $_POST["nickname"];
            añadirPiloto($nombre, $nickname);
            header("Location: formulario.php?hacer=anpi&retorno=si");

       break;
//Añade un circuito
        case 'anci':
            $circuito = $_POST["circuito"];
            $pais = $_POST["pais"];
            añadirCircuito($circuito, $pais);
            header("Location: formulario.php?hacer=anci&retorno=si");
            
        break;
//Añade una temporada
        case 'ante':
            $temporada = $_POST["temporada"];
            añadirTemporada($temporada);
            header("Location: formulario.php?hacer=ante&retorno=si");
            
        break;
//Añade un equipo
        case 'aneq':
            $nombre = $_POST["nombre"];
            añadirEquipo($nombre);
            header("Location: formulario.php?hacer=aneq&retorno=si");
            
        break;
//Añadir un piloto a un equipo
        case 'equipar':
            $piloto = $_POST['piloto'];
            $temporada =$_POST['temporada'];
            $equipo = $_POST['equipo'];
            añadirPiloto_Equipo($temporada, $piloto, $equipo);
            header("Location: formulario.php?hacer=equipar&retorno=si&piloto=".$_POST['piloto']."");

        break;
//Edita un piloto
        case 'edpi':
            $nombre = $_POST['nombre'];
            $nickname = $_POST['nickname'];
            $id = $_POST['id'];
            editarPiloto($id, $nombre, $nickname);
            header("Location: formulario.php?hacer=Pilotos&retorno=si&piloto=".$_POST['id']."");

        break;
//Edita un equipo en una temporada       
       case 'edeq':
            $id_equipo = $_POST['id_equipo'];
            $id_piloto = $_POST['id_piloto'];
            $id_temporada = $_POST['id_temporada'];
            editarEquipo($id_piloto, $id_equipo, $id_temporada);
            header("Location: formulario.php?hacer=Equipos&retorno=si&piloto=".$id_piloto."&temporada=".$id_temporada."");

        break;
//Edita un circuito
        case 'edci':
           $id = $_POST['id'];
           $circuito = $_POST['circuito'];
           $pais = $_POST['pais'];
           editarCircuito($id, $circuito, $pais);
           header("Location: formulario.php?hacer=edci&retorno=si&id=".$_POST['id']);

        break;
//Edita un resultado
        case 'edre':
            $temporada = $_POST["temporada"];
            $circuito = $_POST["circuito"];
            $sprint =$_POST["sprint"];

        for ($i = 1; $i < 21; $i++) {
            $piloto = $_POST["piloto_" . $i];
            $llegada = $_POST["posicion_" . $i];
            $puntos = $_POST["puntos_" . $i];
            $pole = $_POST["pole_" . $i];
            $vuelta_rapida = $_POST["vuelta_rapida_" . $i];
            $puntos_sprint = $_POST["puntos_sprint_" . $i];
            $sancion = $_POST["sancion_" . $i];
            if($piloto){
            editarResultado($temporada, $circuito, $piloto, $llegada, $puntos, $pole, $vuelta_rapida, $sprint, $puntos_sprint, $sancion);
            }
        }
        header("Location: formulario.php?hacer=edre&retorno=si&id_temporada=$temporada&id_circuito=$circuito");
            break;
   }
}
//Función editar resultado
	function editarResultado($temporada, $circuito, $piloto, $llegada, $puntos, $pole, $vuelta_rapida, $sprint, $puntos_sprint, $sancion){
        $conectar = crearConexion();
        $sql1="UPDATE clasificacion_carrera
        SET posicion=$llegada, puntos=$puntos, 
            pole=$pole, vuelta_rapida=$vuelta_rapida, sprint=$sprint, 
            puntos_sprint=$puntos_sprint, sancion=$sancion, 
            id_equipo =(SELECT id_equipo FROM compite WHERE id_piloto = $piloto AND id_temporada = $temporada)
        WHERE temporada = $temporada and id_carrera=$circuito and id_piloto=$piloto";
        mysqli_query($conectar, $sql1);
        cerrarConexion($conectar);
	}
//Función añadir resultado
	function aniadirResultado($temporada, $circuito, $piloto, $llegada, $puntos, $pole, $vuelta_rapida, $sprint, $puntos_sprint, $sancion){
        $conectar = crearConexion();
        $sql1="INSERT clasificacion_carrera (temporada, id_carrera, id_piloto, posicion, puntos, pole, vuelta_rapida, sprint, puntos_sprint, sancion, id_equipo)
        VALUES ($temporada, $circuito, $piloto, $llegada, $puntos, $pole, $vuelta_rapida, $sprint, $puntos_sprint, $sancion,
                            (SELECT id_equipo FROM compite WHERE id_piloto = $piloto AND id_temporada = $temporada LIMIT 1))";
        mysqli_query($conectar, $sql1);
        cerrarConexion($conectar);
	}
//Función para añadir un piloto a un equipo
    function añadirPiloto_equipo($temporada, $piloto, $equipo){
        $conectar = crearConexion();
        $sql1 ="INSERT compite (id_temporada, id_equipo, id_piloto)
        VALUES ($temporada, $equipo, $piloto)";
        mysqli_query($conectar, $sql1);
    }
//Función para añadir un piloto
    function añadirPiloto($nombre, $nickname){
        $conectar = crearConexion();
        $sql1="INSERT piloto (nombre, nickname)
        VALUES ('$nombre', '$nickname')";
        mysqli_query($conectar, $sql1);
        cerrarConexion($conectar);
    }
//Función para añadir un circuito
    function añadirCircuito($circuito, $pais){
        $conectar = crearConexion();
        $sql1="INSERT carrera (circuito, pais)
        VALUES ('$circuito', '$pais')";
        mysqli_query($conectar, $sql1);
        cerrarConexion($conectar);
    }
//Función para añadir una temporada
    function añadirTemporada($id){
        $conectar = crearConexion();
        $sql1="INSERT temporada (id)
        VALUES ('$id')";
        mysqli_query($conectar, $sql1);
        cerrarConexion($conectar);
    }
//Función para añadir una temporada
    function añadirEquipo($nombre){
        $conectar = crearConexion();
        $sql1="INSERT equipo (nombre)
        VALUES ('$nombre')";
        mysqli_query($conectar, $sql1);
        cerrarConexion($conectar);
    }
//Función para editar un piloto
    function editarPiloto($id, $nombre, $nickname) {
        $conectar = crearConexion();
        $sql1="UPDATE piloto 
        SET nombre = '$nombre', nickname = '$nickname'  
        WHERE id =$id;";
        mysqli_query($conectar, $sql1);
        cerrarConexion($conectar);
    }
//Función para editar un equipo en una temporada
    function editarEquipo($id_piloto, $id_equipo, $id_temporada) {
        $conectar = crearConexion();
        $sql1="UPDATE compite 
        SET  id_equipo = '$id_equipo'  
        WHERE id_temporada =$id_temporada AND id_piloto = $id_piloto;";
        mysqli_query($conectar, $sql1);
        cerrarConexion($conectar);
    }
//Funcion para editar un circuito
    function editarCircuito($id, $circuito, $pais){
        $conectar = crearConexion();
        $sql1="UPDATE carrera 
        SET  circuito = '$circuito', pais = '$pais'  
        WHERE id =$id;";
        mysqli_query($conectar, $sql1);
        cerrarConexion($conectar);
    }
?>