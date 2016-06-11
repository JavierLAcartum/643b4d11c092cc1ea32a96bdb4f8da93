<?php 
function RedirectToURL($url, $tiempo)
{
	header("Refresh: $tiempo, URL=$url");
	exit;
}

if(session_id() == '') {
    session_start();
}
if(isset($_SESSION['user'])){
	foreach (array_keys($_SESSION['user']) as $field)
		{
			if($field!="postor"){
				RedirectToURL("$field.php",0);
			}
		}
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
		<title>
			POSTOR
		</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="estilos.css" media="screen" />
	</head>
	<body>
		<div id="header">
			<a href="postor.php?page=cerrarSesion">
				<button class="buttonSesion"> Cerrar sesión </button>
			</a>
			<a href="postor.php?page=historialSubastas">
				<button class="buttonSub"> Historial subastas </button>
			</a>
		</div>
			
		<div>				
				<?php
				if(!isset($_GET['page'])){
					include("listaSubastas.php");
					crearTablaSubastas("");
				}else{
					$page = $_GET['page'];
					include("$page.php");
				}
				?>
			
		</div>
	</body
	</html>