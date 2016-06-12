<?php
	
	$fechasegundapuja;
	if(isset($_GET['id'])){
        $idSubasta = $_GET['id'];
    }
    $conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
    
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
	echo $tipoUsuario;
	$select = "SELECT fechainicio, fechacierre, fechasegundapuja, tipo FROM subastas WHERE id='$idSubasta'";
    $result = $conn->query($select);
	
	if($result->num_rows>0){
		while($row = $result->fetch_assoc()){
			$fechainicio = $row['fechainicio'];
			$fechasegundapuja = $row['fechasegundapuja'];
			$fechacierre = $row['fechacierre'];
			$tipoSubasta = $row['tipo'];
		}
	}
	$fechaActual = '';
	$fechaActual = date("Y-m-d H:i:s");
	
	echo "<br>";
	echo "Inicio:  ".$fechainicio;
	echo "<br>";
	echo "Segun:  ".$fechasegundapuja;
	echo "<br>";
	echo "Cierre: ".$fechacierre;
	echo "<br>";
	$posicionFecha;
	
	include("valorMinimo.php");
	
	if($tipoUsuario == "postor"){
		$haPujado = haPujado_SI_NO($idSubasta);
		
		if(strtotime($fechaActual) < strtotime($fechainicio)){ 
			?><br><p>La subasta no ha comenzado aun</p><br><?php
			$posicionFecha = 1;
		}else if((strtotime($fechaActual) >= strtotime($fechainicio)) && (strtotime($fechaActual) < strtotime($fechasegundapuja))){
			$posicionFecha = 2;
			echo "SUBASTA EMPEZADA";
			echo !empty($haPujado);
			if (!empty($haPujado)){
				echo "<table><tr><td>Fecha</td><td>Puja</td></tr>";
				while($row = $haPujado->fetch_assoc()){
					echo "<tr><td>".$row['fecha']."</td><td>".$row['cantidad']."</td></tr>'";
				}
				echo "</table>";
				
			}else{
				
				pujar($idSubasta, $tipoSubasta, $_SESSION['user']['postor'], "puja");
			}
		}else if(strtotime($fechaActual) == strtotime($fechasegundapuja)){
			$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
			
			$update = "UPDATE subastas SET cantidadsegundapuja='".valorMinimoRR($idSubasta)."' WHERE id='$idSubasta'";
			$result = $conn->query($update);
		}else if((strtotime($fechaActual) >= strtotime($fechasegundapuja)) &&  (strtotime($fechaActual) < strtotime($fechacierre))){
			if($tipoSubasta==11){
				echo "<br>";
				echo "<p>La puja ganadora hasta el momento es de: ".cantidadSegundaPuja($idSubasta)." euros. Puede realizar otra unica puja mayor que la actual.";
			}else if($tipoSubasta==12){
				echo "<br>";
				echo "<p>La puja ganadora hasta el momento es de: ".cantidadSegundaPuja($idSubasta)." euros. Puede realizar otra unica puja menor que la actual.";
			}
			$posicionFecha = 3;
			if(empty($haPujado)){
				?><p>Ya no puede pujar, porque no participo en la primera ronda</p><?php
			}else{
				if($haPujado->num_rows == 1){
					pujar($idSubasta, $tipoSubasta, $_SESSION['user']['postor'], "pujaSegunda");
					echo "<table><tr><td>Fecha</td><td>Puja</td></tr>";
					while($row = $haPujado->fetch_assoc()){
						echo "<tr><td>".$row['fecha']."</td><td>".$row['cantidad']."</td></tr>'";
					}
				echo "</table>";
				}else if($haPujado->num_rows == 2){
					echo "<table><tr><td>Fecha</td><td>Puja</td></tr>";
					while($row = $haPujado->fetch_assoc()){
						echo "<tr><td>".$row['fecha']."</td><td>".$row['cantidad']."</td></tr>'";
					}
					echo "</table>";
				}else{
					echo "Ha pujado ".$haPujado->num_rows." veces. WTF";
				}
				
			}
		}else if(strtotime($fechaActual) >= strtotime($fechacierre)){
			//Subasta finalizada, mostrar ganador
			?><p>Subasta finalizada</p><?php
			listaPujas($idSubasta);
			$posicionFecha = 4;
			echo "La puja ganadora de esta subasta es de: ".valorMinimoRR($idSubasta)." euros.";
		}
		echo $posicionFecha;
	}
	if($tipoUsuario == "subastador"){
		if(strtotime($fechaActual) < strtotime($fechainicio)){ 
			echo "<br><p>La subasta no ha comenzado aun</p><br>";
			$posicionFecha = 1;
		}else if((strtotime($fechaActual) >= strtotime($fechainicio)) && (strtotime($fechaActual) < strtotime($fechasegundapuja))){
			$posicionFecha = 2;
			listaPujas($idSubasta);
		}else if(strtotime($fechaActual) == strtotime($fechasegundapuja)){
			$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
			
			$update = "UPDATE subastas SET cantidadsegundapuja='".valorMinimoRR($idSubasta)."' WHERE id='$idSubasta'";
			$result = $conn->query($update);
		}else if((strtotime($fechaActual) >= strtotime($fechasegundapuja)) &&  (strtotime($fechaActual) < strtotime($fechacierre))){
			if($tipoSubasta==11){
				echo "<br>";
				echo "<p>La puja ganadora hasta el momento es de: ".cantidadSegundaPuja($idSubasta)." euros.";
			}else if($tipoSubasta==12){
				echo "<br>";
				echo "<p>La puja ganadora hasta el momento es de: ".cantidadSegundaPuja($idSubasta)." euros.";
			}
			$posicionFecha = 3;
			listaPujas($idSubasta);
		}else if(strtotime($fechaActual) >= strtotime($fechacierre)){
			//Subasta finalizada, mostrar ganador
			?><p>Subasta finalizada</p><?php
			listaPujas($idSubasta);
			$posicionFecha = 4;
			echo "La puja ganadora de esta subasta es de: ".valorMinimoRR($idSubasta)." euros.";
		}
	}
	
	function haPujado_SI_NO($idSubasta){

		$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		
		$select = "SELECT fecha, cantidad FROM pujas WHERE idsubasta='$idSubasta' AND idpostor='".$_SESSION['user']['postor']."'";
		$result = $conn->query($select);
		if($result->num_rows>0){
			return $result;		
		}else{
			return false;
		}
	}
	function listaPujas($idSubasta){

		$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		
		$select = "SELECT fecha, cantidad ,idpostor FROM pujas WHERE idsubasta='$idSubasta'";
		$result = $conn->query($select);
		if($result->num_rows>0){
			echo "<table><tr><td>Fecha</td><td>Puja</td></tr>";
			while($row = $result->fetch_assoc()){
				echo "<tr><td>".$row['fecha']."</td><td>".$row['cantidad']."</td></tr>'";
			}
			echo "</table>";		
		}else{
			echo "No hay pujas";
		}
	}
	
	function pujar($idSubasta, $tipoSubasta, $idUser, $momento){//$momento = "puja" si primera ronda y ="pujaSegunda" si segunda ronda

			?>
			
			<form id='pujar' action="roundRobin.php?id=<?php echo $idSubasta;?>" method='post' accept-charset='UTF-8'>
				<input type='number' name='<?php echo $momento; ?>' id='puja' placeholder="Cantidad a pujar" step='0.01' min='0' />
				<button name = 'pujar'> Pujar </button>	
			</form>
		<?php
	
	}
	
?>
