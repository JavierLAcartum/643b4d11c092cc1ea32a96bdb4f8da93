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
			echo "Usuario ".$username." registrado correctamente.";
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
        <link rel="stylesheet" type="text/css" href="estilos.css" media="screen" />
    </head>

    <body>

        <div id="header">

            <a href="administrador.php?page=registroAdminSubas">
                <button class="buttonSub"> Crear Usuario </button>
            </a>
            <a href="administrador.php?page=verLog">
                <button class="buttonSub"> Ver Log </button>
            </a>
            <a href="administrador.php?page=cerrarSesion">
                <?php echo "Cerrar sesion"?>
            </a>


        </div>

        <div class="wrapper">
            <div id="num_table" style="display:inline-block">
            </div>

            <?php
				if(!isset($_GET['page'])){
					include("listaSubastas.php");
					crearTablaSubastas("");
				}else{
					$page = $_GET['page'];
					include("$page.php");
                    if($page == 'verLog'){
                    }
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