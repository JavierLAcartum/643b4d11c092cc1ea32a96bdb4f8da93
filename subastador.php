<?php
function RedirectToURL($url, $tiempo)
{
	header("Refresh: $tiempo, URL=$url");
	exit;
}
function subirP(){
	$nom = $_POST['nombreProducto'];
	$des = $_POST['descripcionProducto'];
	if(session_id() == '') {
			session_start();
	}	
	$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
	$selectProductos = ("SELECT nombre FROM productos WHERE nombre='$nom' AND idusuario='".$_SESSION['user']['subastador']."'");
			$resultProductos = $conn->query($selectProductos);
			
	$selectLotes = ("SELECT nombre FROM lotes WHERE nombre='$nom' AND idusuario='".$_SESSION['user']['subastador']."'");
			$resultLotes = $conn->query($selectLotes);
			
	if ($resultProductos->num_rows > 0) {
			?>
			<script type="text/javascript">
				alert('Ya existe otro producto con el mismo nombre');
			</script>
			<?php
				return false;
	}
	if ($resultLotes->num_rows > 0) {
			?>
			<script type="text/javascript">
				alert('Ya existe otro lote con el mismo nombre');
			</script>
			<?php
			return false;
	}
		
	
	if(!empty($nom) && !empty($des)){
			
		if(!empty($_FILES['imagenProducto']['name'])){
				
			//si el tipo de archivo es un tipo de imagen permitido.
			
			$permitidos = array("image/jpg", "image/jpeg","image/png");
			$nombreImagen;
			$ruta;
			$tipo = ".".substr($_FILES['imagenProducto']['type'],6,strlen($_FILES['imagenProducto']['type']));
			if (in_array($_FILES['imagenProducto']['type'], $permitidos)){
								
				$ruta = generarNombreArchivo($tipo);
				
				//aquí movemos el archivo desde la ruta temporal a nuestra ruta
				
				$resultado = move_uploaded_file($_FILES["imagenProducto"]["tmp_name"], $ruta);
				if (!$resultado){
					?>
					<script type="text/javascript">
						alert('Ocurrió un error al subir la imagen');
					</script>
					<?php
					return false;
				}
				
			} else {
				?>
				<script type="text/javascript">
					alert('Tipo de archivo no permitido, elija una imagen en formato jpg, jpeg, o png');
				</script>
				<?php
				return false;
			}
				pasarABase64($ruta);	
				return insertInDB($nom,$des,$ruta);
				
		}
				
		else{
			//Crear un producto en la base de datos con nombreProducto, descripcionProducto, y fechaActual
			return insertInDB($nom,$des,"");	
			}
	}else{
		return false;
	}	
}

function pasarABase64 ($imagen){
	
	$img = file_get_contents($imagen);
	$imgcod = base64_encode($img);
	$myfile = fopen($imagen.".b64", "w") or die("Unable to open file!");
	fwrite($myfile, $imgcod);
	fclose($myfile);
	
}

function generarNombreArchivo($tipo){
	$contador = 1;
	$nombre = "img/imagen".$contador.$tipo;
	$nombreFinal ="";
	
	$nombreFinal = generarNombreArchivoRec($nombre, $contador, $tipo);
	
	return $nombreFinal;
}

function generarNombreArchivoRec($nombre, $contador, $tipo){
	
	if(file_exists($nombre.".b64")){
		$contador++;
		$nombre = "img/imagen".$contador.$tipo;
		return generarNombreArchivoRec($nombre, $contador, $tipo);
	}
	else{
		return $nombre;
	}		
	
}

function subirLote(){
	//Mayor o igual que 4 porque tambien hay un campo nombre y el campo de enviar
	//echo "Numero de campos marcados en formulario: ".count($_POST);
	
	if(session_id() == '') {
		session_start();
	}
	
	if(count($_POST)>=4){
		$nombrelote = $_POST['nombreLote'];
		$descripcionLote = $_POST['descripcionLote'];
		
		$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		$selectProductos = ("SELECT nombre FROM productos WHERE nombre='$nombrelote' AND idusuario='".$_SESSION['user']['subastador']."'");
		$resultProductos = $conn->query($selectProductos);
			
		$selectLotes = ("SELECT nombre FROM lotes WHERE nombre='$nombrelote' AND idusuario='".$_SESSION['user']['subastador']."'");
		$resultLotes = $conn->query($selectLotes);
		if ($resultProductos->num_rows > 0) {
			while($resultProductos->fetch_assoc()) {
				?>
				<script type="text/javascript">
					alert('Ya existe otro producto con el mismo nombre');
				</script>
				<?php
				return false;
			}
		}
		if ($resultLotes->num_rows > 0) {
			while($resultLotes->fetch_assoc()) {
				?>
				<script type="text/javascript">
					alert('Ya existe otro lote con el mismo nombre');
				</script>
				<?php	
				return false;
			}
		}
		

		      

		$sql = ("INSERT INTO lotes (nombre, descripcion, idusuario) VALUES ('$nombrelote', '$descripcionLote', '".$_SESSION['user']['subastador']."')");
        
        

		
		if ($conn->query($sql) === TRUE) {
			echo "";
			$idLote = $conn->insert_id;
            
            //esto es para escribir el log
            include("escribirLog.php");
            escribirLog("Lote \""."$nombrelote"."\" creado.", $_SESSION['user']['subastador'], "NULL", "NULL", $idLote, "NULL" );
            //fin de escribir el log
       
			foreach (array_keys($_POST) as $field)
			{
				if($field!="crearLote" && $field!="enviar"){
					$sqlUpdate = "UPDATE productos SET idlote='$idLote' WHERE id='$field'";
					if ($conn->query($sqlUpdate) === TRUE) {
						//echo "Record updated successfully";
					} else {
						//echo "Error updating record: " . $conn->error;
					}
				}
				 
			}
			return true;
		} else {
			//echo "Error: " . $sql . "<br>" . $conn->error;
			return false;
		}
	}else{
		?>
		<script type="text/javascript">
			alert('Debe seleccionar al menos 2 productos');
		</script>
		<?php	
		return false;
	}
}

function crearSubasta(){
	
		
	$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");

	if(array_key_exists('seleccion', $_POST)){ //Si ha seleccionado algún producto o lote
		$tipoSubasta = $_POST['tipoSubasta'];
		$subtipo = $_POST['subtipo'];
		$tipoSubasta = $tipoSubasta * 2 - $subtipo;
	
		/*TIPOS DE SUBASTA
		Dinámica Descubierta ascendente - 1
		Dinámica Descubierta descendente - 2
		Dinámica anónima A - 3
		Dinámica anónima D - 4
		Dinámica holandesa A - 5
		Dinámica holandesa D - 6
		
		Sobre cerrado primer precio A - 7
		Sobre cerrado primer precio D - 8
		Sobre cerrado segundo precio A - 9
		Sobre cerrado segundo precio D -10
		
		Round Robin A - 11
		Round Robin D - 12
		*/
		
		$fechainicio = $_POST['fechainicio'];
		$fechacierre = $_POST['fechacierre'];
		
		$seleccion = $_POST['seleccion'];
		
		$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		
		if(session_id() == '') {
			session_start();
		}
		$sql;
		
		if ($tipoSubasta == "1" | $tipoSubasta == "2" | $tipoSubasta == "3" | $tipoSubasta == "4"){ //Subastas dinámicas descubiertas y anónimas tanto ascendentes como descendentes
		
			$precioInicial = $_POST['precioInicial'];			
			$sql = ("INSERT INTO subastas (tipo,fechainicio,fechacierre, precioinicial, idsubastador) VALUES ('$tipoSubasta', '$fechainicio', '$fechacierre', '$precioInicial', '".$_SESSION['user']['subastador']."')");
		}
		else if($tipoSubasta == "5" | $tipoSubasta == "6"){ //Subastas tipo holandés ascendentes y descendentes
			
			$precioInicial = $_POST['precioInicial'];		
			$cambioPrecio = $_POST['cambioPrecio'];
			$tiempoCambioPrecio = $_POST['tiempoCambioPrecio'];
			
			$sql = ("INSERT INTO subastas (tipo,fechainicio,fechacierre, precioinicial, cambioprecio, tiempocambioprecio, idsubastador) VALUES ('$tipoSubasta', '$fechainicio', '$fechacierre', '$precioInicial', '$cambioPrecio', '$tiempoCambioPrecio', '".$_SESSION['user']['subastador']."')");
			
		}
		else if($tipoSubasta == "11"| $tipoSubasta == "12"){ //Subastas Round Robin ascendentes y descendentes
		
			$fechaSegundaPuja = $_POST['fechaSegundaPuja'];
			
			$sql = ("INSERT INTO subastas (tipo,fechainicio,fechacierre, fechasegundapuja, idsubastador) VALUES ('$tipoSubasta', '$fechainicio', '$fechacierre', '$fechaSegundaPuja', '".$_SESSION['user']['subastador']."')");
			
		}
		else{ //Subastas de sobre cerrado
		
			$sql = ("INSERT INTO subastas (tipo,fechainicio,fechacierre, idsubastador) VALUES ('$tipoSubasta', '$fechainicio', '$fechacierre', '".$_SESSION['user']['subastador']."')");
		
		}
		
		if ($conn->query($sql) === TRUE){
			$idSubasta = $conn->insert_id;
			
			$selectProductos = ("SELECT nombre FROM productos WHERE nombre='$seleccion'");
			$resultProductos = $conn->query($selectProductos);
			$selectLotes = ("SELECT nombre FROM lotes WHERE nombre='$seleccion'");
			$resultLotes = $conn->query($selectLotes);
			while($resultProductos->fetch_assoc()) {

				$sqlUpdate = "UPDATE productos SET idsubasta='$idSubasta' WHERE nombre='$seleccion' AND idusuario ='".$_SESSION['user']['subastador']."'";
				if ($conn->query($sqlUpdate) === TRUE) {
						//echo "Record updated successfully";
				} else {
						//echo "Error updating record: " . $conn->error;
				}
			}		
		
			while($resultLotes->fetch_assoc()) {
					
				$sqlUpdate = "UPDATE lotes SET idsubasta='$idSubasta' WHERE nombre='$seleccion' AND idusuario ='".$_SESSION['user']['subastador']."'";
		
				if ($conn->query($sqlUpdate) === TRUE) {
						//echo "Record updated successfully";
						
				} else {
						//echo "Error updating record: " . $conn->error;
						return false;
				}
			}

			?>
			<script type="text/javascript">
				alert('Subasta creada correctamente');
			</script>
			<?php	

            //esto es para escribir el log
            include("escribirLog.php");
            $queryBuscarProd = "SELECT id FROM productos WHERE idsubasta='$idSubasta' AND idusuario ='".$_SESSION['user']['subastador']."'";
            $resultNombreProd = $conn->query( $queryBuscarProd);
            if($resultNombreProd->num_rows > 0){
                $rowNombreProd = $resultNombreProd->fetch_assoc();
			    $idprod = $rowNombreProd['id'];
                escribirLog("Subasta creada.", $_SESSION['user']['subastador'], $idSubasta, $idprod, "NULL", "NULL");
            }else{
                $queryBuscarLote= "SELECT id FROM lotes WHERE idsubasta='$idSubasta' AND idusuario ='".$_SESSION['user']['subastador']."'";
                $resultNombreLote = $conn->query( $queryBuscarLote);
                $rowNombreLote = $resultNombreLote->fetch_assoc();
			    $idlote = $rowNombreLote['id'];
                escribirLog("Subasta creada.", $_SESSION['user']['subastador'], $idSubasta, "NULL", $idlote, "NULL");
            }
            //fin de escribir el log
            
		}
		else {
			//echo "Error inserting record: " . $conn->error; 
		}
		
	}
		
	else{
		?>
		<script type="text/javascript">
			alert('Debe seleccionar un producto o lote para subastar');
		</script>
		<?php	
	}
		
}

function insertInDB($nombre, $descripcion, $imagen){
	
	$res = '';
	
	$fecha = date("Y-m-d");
	//Llamamos a una funcion que comprueba si tenemos acceso a la base de datos
	
	//Escribimos en la base de datos
	$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
	
	$sql = ("INSERT INTO productos (nombre, descripcion, fecha, idusuario, imagen) VALUES ('$nombre', '$descripcion', '$fecha', '".$_SESSION['user']['subastador']."', '$imagen')");
	
	if ($conn->query($sql) === TRUE) {
		echo "";
		//echo $imagen;
        
        //esto es para escribir el log
        $resultNombreProd = $conn->query( "SELECT id FROM productos WHERE nombre='$nombre'");
        $rowNombre = $resultNombreProd->fetch_assoc();
		$idprod = $rowNombre['id'];
        include("escribirLog.php");
        escribirLog("Producto \""."$nombre"."\" insertado.", $_SESSION['user']['subastador'], "NULL", $idprod, "NULL", "NULL");
        //fin de escribir el log
        
		$res = true;
	} else {
		$res = false;
		//echo "Error: " . $sql . "<br>" . $conn->error;
	}

	if($imagen != ""){
		unlink($imagen); //Borrar la imagen que no está en b64
	}
	$conn->close();
	
	return $res;
}


function borrarProducto(){
    include("escribirLog.php");
	$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
	if(session_id() == '') {
		session_start();
	}
	
	if(count($_POST)> 1){
		foreach ($_POST['productoSeleccionado'] as $field){
			//echo $field;
				if($field!="borrarProducto"){
                    
                    //para el escribir log:
                    $resultNombre = $conn->query( "SELECT id FROM productos WHERE nombre='$field'AND idusuario = '".$_SESSION['user']['subastador']."'");
                    $rowNombre = $resultNombre->fetch_assoc();
		            $id = $rowNombre['id'];
                    //fin datos log
                    
					$deleteProducto = "DELETE FROM productos WHERE nombre='$field' AND idusuario = '".$_SESSION['user']['subastador']."'";					
					$conn->query ($deleteProducto); //Si selecciona un producto, borrar producto
                    
                    if($id!=""){
                        //esto es para escribir el log
                        escribirLog("Producto \""."$field"."\" borrado.", $_SESSION['user']['subastador'], "NULL", $id, "NULL", "NULL" );
                        //fin de escribir el log
                    }
                    
					
					$selectLote = "SELECT id FROM lotes WHERE nombre = '$field' AND idusuario = '".$_SESSION['user']['subastador']."'";
					$resultSelectLote = $conn->query($selectLote);
					if($resultSelectLote->num_rows >= 1){
						
						//Si selecciona un lote, primero se pone la idlote a NULL de los productos que pertenecían a ese lote 
						//y después se borra el lote
						
						while($row = $resultSelectLote->fetch_assoc()) {
							$idLoteActual = $row['id'];
							$updateProducto = "UPDATE productos SET idlote=NULL WHERE idlote='$idLoteActual'";
							$conn->query($updateProducto);
                            
                            //esto es para escribir el log
                            escribirLog("Lote \""."$field"."\" borrado.", $_SESSION['user']['subastador'], "NULL", "NULL", $idLoteActual, "NULL" );
                            //fin de escribir el log
                            
						}
					}
					
					$deleteLote = "DELETE FROM lotes WHERE nombre='$field' AND idusuario = '".$_SESSION['user']['subastador']."'";
					$conn->query ($deleteLote);				
				}
				 
			}
		return true;
	}
	else{
		?>
		<script type="text/javascript">
			alert('No ha seleccionado ningún producto o lote para eliminar');
		</script>
		<?php
		return false;
	}
	
}

if(session_id() == '') {
    session_start();
}
if(isset($_SESSION['user'])){
	foreach (array_keys($_SESSION['user']) as $field)
		{
			if($field!="subastador"){
				RedirectToURL("$field.php",0);
			}
		}
}

if(isset($_POST['subirProducto']))
{
	$sub = subirP();
	if($sub == false){
		?>
		<script type="text/javascript">
			alert('No se ha podido crear el producto');
		</script>
		<?php
		

	}else{
		?>
		<script type="text/javascript">
			alert('Producto creado correctamente');
		</script>
		<?php
	}
}
if(isset($_POST['crearLote'])){
	if(subirLote()==true){
		?>
		<script type="text/javascript">
			alert('Lote creado correctamente');
		</script>
		<?php	
	}else{
		foreach (array_keys($_POST) as $field)
		{
			$_POST[$field] = '';
		}
		?>
		<script type="text/javascript">
			alert('Error al crear el lote');
		</script>
		<?php	
	}
}
if(isset($_REQUEST['crearSubasta'])){
		
	if(crearSubasta()==true){	
		RedirectToURL('subastador.php', 0);
	}
	else {
		foreach (array_keys($_POST) as $field){
				$_POST[$field] = '';
		}
	}
}
if(isset($_POST['borrarProducto'])){
	if(borrarProducto()==true){
		?>
		<script type="text/javascript">
			alert('Producto/lote borrado correctamente');
		</script>
		<?php	

	}else{
		foreach (array_keys($_POST) as $field)
		{
			$_POST[$field] = '';
		}
		?>
		<script type="text/javascript">
			alert('Error al borrar producto/lote');
		</script>
		<?php	
	}
}
if(isset($_POST['salir'])){
	
	if(session_id() == '') {
		session_start();
	}
    
    //esto es para escribir el log
    include("escribirLog.php");
    escribirLog("Cierre de sesión de un subastador." , $_SESSION['user']['subastador'], "NULL", "NULL", "NULL", "NULL");
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
				<a href="subastador.php?page=cerrarSesion">
					<button class="buttonSesion"> Cerrar sesión </button>
				</a>
				<?php 
				if(isset($_GET['page'])){
						?>
						<button class="buttonVolver" onclick="location.href='subastador.php'">Volver</button>
						<?php
				}
				?>
				<a class="active" href="subastador.php?page=subirProducto">
					<button class="buttonSub" style="margin-top: 70px;"> Subir producto </button>
				</a>
				<a href="subastador.php?page=crearLotes">
					<button class="buttonSub"> Crear lotes </button>
				</a>
				<a href="subastador.php?page=borrarProducto">
					<button class="buttonSub"> Borrar productos / lotes </button>
				</a>
				<a href="subastador.php?page=crearSubasta">
					<button class="buttonSub"> Crear subasta </button>
				</a>
		</div>

		<div class="wrapper">

		<table style="width:100%; padding: 30px; margin-top: 230px; font-family:'Segoe UI'; font-weight: bold;">
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

			<div id="num_table"	style="display:inline-block">
			</div>
			</br></br></br>
			<?php
				if(!isset($_GET['page'])){
					?>
					<?php
					include("listaSubastas.php");
					crearTablaSubastas('subastador');
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