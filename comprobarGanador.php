<?php	
    include("escribirLog.php");
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
                    
                    //escribir en el log
                    $queryFinSubasta = "SELECT * FROM log WHERE descripcion = 'La subasta ".$idSubasta." ha finalizado.'";
                    $resultQueryFinSubasta = $conn ->query($queryFinSubasta);
                    if($resultQueryFinSubasta->num_rows == 0){                              
                        $queryBuscarProd = "SELECT id FROM productos WHERE idsubasta='$idSubasta' ";
                        $resultNombreProd = $conn->query( $queryBuscarProd);
                        if($resultNombreProd->num_rows > 0){
                            $rowNombreProd = $resultNombreProd->fetch_assoc();
                            $idprod = $rowNombreProd['id'];
                            escribirLog("La subasta ".$idSubasta." ha finalizado.", "NULL", $idSubasta, $idprod, "NULL", "NULL");
                        }else{
                            $queryBuscarLote= "SELECT id FROM lotes WHERE idsubasta='$idSubasta' ";
                            $resultNombreLote = $conn->query( $queryBuscarLote);
                            $rowNombreLote = $resultNombreLote->fetch_assoc();
                            $idlote = $rowNombreLote['id'];
                            escribirLog("La subasta ".$idSubasta." ha finalizado.", "NULL", $idSubasta, "NULL", $idlote,  "NULL");
                        }
                        
                    }
                    //fin de escribir en el log
                   
					
					if ($resultPujas->num_rows > 0){
				
						while($rowPuja= $resultPujas->fetch_assoc()) {
							
							$idPuja = $rowPuja['id'];
							$cantidadPuja = $rowPuja['cantidad'];
							
							if (in_array($cantidadPuja, $arrayCantidadPujas)) { //Si ya existe una puja con esa cantidad en el array, no se añade
								
							}
							else{
								
								array_push($arrayIdPujas, $idPuja);
								array_push($arrayCantidadPujas, $cantidadPuja);
								//echo (count($arrayCantidadPujas));
								//echo (count($arrayIdPujas));
								
							}
						}
						
						array_multisort($arrayCantidadPujas, $arrayIdPujas); //Ordenar los arrays de menor a mayor por cantidad
		
		                $idpujaganadora='';
                        $cantidadpujaganadora='';
                        
						if($tipoSubasta == 7){ //De primer precio ascendente
							?>
							<label style="position: absolute; left: 435px; top: 400px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold; "> *El usuario ganador es <?php echo userPujador($arrayIdPujas[count($arrayIdPujas)-1], $conn); ?> con la cantidad de <?php echo $arrayCantidadPujas[count($arrayIdPujas)-1]; ?> euros* </label> 
							<?php
							//La ganadora es la puja con mayor cantidad
							//Paga el precio más alto
                            $idpujaganadora=$arrayIdPujas[count($arrayIdPujas)-1];
                            $cantidadpujaganadora=$arrayCantidadPujas[count($arrayIdPujas)-1];
						}
						else if($tipoSubasta == 8){ //De primer precio descendente
							?>
							<label style="position: absolute; left: 435px; top: 400px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold; "> *El usuario ganador es <?php echo userPujador($arrayIdPujas[0], $conn); ?> con la cantidad de <?php echo $arrayCantidadPujas[0]; ?> euros* </label> 
							<?php
							//Paga el segundo precio más bajo
                            $idpujaganadora=$arrayIdPujas[0];
                            $cantidadpujaganadora=$arrayCantidadPujas[0];
						}
						else if($tipoSubasta == 9){ //De segundo precio ascendente
							?>
								<label style="position: absolute; left: 435px; top: 400px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold; "> *El usuario ganador es <?php echo userPujador($arrayIdPujas[count($arrayIdPujas)-1], $conn); ?> </label> 
							<?php
							//La puja ganadora es la puja más alta
                            $idpujaganadora=$arrayIdPujas[count($arrayIdPujas)-1];
                            
							
							//Comprobar que hay más de una puja
							if(count($arrayIdPujas) >=2){
								?>
									<label style="position: absolute; left: 435px; top: 400px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold; "> con la cantidad de <?php echo $arrayCantidadPujas[count($arrayIdPujas)-2]; ?>* </label> 
								<?php
								//Paga el segundo precio más alto
                                $cantidadpujaganadora = $arrayCantidadPujas[count($arrayIdPujas)-2];
							}
							else{ //Si no hay más pujas paga lo que él haya pujado
								?>
									<label style="position: absolute; left: 435px; top: 400px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold; "> con la cantidad de <?php echo $arrayCantidadPujas[0]; ?>* </label> 
								<?php
                                $cantidadpujaganadora = $arrayCantidadPujas[0];
							}
						}
						else if($tipoSubasta == 10) { //De segundo precio descendente
							?>
								<label style="position: absolute; left: 435px; top: 400px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold; "> *El usuario ganador es <?php echo userPujador($arrayIdPujas[0], $conn); ?> </label> 
							<?php
							//La puja ganadora es la puja más baja
							$idpujaganadora = $arrayIdPujas[0];
                            
							//Comprobar que hay más de una puja
							if(count($arrayIdPujas) >=2){
								?>
									<label style="position: absolute; left: 435px; top: 400px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold; "> con la cantidad de <?php echo $arrayCantidadPujas[1]; ?>* </label> 
								<?php
								//Paga el segundo precio más bajo
                                $cantidadpujaganadora = $arrayCantidadPujas[1];
							}
							else{ //Si no hay más pujas paga lo que él haya pujado
								?>
									<label style="position: absolute; left: 435px; top: 400px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold; "> con la cantidad de <?php echo $arrayCantidadPujas[0]; ?>* </label> 
								<?php
                                $cantidadpujaganadora = $arrayCantidadPujas[0];
							}
						
						}
                        
						//escribir en el log
                        $queryFinSubasta = "SELECT * FROM log WHERE descripcion = 'La puja ganadora de la subasta ".$idSubasta." es ".$cantidadpujaganadora."€.'";
                        $resultQueryFinSubasta = $conn ->query($queryFinSubasta);
                        
                        $queryNombreUsuario= ("SELECT idpostor FROM pujas WHERE id ='$idPuja'");
                        $resultNombreUsuario = $conn->query( $queryNombreUsuario);
                        $rowNombreUsuario = $resultNombreUsuario->fetch_assoc();
                        $nombreUsuario = $rowNombreUsuario['idpostor'];
                        
                        if($resultQueryFinSubasta->num_rows == 0){
                            $queryBuscarProd = "SELECT id FROM productos WHERE idsubasta='$idSubasta' ";
                            $resultNombreProd = $conn->query( $queryBuscarProd);
                            if($resultNombreProd->num_rows > 0){
                                $rowNombreProd = $resultNombreProd->fetch_assoc();
                                $idprod = $rowNombreProd['id'];
                                escribirLog("La puja ganadora de la subasta ".$idSubasta." es ".$cantidadpujaganadora."€.",$nombreUsuario, $idSubasta, $idprod, "NULL", $idpujaganadora);
                            }else{
                                $queryBuscarLote= "SELECT id FROM lotes WHERE idsubasta='$idSubasta' ";
                                $resultNombreLote = $conn->query( $queryBuscarLote);
                                $rowNombreLote = $resultNombreLote->fetch_assoc();
                                $idlote = $rowNombreLote['id'];
                                escribirLog("La puja ganadora de la subasta ".$idSubasta." es ".$cantidadpujaganadora."€.", $nombreUsuario, $idSubasta, "NULL", $idlote, $idpujaganadora);
                            }
                        
                        }
                        //fin de escribir en el log
                        
					}
					
					else{
						?>
						<label style="position: absolute; left: 515px; top: 535px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold; "> *La subasta ha finalizado sin pujas* </label>
						<?php
						echo "";
                        //escribir en el log
                            $queryFinSubasta = "SELECT * FROM log WHERE descripcion = 'La subasta ".$idSubasta." ha finalizado sin pujas.'";
                            $resultQueryFinSubasta = $conn ->query($queryFinSubasta);
                            if($resultQueryFinSubasta->num_rows == 0){
                         
                               $queryBuscarProd = "SELECT id FROM productos WHERE idsubasta='$idSubasta' ";
                                $resultNombreProd = $conn->query( $queryBuscarProd);
                                if($resultNombreProd->num_rows > 0){
                                    $rowNombreProd = $resultNombreProd->fetch_assoc();
                                    $idprod = $rowNombreProd['id'];
                                    escribirLog("La subasta ".$idSubasta." ha finalizado sin pujas.", "NULL", $idSubasta, $idprod, "NULL", "NULL");
                                }else{
                                    $queryBuscarLote= "SELECT id FROM lotes WHERE idsubasta='$idSubasta' ";
                                    $resultNombreLote = $conn->query( $queryBuscarLote);
                                    $rowNombreLote = $resultNombreLote->fetch_assoc();
                                    $idlote = $rowNombreLote['id'];
                                    escribirLog("La subasta ".$idSubasta." ha finalizado sin pujas.", "NULL", $idSubasta, "NULL", $idlote, "NULL");
                                }
                        
                            }
                            //fin de escribir en el log
					}
				}
			}
			
		}
		
	}
	
	function userPujador($idPuja, $conn){
        $selectPujas = "SELECT idpostor FROM pujas WHERE id='$idPuja'";
        $resultPujas= $conn->query($selectPujas);
        $row=$resultPujas->fetch_assoc();
        $idPostor = $row['idpostor'];
        $selectUser = "SELECT usuario FROM usuarios WHERE id='$idPostor'";
        $resultUser= $conn->query($selectUser);
        $row=$resultUser->fetch_assoc();
        $user= $row['usuario'];
        
        return $user;	
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
                    
                    //escribir en el log
                    $queryFinSubasta = "SELECT * FROM log WHERE descripcion = 'La subasta ".$idSubasta." ha finalizado.'";
                    $resultQueryFinSubasta = $conn ->query($queryFinSubasta);
                    if($resultQueryFinSubasta->num_rows == 0){
                            
                        $queryBuscarProd = "SELECT id FROM productos WHERE idsubasta='$idSubasta' ";
                        $resultNombreProd = $conn->query( $queryBuscarProd);
                        if($resultNombreProd->num_rows > 0){
                            $rowNombreProd = $resultNombreProd->fetch_assoc();
                            $idprod = $rowNombreProd['id'];
                            escribirLog("La subasta ".$idSubasta." ha finalizado.", "NULL", $idSubasta, $idprod, "NULL", "NULL");
                        }else{
                            $queryBuscarLote= "SELECT id FROM lotes WHERE idsubasta='$idSubasta' ";
                            $resultNombreLote = $conn->query( $queryBuscarLote);
                            $rowNombreLote = $resultNombreLote->fetch_assoc();
                            $idlote = $rowNombreLote['id'];
                            escribirLog("La subasta ".$idSubasta." ha finalizado.", "NULL", $idSubasta, "NULL", $idlote,  "NULL");
                        }
                        
                    }
                    //fin de escribir en el log
					
					if ($resultPujas->num_rows > 0){
							$rowPuja=$resultPujas->fetch_assoc();
							$idPuja = $rowPuja['id'];
							$cantidadPuja = $valorPujaFinal;
							
							?>
								<label style="position: absolute; left: 415px; top: 335px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold; "> *El usuario ganador de la subasta es <?php echo userPujador($idPuja, $conn); ?> con la cantidad de <?php echo $valorPujaFinal; ?> euros* </label>
							<?php
                        
                            //escribir en el log
                            $queryFinSubasta = "SELECT * FROM log WHERE descripcion = 'La puja ganadora de la subasta "  .$idSubasta.  " es " .$valorPujaFinal. "€.'";
                            $resultQueryFinSubasta = $conn ->query($queryFinSubasta);
                        
                            $queryNombreUsuario= ("SELECT idpostor FROM pujas WHERE id ='$idPuja'");
                            $resultNombreUsuario = $conn->query( $queryNombreUsuario);
                            $rowNombreUsuario = $resultNombreUsuario->fetch_assoc();
                            $nombreUsuario = $rowNombreUsuario['idpostor'];
                        
                            if($resultQueryFinSubasta->num_rows == 0){
                               $queryBuscarProd = "SELECT id FROM productos WHERE idsubasta='$idSubasta' ";
                                $resultNombreProd = $conn->query( $queryBuscarProd);
                                if($resultNombreProd->num_rows > 0){
                                    $rowNombreProd = $resultNombreProd->fetch_assoc();
                                    $idprod = $rowNombreProd['id'];
                                    escribirLog("La puja ganadora de la subasta ".$idSubasta." es ".$valorPujaFinal."€.", $nombreUsuario, $idSubasta, $idprod, "NULL", $idPuja);
                                }else{
                                    $queryBuscarLote= "SELECT id FROM lotes WHERE idsubasta='$idSubasta' ";
                                    $resultNombreLote = $conn->query( $queryBuscarLote);
                                    $rowNombreLote = $resultNombreLote->fetch_assoc();
                                    $idlote = $rowNombreLote['id'];
                                    escribirLog("La puja ganadora de la subasta ".$idSubasta." es ".$valorPujaFinal."€.", $nombreUsuario, $idSubasta, "NULL", $idlote, $idPuja);
                                }
                        
                            }
                            //fin de escribir en el log
													
					}
					
					else{
						
						?>
						<label style="position: absolute; left: 515px; top: 535px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold; "> *La subasta ha finalizado sin pujas* </label>
						<?php
                         //escribir en el log
                            $queryFinSubasta = "SELECT * FROM log WHERE descripcion = 'La subasta ".$idSubasta." ha finalizado sin pujas.'";
                            $resultQueryFinSubasta = $conn ->query($queryFinSubasta);
                            if($resultQueryFinSubasta->num_rows == 0){
                        
                                $queryBuscarProd = "SELECT id FROM productos WHERE idsubasta='$idSubasta' ";
                                $resultNombreProd = $conn->query( $queryBuscarProd);
                                if($resultNombreProd->num_rows > 0){
                                    $rowNombreProd = $resultNombreProd->fetch_assoc();
                                    $idprod = $rowNombreProd['id'];
                                    escribirLog("La subasta ".$idSubasta." ha finalizado sin pujas.", "NULL", $idSubasta, $idprod, "NULL", "NULL");
                                }else{
                                    $queryBuscarLote= "SELECT id FROM lotes WHERE idsubasta='$idSubasta' ";
                                    $resultNombreLote = $conn->query( $queryBuscarLote);
                                    $rowNombreLote = $resultNombreLote->fetch_assoc();
                                    $idlote = $rowNombreLote['id'];
                                    escribirLog("La subasta ".$idSubasta." ha finalizado sin pujas.", "NULL", $idSubasta, "NULL", $idlote, "NULL");
                                }
                        
                            }
                            //fin de escribir en el log
					}
				}
			}
			
		}		
	}
?>