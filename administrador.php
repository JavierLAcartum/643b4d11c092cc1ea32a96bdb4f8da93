<?php 

function RedirectToURL($url, $tiempo)
{
	header("Refresh: $tiempo, URL=$url");
	exit;
}

function Registro(){
	
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	$tipoUsuario = $_POST['tipoUsuario'];
	$nombre = $_POST['nombre'];
	$apellidos = $_POST['apellidos'];
	
	$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");

	$select = "SELECT id FROM usuarios WHERE usuario='$username'";
	$result = $conn->query($select);
	
	if ($result->num_rows == 1) {
		
		echo "Ya existe alguien registrado con ese nombre de usuario.";
		return false;
		
	}else{
		
		$insert = "INSERT INTO usuarios (tipo, usuario, password, nombre, apellidos) VALUES ('$tipoUsuario', '$username', '$password', '$nombre', '$apellidos')";
		
		if ($conn->query($insert) === TRUE) {
			//echo "Usuario ".$username." registrado correctamente.";
			?>
			</br></br>
			<label style="margin-left: 550px; font-family:'Segoe UI'; font-size: 13px;">*Usuario </label>
			<?php
				echo $username
			?>
			<label style="font-family:'Segoe UI'; font-size: 13px;"> registrado correctamente* </label>
			<?php           
            //esto es para escribir el log
            $resultNombreAdmin = $conn->query( "SELECT usuario FROM usuarios WHERE id = '".$_SESSION['user']['administrador']."'");
            $rowNombreAdmin = $resultNombreAdmin->fetch_assoc();
			$nombreAdmin = $rowNombreAdmin['usuario'];
            
            $resultNombreUsuario = $conn->query( "SELECT id FROM usuarios WHERE usuario='$username'");
            $rowNombreUsuario = $resultNombreUsuario->fetch_assoc();
			$idusuario = $rowNombreUsuario['id'];
            include("escribirLog.php");
            escribirLog("El administrador \""."$nombreAdmin"."\" ha creado un " .$tipoUsuario. ".", $idusuario, "NULL", "NULL", "NULL", "NULL");
            //fin de escribir el log
            
			return true;
		} else {
			echo "Error updating record: " . $conn->error;
			return false;
		}
	}
}



if(session_id() == '') {
    session_start();
}

if(isset($_SESSION['user'])){
	foreach (array_keys($_SESSION['user']) as $field)
		{
			if($field!="administrador"){
				RedirectToURL("$field.php",0);
			}
		}
}

if(isset($_POST['registro']))
{
	Registro();
}

if(isset($_POST['salir'])){
	
	if(session_id() == '') {
		session_start();
	}
    
    //esto es para escribir el log
    include("escribirLog.php");
    escribirLog("Cierre de sesión de un administrador." , $_SESSION['user']['administrador'], "NULL", "NULL", "NULL", "NULL");
    //fin de escribir el log
	
	$_SESSION['user']=NULL;
	
	if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
		);
	}
	
	session_destroy();
	
	RedirectToURL("index.php", 0);
}







?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>SUBASTAS</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/estilos.css" media="screen" />
    </head>

    <body>

        <div id="header">

        	<a href="administrador.php?page=cerrarSesion">
                <button class="buttonSesion"> Cerrar Sesion </button>
            </a>
            
            <?php 
			if(isset($_GET['page'])){
					?>
					<button class="buttonVolver" onclick="location.href='administrador.php'"> Volver</button>
					<?php
			}
			?>

            <a href="administrador.php?page=registroAdminSubas">
                <button class="buttonSub"> Crear Usuario </button>
            </a>
            <a href="administrador.php?page=consultaLog">
                <button class="buttonSub"> Consultar Log </button>
            </a>
		
        </div>

        <div class="wrapper">
            <div id="num_table" style="display:inline-block">
            </div>
            </br></br></br></br></br></br></br></br>
            <?php
				if(!isset($_GET['page'])){
					include("verLog.php");
					mostrarLogs();
				}else{
					$page = $_GET['page'];
					include("$page.php");
				}
                if(isset($_REQUEST['enviarConsultaLog'])){
                    include("verLog.php");
                    $tipoConsulta = $_POST['consultaLog'];
                    if($tipoConsulta == "fecha"){
                        $valorConsulta = array( $_POST['fechainicio'], $_POST['fechafin']);
                    }else{
                        $valorConsulta = $_POST['valor'];
                    }
	                mostrarConsulta($tipoConsulta, $valorConsulta); 
                }


				?>

        </div>
        
        <!-- Pie de pagina -->
        <div id="footer">
            <a href="mailto:atercf00@estudiantes.unileon.es">atercf00@estudiantes.unileon.es</a> -
            <a href="mailto:jlezaa00@estudiantes.unileon.es">jlezaa00@estudiantes.unileon.es</a> -
            <a href="mailto:rsierv00@estudiantes.unileon.es">rsierv00@estudiantes.unileon.es</a> -
            <a href="mailto:sestrn00@estudiantes.unileon.es">sestrn00@estudiantes.unileon.es</a> -
            <a href="mailto:sleons00@estudiantes.unileon.es">sleons00@estudiantes.unileon.es</a>
            <address> 09/06/2016 </address>
        </div>
        <!-- end #footer -->

    </body>

    </html>