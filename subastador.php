<?php
function RedirectToURL($url, $tiempo)
{
	header("Refresh: $tiempo, URL=$url");
	exit;
}
function subirP(){
	$nom = $_POST['nombreProducto'];
	$des = $_POST['descripcionProducto'];
	session_start();	
	$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
	$selectProductos = ("SELECT nombre FROM productos WHERE nombre='$nom' AND idusuario='".$_SESSION['user']['subastador']."'");
			$resultProductos = $conn->query($selectProductos);
			
	$selectLotes = ("SELECT nombre FROM lotes WHERE nombre='$nom' AND idusuario='".$_SESSION['user']['subastador']."'");
			$resultLotes = $conn->query($selectLotes);
			
	if ($resultProductos->num_rows > 0) {	
		while($resultProductos->fetch_assoc()) {
			echo "Ya existe otro producto o lote con el mismo nombre.";
				return false;
		}
	}
	if ($resultLotes->num_rows > 0) {
		while($resultLotes->fetch_assoc()) {
			echo "Ya existe otro producto o lote con el mismo nombre.";
			return false;
			
		}
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
					echo "Ocurri&oacute un error al subir la imagen";
					return false;
				}
				
			} else {
				
				echo "Tipo de archivo no permitido, elija una imagen en formato jpg, jpeg, o png";
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
	echo "Numero de campos marcados en formulario: ".count($_POST);
	
	session_start();
	
	if(count($_POST)>=4){
		$nombrelote = $_POST['nombreLote'];
		
		$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		$selectProductos = ("SELECT nombre FROM productos WHERE nombre='$nombrelote' AND idusuario='".$_SESSION['user']['subastador']."'");
		$resultProductos = $conn->query($selectProductos);
			
		$selectLotes = ("SELECT nombre FROM lotes WHERE nombre='$nombrelote' AND idusuario='".$_SESSION['user']['subastador']."'");
		$resultLotes = $conn->query($selectLotes);
		if ($resultProductos->num_rows > 0) {
			while($resultProductos->fetch_assoc()) {
				echo "Ya existe otro producto o lote con el mismo nombre.";
				return false;
			}
		}
		if ($resultLotes->num_rows > 0) {
			while($resultLotes->fetch_assoc()) {
				echo "Ya existe otro producto o lote con el mismo nombre.";
				return false;
			}
		}
		
		$sql = ("INSERT INTO lotes (nombre, idusuario) VALUES ('$nombrelote', '".$_SESSION['user']['subastador']."')");
		
		if ($conn->query($sql) === TRUE) {
			echo "New record created successfully";
			$idLote = $conn->insert_id;
			foreach (array_keys($_POST) as $field)
			{
				if($field!="crearLote" && $field!="enviar"){
					$sqlUpdate = "UPDATE productos SET idlote='$idLote' WHERE id='$field'";
					if ($conn->query($sqlUpdate) === TRUE) {
						echo "Record updated successfully";
					} else {
						echo "Error updating record: " . $conn->error;
					}
				}
				 
			}
			return true;
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
			return false;
		}
	}else{
		echo "Debe seleccionar al menos 2 productos";
		return false;
	}
}

function cambiarFormatoFecha($fecha){
	//El formato que devuelve el input datetime es YYYY-MM-DDTHH:MM
	//Para introducirlo en la base de datos tiene que tener el formato YYYY-MM-DD HH:MM:SS
	
	$date = substr($fecha,0,10); //YYYY-MM-DD
	$tiempo = substr($fecha,11,5).":00"; 
									 
	$fecha = $date." ".$tiempo;

									 
	return $fecha;
}

function crearSubasta(){
	
		
	$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");

	
	if(count($_POST)==7){
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
		
		$precioinicial = $_POST['precio'];
		
		$seleccion = $_POST['seleccion'];
		
		$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		 
		$fechainicio = cambiarFormatoFecha($fechainicio);
		$fechacierre = cambiarFormatoFecha($fechacierre);
		
		session_start();
		$sql;
		
		if($precioinicial==""){		
			$sql = ("INSERT INTO subastas (tipo,fechainicio,fechacierre, idsubastador) VALUES ('$tipoSubasta', '$fechainicio', '$fechacierre', '".$_SESSION['user']['subastador']."')");
		}
		else{
			$sql = ("INSERT INTO subastas (tipo,fechainicio,fechacierre, precioinicial, idsubastador) VALUES ('$tipoSubasta', '$fechainicio', '$fechacierre', '$precioinicial', '".$_SESSION['user']['subastador']."')");
		}
		
		if ($conn->query($sql) === TRUE){
			$idSubasta = $conn->insert_id;
			
					echo $seleccion;
			$selectProductos = ("SELECT nombre FROM productos WHERE nombre='$seleccion'");
			$resultProductos = $conn->query($selectProductos);
			$selectLotes = ("SELECT nombre FROM lotes WHERE nombre='$seleccion'");
			$resultLotes = $conn->query($selectLotes);
			while($resultProductos->fetch_assoc()) {

				$sqlUpdate = "UPDATE productos SET idsubasta='$idSubasta' WHERE nombre='$seleccion'";
				if ($conn->query($sqlUpdate) === TRUE) {
						echo "Record updated successfully";
				} else {
						echo "Error updating record: " . $conn->error;
				}
			}		
		
			while($resultLotes->fetch_assoc()) {
					
				$sqlUpdate = "UPDATE lotes SET idsubasta='$idSubasta' WHERE nombre='$seleccion'";
		
				if ($conn->query($sqlUpdate) === TRUE) {
						echo "Record updated successfully";
						
				} else {
						echo "Error updating record: " . $conn->error;
						return false;
				}
			}
		}
		else {
			echo "Error inserting record: " . $conn->error; 
		}
		
	}
		
	else{
		echo "Debe seleccionar un producto o lote para subastar.";
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
		echo "New record created successfully";
		echo $imagen;
		$res = true;
	} else {
		$res = false;
		echo "Error: " . $sql . "<br>" . $conn->error;
	}

	if($imagen != ""){
		unlink($imagen); //Borrar la imagen que no está en b64
	}
	$conn->close();
	
	return $res;
}


if(isset($_POST['subirProducto']))
{
	$sub = subirP();
	if($sub == false){
		echo "NO SE HA PODIDO CREAR EL PRODUCTO";
	}else{
		echo "PRODUCTO CREADO CORRECTAMENTE";
		RedirectToURL('subastador.php', 3);
	}
}
if(isset($_POST['crearLote'])){
	if(subirLote()==true){
		echo "LOTE CREADO CORRECTAMENTE";
		RedirectToURL('subastador.php', 3);
	}else{
		foreach (array_keys($_POST) as $field)
		{
			$_POST[$field] = '';
		}
		echo "Error al crear lote";
	}
}
if(isset($_REQUEST['crearSubasta'])){
		
	if(crearSubasta()==true){	
		RedirectToURL('subastador.php');
	}
	else {
		foreach (array_keys($_POST) as $field){
				$_POST[$field] = '';
		}
	}
}
		//recuperamos la variable de sesion
		//session_start();

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
			<li style="float:left; padding-left:10%;">
				<a class="active" href="subastador.php?page=subirProducto"><?php echo "subir producto"?></a>
				<a href="subastador.php?page=crearLotes"><?php echo "crear lotes"?></a>
				<a href="subastador.php?page=crearSubasta">
					<?php echo "Crear Subasta"?>
				</a>
			</li>
		</ul>
		<div class="wrapper">
			<div id="num_table"	style="display:inline-block">
				<h1 style="color:white">
					Kenken
				</h1>
			</div>
			<?php
				if(!isset($_GET['page'])){
					
				}else{
					$page = $_GET['page'];
					include("$page.php");
				}
			?>
			<br>
			<br>
			
		</div>
	</body
	</html>