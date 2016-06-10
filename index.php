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
	
	//Tenemos que obtener el tipo de usuario que es
	
	$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");

	$select = "SELECT id, tipo FROM usuarios WHERE usuario='$username' AND password='$password'";
	$result = $conn->query($select);
	$tipoUser = '';
	$idUser = '';
	if ($result->num_rows == 1) {
		echo $result->num_rows;
		$row = $result->fetch_assoc();
		$tipoUser = $row['tipo'];
		$idUser = $row['id'];
		
	}else{
		echo "";
		?>
			<!-- Titulo -->
	        <div id="tittle">
	            <p> Aplicaciones<b>Web</b> </p>
	        </div>
        	<!-- end #tittle -->

			<label style="position: absolute; left: 920px; top: 35px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold; "> *USUARIO O CONTRASEÑA INCORRECTOS* </label> </br>

		<?php
		return false;
	}
	
	//array que guarda tipoUser -> nombre
	$array = array(
		$tipoUser => $idUser,
	);
	
	if(session_id() == '') {
		session_start();
	}
		
	$_SESSION['user'] = $array;
	
		
	$conn->close();
	
	
	
	return $tipoUser;
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
if(session_id() == '') {
    session_start();
    
    ?>
		<!-- Titulo -->
        <div id="tittle">
            <p> Aplicaciones<b>Web</b> </p>
        </div>
        <!-- end #tittle -->
	<?php
}

if(isset($_SESSION['user'])){
	foreach (array_keys($_SESSION['user']) as $field)
		{
			echo "Para volver a la pagina principal debe cerrar sesion";
			RedirectToURL("$field.php",3);
		}
}

if(isset($_POST['submit']))
{
	$tipoUser = Login(); //Tipo de usuario 
	if ($tipoUser != ""){
		echo $tipoUser;
		RedirectToURL("$tipoUser.php", 0);
	}
}

if(isset($_POST['registro']))
{
	Registro();
}
?>




<!DOCTYPE html>
<html>
	<meta charset="UTF-8">
    </meta>
    <head>
        <title>SUBASTAS</title>
        <link rel="stylesheet" href="estilos.css" type="text/css" media="all" />
    </head>
	<body>

        <!-- Cabecera -->
        <div id="header">

          <!-- Login -->
			<a class="active">
				<form id='login' class="input-list style-4 clearfix" action='index.php' method='post' accept-charset='UTF-8'>
					<input type='text' name='username' id='username' placeholder="Usuario" maxlength="20" style="width:100px; margin-top: 50px;" required />
					<input type='password' name='password' id='password' placeholder="Password" maxlength="20" style="width:100px;" required />
					<button name = 'submit'> Log in </button>
				</form>
			</a>
				

        	<!-- Boton registro -->
			<a href="index.php?page=registro">
				<button class="buttonReg"> Reg&iacutestrate </button>
			</a>

            <h2> Subastas </h2>
            <p> Alba Terce&ntildeo, Javier Leza, Ricardo Sierra, Sara Estrav&iacutes y Sonia Leonato </p>
        </div>
        <!-- end #header -->



		<div class="wrapper">
			<div id="num_table"	style="display:inline-block">
			</div>


			<!-- ESTO DA WARNINGS!!!!!! -->
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