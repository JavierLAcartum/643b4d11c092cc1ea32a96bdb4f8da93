<?php
function RedirectToURL($url, $tiempo)
{
	header("Refresh: $tiempo, URL=$url");
	exit;
}
function Login()
{
	if(empty($_POST['username']))
	{
		return false;
	}
	if(empty($_POST['password']))
	{
		return false;
	}
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	echo "Usuario: $username y Contrase�a: $password";
	//Tenemos que obtener el tipo de usuario que es
	
	$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");

	$select = "SELECT id, tipo FROM usuarios WHERE usuario='$username' AND password='$password'";
	$result = $conn->query($select);
	$tipoUser = '';
	$idUser = '';
	if ($result->num_rows == 1) {
		
		$row = $result->fetch_assoc();
		$tipoUser = $row['tipo'];
		$idUser = $row['id'];
		
	}else{
		echo "NO EXISTE";
		return false;
	}
	
	//array que guarda tipoUser -> nombre
	$array = array(
		$tipoUser => $idUser,
	);
	
	session_start();
		
	$_SESSION['user'] = $array;
		
	echo "Hemos fijado la variable de sesion: $username";
	
		
	$conn->close();
	
	RedirectToURL("$tipoUser.php", 3);
	
	return true;
}


function Registro(){
	
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	$tipoUsuario = "postor";
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


if(isset($_POST['submit']))
{
	Login();  //Tipo de usuario
}

if(isset($_POST['registro']))
{
	Registro();
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>
			SUBASTAS
		</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="estilos.css" media="screen" />
	</head>
	<body>
		<ul>
			<li style="float:right;">
				<a class="active">
					<form id='login' class="input-list style-4 clearfix" action='index.php' method='post' accept-charset='UTF-8'>
						<input type='text' name='username' id='username' placeholder="Usuario" maxlength="20" style="width:100px;" required />
						<input type='password' name='password' id='password' placeholder="Password" maxlength="20" style="width:100px;" required />
						<input type='submit' name="submit"/>
					</form>
				</a>
			</li>
		</ul>
		<a href="index.php?page=registro">
		<?php echo "Reg&iacutestrate"?>
		</a>
		<div class="wrapper">
			<div id="num_table"	style="display:inline-block">
				<h1 style="color:white">
					Kenken
				</h1>
			</div>
			<div>
				<?php
					if(!isset($_GET['page'])){
						include("listaSubastas.php");
					}else{
						$page = $_GET['page'];
						include("$page.php");
					}
				?>
			</div>
		</div>
	</body>
	</html>