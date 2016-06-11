<?php	
	
	if(isset($_GET['id'])){
		$idSubasta = $_GET['id'];
	}
		
	$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
	
		
	$selectSubasta = "SELECT fechacierre, tipo FROM subastas WHERE id='$idSubasta'";
	$result = $conn->query($selectSubasta);
	$tipoSubasta;
	if($result->num_rows > 0){
			
		while($row = $result->fetch_assoc()){				
				$tipoSubasta = $row['tipo'];
		}
	}
	if($tipoSubasta == 1 || $tipoSubasta == 2 || $tipoSubasta == 3 || $tipoSubasta == 4){
        comprobarGanadorDinamica($tipoSubasta, $idSubasta);
    }
                    
	else if($tipoSubasta == 5 || $tipoSubasta == 6){
		comprobarGanadorHolandesa();
	}
                    
	else if($tipoSubasta == "7" | $tipoSubasta == "8" | $tipoSubasta == "9" | $tipoSubasta == "10"){
		comprobarGanadorSobreCerrado($tipoSubasta, $idSubasta);
	}
	
	function comprobarGanadorSobreCerrado($tipoSubasta, $idSubasta){
       	
		$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		if(session_id() == '') {
			session_start();
		}
		
		$selectSubasta = "SELECT fechacierre, tipo FROM subastas WHERE id='$idSubasta'";
		$result = $conn->query($selectSubasta);
		$arrayIdPujas = array();	
		$arrayCantidadPujas = array();
		if($result->num_rows > 0){
			
			while($row = $result->fetch_assoc()){
		
				$fechaActual = strtotime(date("Y-m-d H:i:s"));
				$fechaCierre = strtotime($row['fechacierre']);
				
				
				if($fechaActual >= $fechaCierre){
					
					$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
					$selectPujas = "SELECT id, cantidad FROM pujas WHERE idsubasta='$idSubasta'";
					$resultPujas = $conn->query($selectPujas);
					
					if ($resultPujas->num_rows > 0){
				
						while($rowPuja= $resultPujas->fetch_assoc()) {
							
							$idPuja = $rowPuja['id'];
							$cantidadPuja = $rowPuja['cantidad'];
							
							if (in_array($cantidadPuja, $arrayCantidadPujas)) { //Si ya existe una puja con esa cantidad en el array, no se añade
								
							}
							else{
								
								array_push($arrayIdPujas, $idPuja);
								array_push($arrayCantidadPujas, $cantidadPuja);
								echo (count($arrayCantidadPujas));
								echo (count($arrayIdPujas));
								
							}
						}
						
						array_multisort($arrayCantidadPujas, $arrayIdPujas); //Ordenar los arrays de menor a mayor por cantidad
		
		
						if($tipoSubasta == 7){ //De primer precio ascendente
						
							echo "La puja ganadora es: ".$arrayIdPujas[count($arrayIdPujas)-1]; //La ganadora es la puja con mayor cantidad.
							echo "Con la cantidad de: ".$arrayCantidadPujas[count($arrayIdPujas)-1]; //Paga el precio más alto
						}
						else if($tipoSubasta == 8){ //De primer precio descendente
						
							echo "La puja ganadora es: ".$arrayIdPujas[0]; //La puja ganadora es la puja más baja
							echo "Con la cantidad de: ".$arrayCantidadPujas[0]; //Paga el segundo precio más bajo
						}
						else if($tipoSubasta == 9){ //De segundo precio ascendente
						
							echo "La puja ganadora es: ".$arrayIdPujas[count($arrayIdPujas)-1]; //La puja ganadora es la puja más alta
							
							//Comprobar que hay más de una puja
							if(count($arrayIdPujas) >=2){
								echo "Con la cantidad de: ".$arrayCantidadPujas[count($arrayIdPujas)-2]; //Paga el segundo precio más alto
							}
							else{ //Si no hay más pujas paga lo que él haya pujado
								
								echo "Con la cantidad de: ".$arrayCantidadPujas[0];
							}
						}
						else if($tipoSubasta == 10) { //De segundo precio descendente
						
							echo "La puja ganadora es: ".$arrayIdPujas[0]; //La puja ganadora es la puja más baja
							
							//Comprobar que hay más de una puja
							if(count($arrayIdPujas) >=2){
								echo "Con la cantidad de: ".$arrayCantidadPujas[1]; //Paga el segundo precio más bajo
							}
							else{ //Si no hay más pujas paga lo que él haya pujado
								
								echo "Con la cantidad de: ".$arrayCantidadPujas[0];
							}
						
						}
						
					}
					
					else{
						
						echo "La subasta ha finalizado sin pujas";
					}
				}
			}
			
		}
		
	}
	
	
	
	function comprobarGanadorDinamica($tipoSubasta, $idSubasta){
		
		$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		if(session_id() == '') {
			session_start();
		}
		
		$selectSubasta = "SELECT fechacierre, tipo FROM subastas WHERE id='$idSubasta'";
		$result = $conn->query($selectSubasta);
		if($result->num_rows > 0){
			
			while($row = $result->fetch_assoc()){
		
				$fechaActual = strtotime(date("Y-m-d H:i:s"));
				$fechaCierre = strtotime($row['fechacierre']);
				
				
				if($fechaActual >= $fechaCierre){
					
					include("valorMinimo.php");
					$valorPujaFinal = valorMinimo($idSubasta);
					$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
					$selectPujas = "SELECT id FROM pujas WHERE cantidad='$valorPujaFinal'";
					$resultPujas = $conn->query($selectPujas);
					
					if ($resultPujas->num_rows > 0){
							$rowPuja=$resultPujas->fetch_assoc();
							$idPuja = $rowPuja['id'];
							$cantidadPuja = $valorPujaFinal;
							
							echo "La id de la puja ganadora es: ".$idPuja;
							echo "Con la cantidad de: ".$valorPujaFinal;
													
					}
					
					else{
						
						echo "La subasta ha finalizado sin pujas";
					}
				}
			}
			
		}		
	}
?>