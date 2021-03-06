<?php
function RedirectToURL($url, $tiempo)
{
	header("Refresh: 0, URL=$url");
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
		//echo $result->num_rows;
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
		?>
			<script type="text/javascript">
				alert('Ya existe alguien registrado con ese nombre de usuario');
			</script>
			<?php
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
            escribirLog("Se ha creado un " .$tipoUsuario. " nuevo.", $idusuario, "NULL", "NULL", "NULL", "NULL");
            //fin de escribir el log
            
			return true;
		} else {
			//echo "Error updating record: " . $conn->error;
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
			?>
			<script type="text/javascript">
				alert('Para volver a la pagina principal debe cerrar sesion');
			</script>
			<?php
			RedirectToURL("$field.php",0);
		}
}

if(isset($_POST['submit']))
{
	$tipoUser = Login(); //Tipo de usuario 
	if ($tipoUser != ""){
		//echo $tipoUser;
		RedirectToURL("$tipoUser.php",0);
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
        <link rel="stylesheet" href="css/estilos.css" type="text/css" media="all" />
    </head>
	<body>

        <!-- Cabecera -->
        <div id="header">

          <!-- Login -->
			<a id="inicio" class="active">
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

            <?php 
				if(isset($_GET['page'])){
						?>
						</br></br></br></br></br></br></br></br></br>
						<button style="margin-left: 60px; font-size: 15px; width: 60px; padding:2px;" class="buttonVolver" onclick="location.href='index.php'">Volver</button>
						<?php
				}
			?>

			</br>
			<a href="#about" class="btn btn-circle page-scroll">
                    <i style="text-align: center; font-family:'Segoe UI'; font-size: 13px;" class="fa fa-angle-double-down animated"> AYUDA</i>
            </a>


            <section style="margin-top: 1100px; position: relative;" id="about" class="container content-section text-center">
            	<div class="row">
            		<div class="col-lg-8 col-lg-offset-2">
				        <h2 style="font-size: 30px;">Ayuda</h2>
				        <p>Bienvenido a nuestra página de subastas. En ella dispondrá de diversas opciones que se van a enumerar a continuación:</p>
				        <p>En primer lugar, puede registrarse si no dispone de una cuenta. Sólo debe rellenar los campos requeridos.
				        </p>
				        <p>En caso de tener ya una cuenta creada, en el botón de 'Log in' le permitirá acceder a ella. Una vez logeado, pueden darse 3 tipos diferentes de usuario:
				        </p>
				        </br>
				        <p> El <u>administrador</u>, tiene la opción de dar de alta otro administrador o un subastador, y de ver el log. Para crear un nuevo usuario, deben rellenarse todos los campos. Para consultar el log, se podrán filtrar las búsquedas.
				        </p>
				        <p> El <u>subastador</u>, tiene las opciones de subir un producto, crear un lote, borrarlos, y crear una subasta, teniendo al menos 2 productos subidos. Para subir un producto o lote, será necesario rellenar una serie de campos. A su vez, puede ver las pujas realizadas en sus subastas.
				        </p>
				        <p> El <u>postor</u>, puede ver el historial de subastas.
				        </p>
				        </br>
				        <p> Cada página dispone de un botón de 'Volver', para regresar a la página principal del usuario, o al index. A su vez, los usuarios tienen en todo momento un botón de cerrar sesión. Para poder ver la página de inicio, no se puede estar logeado.
				        </p>
				    </div>
				</div>
		    </section>
		    </br>
			<a href="#inicio" class="btn btn-circle page-scroll" style="margin-top: 1600px; position: relative;">
                    <i style="text-align: center; font-family:'Segoe UI'; font-size: 13px;" class="fa fa-angle-double-down animated"> Volver arriba</i>
            </a>


        </div>
        <!-- end #header -->



		<div class="wrapper">

			<div>
				<?php
					if(!isset($_GET['page'])){
						?>
							<table style="width:100%; padding: 30px; margin-top: 450px; font-family:'Segoe UI'; font-weight: bold;">
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
						<?php
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
            <div style="margin-top: 1400px; position: relative;" id="footer">
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