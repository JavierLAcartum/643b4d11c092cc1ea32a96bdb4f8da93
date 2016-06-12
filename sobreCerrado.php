
<div id="pujaFinalizada"> </div>
<div id="tablaPujas"> </div>

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
			
			comprobarSiHaPujado($idSubasta,$tipoUsuario);
			
			$selectPujas = "SELECT * FROM pujas WHERE idsubasta='$idSubasta'";
			$resultPujas = $conn->query($selectPujas);
		}		
	}
	
	function comprobarSiHaPujado($idSubasta, $tipoUsuario){
		
		$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		if($tipoUsuario == "postor"){
		
			$selectPujas = "SELECT * FROM pujas WHERE idsubasta='$idSubasta' AND idpostor='".$_SESSION['user']['postor']."'";
			$resultPujas = $conn->query($selectPujas);
			
			if($resultPujas->num_rows == 0){ //Si ya ha realizado una puja, no puede hacer más
				pujar($idSubasta);
			}
		}
			
	}
	
	
	function pujar($idSubasta){

		$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
			?>
			
			<form id='pujar' action="sobreCerrado.php?id=<?php echo $idSubasta;?>" method='post' accept-charset='UTF-8'>
				<input type='number' name='puja' id='puja' placeholder="Cantidad a pujar" step='0.01' min='0' />
				<input type = 'submit' name = 'pujar'> Pujar </button>	
			</form>
		<?php
	
		if(isset($_POST['puja'])){
			$fecha = date("Y-m-d H:i:s");
			$cantidad = $_POST['puja'];
			
			$insertPuja = ("INSERT INTO pujas (fecha, cantidad, idsubasta, idpostor) VALUES ('$fecha', '$cantidad', '$idSubasta', '".$_SESSION['user']['postor']."')");
			$conn->query($insertPuja);
			
			?>
			
			<script>
				document.getElementById('pujar').style.display='none'; //Si ya ha realizado una puja, se oculta el formulario para pujar
			</script>
			
			<?php
		}
	}

	?>

	<script type="text/javascript">
	
	function visualizarPujas() {
               
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if ((xhttp.readyState == 4) && (xhttp.status == 200)) {

						respuestaXhttp = xhttp.responseText;
                        document.getElementById("tablaPujas").innerHTML = respuestaXhttp;
						
                    }
                };
                xhttp.open("GET", "listaPujasSobreCerrado.php?id=<?php echo $idSubasta;?>", true);
                xhttp.send(); 
	}				
	
	        setInterval(function () {
                visualizarPujas();
			}, 500);
	
	var respuestaXhttp;
	function comprobarGanador() {
               
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if ((xhttp.readyState == 4) && (xhttp.status == 200)) {

						respuestaXhttp = xhttp.responseText;
                        document.getElementById("pujaFinalizada").innerHTML = respuestaXhttp;
						if(respuestaXhttp != ""){
							document.getElementById('pujar').style.display='none'; //Si ya ha realizado una puja, se oculta el formulario para pujar
						}
                    }
                };
                xhttp.open("GET", "comprobarGanador.php?id=<?php echo $idSubasta;?>", true);
                xhttp.send(); 
	}				
	
	        setInterval(function () {
                comprobarGanador();
			}, 500);
	</script>