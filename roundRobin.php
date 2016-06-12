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
	
	//BOTON DE VOLVER CUANDO ESTAS EN UNA SUBASTAS
	//*********************************************
	foreach (array_keys($_SESSION['user']) as $field)
	{
			?>
				<button onclick="location.href='<?php echo $field; ?>.php'"> Volver</button>
			<?php
			
	}
	//*********************************************
	
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
			
			
			
			?>
			<script type="text/javascript">
			
			var anterior = "";
			function compararDate(){
				var xhttp = new XMLHttpRequest();
					console.log(xhttp.status);
					xhttp.onreadystatechange = function () {
						if ((xhttp.readyState == 4) && (xhttp.status == 200)) {
							
							if(anterior!=xhttp.responseText){
								document.getElementById("contenido").innerHTML = xhttp.responseText;
							}
							
							anterior = xhttp.responseText;
							
						}
					};
					xhttp.open("GET", "gestionarRoundRobin.php?id=<?php echo $idSubasta; ?>", true);
					xhttp.send();
			}
			
			setInterval(function () {
                compararDate();
            }, 500);
			
			</script>
			
			<?php 
			
			//visualizarPujas($idSubasta, $tipoUsuario, $momento);
			
			
		}		
	}
	

	if(isset($_POST['puja'])){
			$fecha = date("Y-m-d H:i:s");
			$cantidad = $_POST['puja'];
			
			
				$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");	
									
					$date = date('Y-m-d H:i:s');
					$select = "INSERT INTO pujas (fecha, cantidad, idsubasta, idpostor) VALUES ('$date', '$cantidad', '$idSubasta', '".$_SESSION['user']['postor']."')";
					if ($conn->query($select) === TRUE) {
						?>
						<script type="text/javascript">
							alert('Usuario Puja Correcta');
						</script>
						<?php
						echo "";
					}
			
	}
	
	if(isset($_POST['pujaSegunda'])){
			$fecha = date("Y-m-d H:i:s");
			$cantidad = $_POST['pujaSegunda'];
			include("valorMinimo.php");
			if($tipoSubasta==11){
				//COMO ES ASCENDENTE TENEMOS QUE COMPROBAR QUE ES MAYOR QUE LA PUJA ACTUAL MAS ALTA
				?> <script>alert("HOLI");</script><?php
				if($cantidad>$cantidadSegundaPuja($idSubasta)){
					$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");	
									
					$date = date('Y-m-d H:i:s');
					$select = "INSERT INTO pujas (fecha, cantidad, idsubasta, idpostor) VALUES ('$date', '$cantidad', '$idSubasta', '".$_SESSION['user']['postor']."')";
					if ($conn->query($select) === TRUE) {
						?>
						<script type="text/javascript">
							alert('Usuario Puja Correcta');
						</script>
						<?php
						echo "";
					} else {
					   // echo "Error updating record: " . $conn->error;
					}
				}else{
					?>
						<script type="text/javascript">
							alert('La puja tiene un valor incorrecto!');
						</script>
					<?php
					echo "";
				}
			}else if($tipoSubasta==12){
				//COMO ES DESCENDENTE TENEMOS QUE COMPROBAR QUE ES MENOR QUE LA PUJA ACTUAL MAS BAJA
				if($cantidad<$cantidadSegundaPuja($idSubasta)){
					$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");	
									
					$date = date('Y-m-d H:i:s');
					$select = "INSERT INTO pujas (fecha, cantidad, idsubasta, idpostor) VALUES ('$date', '$cantidad', '$idSubasta', '".$_SESSION['user']['postor']."')";
					if ($conn->query($select) === TRUE) {
						?>
						<script type="text/javascript">
							alert('Usuario Puja Correcta');
						</script>
						<?php
						echo "";
					} else {
					   // echo "Error updating record: " . $conn->error;
					}
				}else{
					?>
						<script type="text/javascript">
							alert('La puja tiene un valor incorrecto!');
						</script>
					<?php
					echo "";
				}
			}
	}
	?>
	<div id="contenido" style="border:solid;"></div>
	<?php
			
?>