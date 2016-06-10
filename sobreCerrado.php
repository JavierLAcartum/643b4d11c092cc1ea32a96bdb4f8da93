<?php

	$idSubasta = $_GET['id'];

	echo "Identificador de la subasta: ".$idSubasta."\n";

	$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
	if(session_id() == '') {
		session_start();
	}
	$selectSubastas = "SELECT tipo, idsubastador, fechainicio, fechacierre FROM subastas WHERE id='$idSubasta'";
	$resultSubastas = $conn->query($selectSubastas);
	$tipoSubasta; $tipoSubastaString; $producto; $subastador; $fechaInicio; $fechaCierre;
	
	include("listaSubastas.php");
	
	if($resultSubastas->num_rows > 0){
		
		while($row = $resultSubastas->fetch_assoc()) {
			
			$tipoSubasta = $row['tipo'];
			$tipoSubastaString = pasarTipoSubastaAString($tipoSubasta);
			echo "Tipo de subasta: ".$tipoSubastaString."\n";
			
			$fechaInicio = $row['fechainicio'];
			$fechaCierre = $row['fechacierre'];

			echo "Fecha de inicio: ".$fechaInicio."\n";
			echo "Fecha de cierre: ".$fechaCierre."\n";
			
			$idSubastador = $row['idsubastador'];
			$selectSubastador = "SELECT nombre, apellidos FROM usuarios WHERE id='$idSubastador'";
			$resultSubastador = $conn->query($selectSubastador);
			
			if($resultSubastador->num_rows > 0){
		
				while($rowSubastador = $resultSubastador->fetch_assoc()) {
				
					$nombre = $rowSubastador['nombre'];
					$apellidos = $rowSubastador['apellidos'];
					
					echo "Subastador: ".$nombre." ".$apellidos."\n";

				}	
			}
			
			$selectProducto = "SELECT nombre, descripcion FROM productos WHERE idsubasta='$idSubasta'";
			$resultProducto = $conn->query($selectProducto);
			$selectLote = "SELECT nombre, descripcion FROM lotes WHERE idsubasta='$idSubasta'";
			$resultLote = $conn->query($selectLote);
			
			if($resultProducto->num_rows > 0){
		
				while($rowProducto= $resultProducto->fetch_assoc()) {
				
					$nombreProducto = $rowProducto['nombre'];
					$descripcionProducto = $rowProducto['descripcion'];
					
					echo "Producto a subastar: ".$nombreProducto."\n";
					echo "Descripcion: ".$descripcionProducto;
				}	
			}

			else if ($resultLote->num_rows > 0){
		
				while($rowLote= $resultLote->fetch_assoc()) {
				
					$nombreLote = $rowLote['nombre'];
					$descripcionLote = $rowLote['descripcion'];
					
					echo "Lote a subastar: ".$nombreLote."\n";
					echo "Descripcion: ".$descripcionLote;
				}	
			}
			
			if(session_id() == '') {
				session_start();
			}
			
			$tipoUsuario;
			if(array_key_exists('subastador', $_SESSION['user'])){
				
				$tipoUsuario = "subastador";
			}
			else if(array_key_exists('postor', $_SESSION['user'])){
				
				$tipoUsuario = "postor";
			}
			
			visualizarPujas($idSubasta,$tipoUsuario);
			
			$selectPujas = "SELECT * FROM pujas WHERE idsubasta='$idSubasta'";
			$resultPujas = $conn->query($selectPujas);
			
			if ($resultPujas->num_rows > 0){ //Si se han realizado pujas, se comprueba quién ha ganado la subasta			
				comprobarGanador($fechaCierre, $idSubasta, $tipoSubasta);
			}
		}		
	}
	
	function visualizarPujas($idSubasta, $tipoUsuario){
		
		$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		
		if($tipoUsuario == "postor"){
		
			$selectPujas = "SELECT * FROM pujas WHERE idsubasta='$idSubasta' AND idpostor='".$_SESSION['user']['postor']."'";
			$resultPujas = $conn->query($selectPujas);
			
			if($resultPujas->num_rows == 0){ //Si ya ha realizado una puja, no puede hacer más
				pujar($idSubasta);
			}
		}
		
		else if($tipoUsuario == "subastador"){
			
			$selectPujas = "SELECT * FROM pujas WHERE idsubasta='$idSubasta'";
			$resultPujas = $conn->query($selectPujas);
		}
		
		if ($resultPujas->num_rows > 0){
		
			while($rowPuja= $resultPujas->fetch_assoc()) {
			
				$fechaPuja = $rowPuja['fecha'];
				$cantidadPuja = $rowPuja['cantidad'];
				
				?>
				
				<br/><br/>PUJAS
				
				<?php 
				echo "Fecha de la puja: ".$fechaPuja."\n";
				echo "Cantidad pujada: ".$cantidadPuja."\n";
			}	
		}	
	}
	
	
	function pujar($idSubasta){

		$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
			?>
			
			<form id='pujar' action="sobreCerrado.php?id=<?php echo $idSubasta;?>" method='post' accept-charset='UTF-8'>
				<input type='number' name='puja' id='puja' placeholder="Cantidad a pujar" step='0.01' min='0' />
				<button name = 'pujar'> Pujar </button>	
			</form>
		<?php
	
		if(isset($_POST['puja'])){
			$fecha = date("Y-m-d H:m:s");
			$cantidad = $_POST['puja'];
			
			$insertPuja = ("INSERT INTO pujas (fecha, cantidad, idsubasta, idpostor) VALUES ('$fecha', '$cantidad', '$idSubasta', '".$_SESSION['user']['postor']."')");
			$conn->query($insertPuja);
		}
	}
	
	function comprobarGanador($fechaCierre, $idSubasta, $tipoSubasta){
		
		$fechaActual = date("Y-m-d H:m:s");
		//if($fechaCierre == $fechaActual){
			
			$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
			$arrayIdPujas = array();	
			$arrayCantidadPujas = array();
			$selectPujas = "SELECT id, cantidad FROM pujas WHERE idsubasta='$idSubasta'";
			$resultPujas = $conn->query($selectPujas);
			
			if ($resultPujas->num_rows > 0){
		
				while($rowPuja= $resultPujas->fetch_assoc()) {
					
					$idPuja = $rowPuja['id'];
					$cantidadPuja = $rowPuja['cantidad'];
					array_push($arrayIdPujas, $idPuja);
					array_push($arrayCantidadPujas, $cantidadPuja);
				}
			}			
		//}
		
		array_multisort($arrayCantidadPujas, $arrayIdPujas); //Ordenar los arrays de menor a mayor por cantidad
		
		
		if($tipoSubasta == 7){ //De primer precio ascendente
		
			echo "La puja ganadora es: ".$arrayIdPujas[count($arrayIdPujas)-1]; //La ganadora es la puja con mayor cantidad.
			echo "Con la cantidad de: ".$arrayCantidadPujas[count($arrayIdPujas)-1]; //Paga el precio más alto
		}
		else if($tipoSubasta == 8){ //De primer precio descendente
		
			echo "La puja ganadora es: ".$arrayCantidadPujas[0]; //La puja ganadora es la puja más baja
			echo "Con la cantidad de: ".$arrayCantidadPujas[0]; //Paga el segundo precio más bajo
		}
		else if($tipoSubasta == 9){ //De segundo precio ascendente
		
			echo "La puja ganadora es: ".$arrayCantidadPujas[count($arrayIdPujas)-1]; //La puja ganadora es la puja más alta
			
			//Comprobar que hay más de una puja
			if(count($arrayIdPujas) >=2){
				echo "Con la cantidad de: ".$arrayCantidadPujas[count($arrayIdPujas)-2]; //Paga el segundo precio más alto
			}
			else{ //Si no hay más pujas paga lo que él haya pujado
				
				echo "Con la cantidad de: ".$arrayCantidadPujas[0];
			}
		}
		else if($tipoSubasta == 10) { //De segundo precio descendente
		
			echo "La puja ganadora es: ".$arrayCantidadPujas[0]; //La puja ganadora es la puja más baja
			
			//Comprobar que hay más de una puja
			if(count($arrayIdPujas) >=2){
				echo "Con la cantidad de: ".$arrayCantidadPujas[1]; //Paga el segundo precio más bajo
			}
			else{ //Si no hay más pujas paga lo que él haya pujado
				
				echo "Con la cantidad de: ".$arrayCantidadPujas[0];
			}
		
		}
		
		
	}

	?>

