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
        
        //esto es para escribir el log
        include("escribirLog.php");
        escribirLog("Inicio de sesión de un " .$tipoUser. ".", $idUser, "NULL", "NULL", "NULL", "NULL");
        //fin de escribir el log
		
	}else{
		echo "";
		?>
			<!-- Titulo -->
	        <div id="tittle">
	            <p> Aplicaciones<b>Web</b> </p>
	        </div>
        	<!-- end #tittle -->

			<label style="position: absolute; left: 915px; top: 35px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold; "> *USUARIO Y/O CONTRASEÑA INCORRECTOS* </label> </br>

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
			//echo "Usuario ".$username." registrado correctamente.";
            
            //esto es para escribir el log
            $resultNombreUsuario = $conn->query( "SELECT id FROM usuarios WHERE usuario='$username'");
            $rowNombreUsuario = $resultNombreUsuario->fetch_assoc();
			$idusuario = $rowNombreUsuario['id'];
            include("escribirLog.php");
            escribirLog("Se ha creado un " .$tipoUsuario. " nuevo", $idusuario, "NULL", "NULL", "NULL", "NULL");
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

			<table style="width:100%; padding: 30px; margin-top: 390px; font-family:'Segoe UI'; font-weight: bold;">
			    <tr>
			        <td style="width: 250px; text-align: center;">TIPO</td>
			        <td style="width: 100px; text-align: center;">ID</td>
			        <td style="width:140px; text-align: center;">NOMBRE</td>
			        <td style="width: 135px; text-align: center;">IMAGEN</td>
			        <td style="width: 200px; text-align: center;">FECHA FINALIZACIÓN</td>
			        <td style="width: 170px; text-align: center;">PRODUCTO/LOTE</td>
			        <td style="width: 150px; text-align: center;"> </td>
			    </tr>
           	</table>

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