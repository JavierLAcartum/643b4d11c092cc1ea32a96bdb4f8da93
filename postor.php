<?php 
function RedirectToURL($url, $tiempo)
{
	header("Refresh: 0, URL=$url");
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
    
    //esto es para escribir el log
    include("escribirLog.php");
    escribirLog("Cierre de sesión de un postor." , $_SESSION['user']['postor'], "NULL", "NULL", "NULL", "NULL");
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
		<title>
			POSTOR
		</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="css/estilos.css" media="screen" />
	</head>
	<body>
		<div id="header">
			<a href="postor.php?page=cerrarSesion">
				<button class="buttonSesion"> Cerrar sesión </button>
			</a>
			<?php 
				if(isset($_GET['page'])){
						?>
						<button class="buttonVolver" onclick="location.href='subastador.php'">Volver</button>
						<?php
				}
			?>
			<a href="postor.php?page=historialSubastas">
				<button class="buttonSub"> Historial subastas </button>
			</a>
		</div>
			
		</br></br></br>
		</br></br></br>

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