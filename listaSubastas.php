<?php
		
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
	
	function pasarTipoSubastaAString($tipoSubasta){
		
		switch($tipoSubasta){
			case 1:
			return "Din&aacutemica descubierta ascendente";
			break;
			case 2: 
			return "Din&aacutemica descubierta descendente";
			break;
			case 3:
			return "Din&aacutemica an&oacutenima ascendente";
			break;
			case 4:
			return "Din&aacutemica an&oacutenima descendente";
			break;
			case 5:
			return "Din&aacutemica holandesa ascendente";
			break;
			case 6:
			return "Din&aacutemica holandesa descendente";
			break;
			case 7:
			return "Sobre cerrado primer precio ascendente";
			break;
			case 8:
			return "Sobre cerrado primer precio descendente";
			break;
			case 9:
			return "Sobre cerrado segundo precio ascendente";
			break;
			case 10:
			return "Sobre cerrado segundo precio descendente";
			break;
			case 11:
			return "Round Robin ascendente";
			break;
			case 12:
			return "Robin Robin descendente";
			break;
			
		}
		
	}
	
	function crearTablaSubastas($tipoUsuario){
		
		$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		$selectSubastas;
		$resultSubastas;
		
		if(session_id() == '') {
			session_start();
		}
		
		if($tipoUsuario == ""){
			$selectSubastas = "SELECT * FROM subastas ORDER BY fechacierre DESC";
			$resultSubastas = $conn->query($selectSubastas);
			crearDivSubasta($resultSubastas, $conn);
		}
				
		else if($tipoUsuario == 'subastador'){
			$selectSubastas = "SELECT * FROM subastas WHERE idsubastador = '".$_SESSION['user']['subastador']."' ORDER BY fechacierre DESC";
			$resultSubastas = $conn->query($selectSubastas);
			crearDivSubasta($resultSubastas, $conn);
			
		}else if($tipoUsuario == 'postor'){
			$selectPujas = "SELECT DISTINCT idsubasta FROM pujas WHERE idpostor = '".$_SESSION['user']['postor']."'";
			$resultPujas = $conn->query($selectPujas);
			if($resultPujas->num_rows > 0){//LISTA DE SUBASTAS
				while($rowPujas = $resultPujas->fetch_assoc()) {
					echo "<br>BIENVENIDO AL HISTORIAL DE SUBASTAS, AQUI APARECEN TODAS LAS SUBASTAS EN LAS QUE UDS. HA PUJADO, SELECCIONE LA QUE QUIERA PARA VER SUS LAS PUJAS EN DICHA SUBASTA<br>";
					$selectSubastas = "SELECT * FROM subastas WHERE id = '".$rowPujas['idsubasta']."'";
					$resultSubastas = $conn->query($selectSubastas);
					crearDivSubasta($resultSubastas, $conn);
				}
			}
		}
		
			
	}
	
	function crearDivSubasta($resultSubastas, $conn){
		if($resultSubastas->num_rows > 0){//LISTA DE SUBASTAS
				while($rowSubasta = $resultSubastas->fetch_assoc()) {//ITERACION SOBRE LAS SUBASTAS
					//VARIABLES A MOSTRAR
					//**************************************************************************
					$idSubasta = '';
					$tipoSubasta;
					$fechaIniSubasta; //fecha inicio subasta
					$fechaFinSubasta; //fecha fin subasta
                    $tipoSubastaPhp = ''; //Guarda URL del tipo de subasta
					
					$producto_lote = -1; //Variable para fijar si es un producto = 0 y si es lote = 1. Por si acaso luego queremos saber en que tabla buscar
					$nombreObjeto = ''; 
					$descripcionObjeto = ''; //Si es un lote no tiene descripcion
					$imagenObjeto; //Si es un lote no tiene imagen
					$arrayProductos = array(); //Array que contiene la id de los productos si es un lote
					
					$nombreSubastador = '';
					$apellidosSubastador = '';
					
					$pujasRealizadas; //Es un array sobre el que hay que iterar, if($resultSubastas->num_rows > 0){while($rowSubasta = $resultSubastas->fetch_assoc()) {
					$pujaActual;
					//****************************************************************************
					//****************************************************************************
					
					$idSubasta = $rowSubasta['id'];
					$tipoSubasta = $rowSubasta['tipo'];
					$fechaIniSubasta = $rowSubasta['fechainicio'];
					$fechaFinSubasta = $rowSubasta['fechacierre'];
					
					$selectSubastador = "SELECT * FROM usuarios WHERE id='".$rowSubasta['idsubastador']."'";
					$resultSubastador = $conn->query($selectSubastador);
					
					$selectProductos = "SELECT * FROM productos WHERE idsubasta='".$idSubasta."'";
					$resultProductos = $conn->query($selectProductos);
					
					$selectLotes = "SELECT * FROM lotes WHERE idsubasta='".$idSubasta."'";
					$resultLotes = $conn->query($selectLotes);
					
					$selectPujas = "SELECT * FROM pujas WHERE idsubasta='".$idSubasta."' ORDER BY fecha DESC";
					$resultPujas = $conn->query($selectPujas);
					$pujasRealizadas = $resultPujas;
					
					if($resultSubastador->num_rows == 1){
						$rowSubastador = $resultSubastador->fetch_assoc();
						
						$nombreSubastador = $rowSubastador['nombre'];
						$apellidosSubastador = $rowSubastador['apellidos'];
					}
					if($resultProductos->num_rows == 1){
						$rowProductos = $resultProductos->fetch_assoc();
						
						$producto_lote = 0;
						$nombreObjeto = $rowProductos['nombre'];
						$descripcionObjeto = $rowProductos['descripcion'];
						$imagenObjeto = $rowProductos['imagen'];
					}else{
						if($resultProductos->num_rows != 0){
							$producto_lote = 1;
							while($rowProductos = $resultProductos->fetch_assoc()) {
								array_push($arrayProductos, $rowProductos['nombre']);
							}
						}
					}					
					
					$paginaSubastas;
					
					 if($tipoSubasta == 1 || $tipoSubasta == 2 || $tipoSubasta == 3 || $tipoSubasta == 4){
                        $tipoSubastaPhp = "dinamicaDescAscendente";
                    }
                    
                    if($tipoSubasta == 5 || $tipoSubasta == 6){
                        $tipoSubastaPhp = "subastaHolandesa";
                    }
                    
					if($tipoSubasta == "7" | $tipoSubasta == "8" | $tipoSubasta == "9" | $tipoSubasta == "10"){
						$tipoSubastaPhp = "sobreCerrado";
					}
				?>
				

				<!-- TABLA -->
					<table style="width:100%; padding: 30px; margin-top: 10px; font-family:'Segoe UI'; border: 1px solid black;">
					    <tr>

										
								<td style="width: 250px; text-align: center;"><?php echo pasarTipoSubastaAString($tipoSubasta); ?></td>
								<td style="width: 100px; text-align: center;"><?php echo $idSubasta ?></td>
								 							
								<td style="font-size: 12px; width:140px; text-align: center;"><?php if($nombreObjeto!=''){ echo $nombreObjeto; } else{ echo "*Sin nombre*";} ?></td>
								<td style="font-size: 12px; width: 135px; text-align: center;"><?php if($imagenObjeto!=null){ echo "<img src='".$imagenObjeto."' width='100px' height='75px'>"; } else{ echo "*Sin imagen*";} ?></td> <!--isset($imagenObjeto)-->
													
								<td style="width: 200px; text-align: center;"> <?php echo $fechaFinSubasta; ?></td> 							
								<td style="width: 170px; text-align: center;"><?php if($producto_lote==0){echo "Producto"; }else{ echo "Lote";} ?></td>
								
								<?php
								
								if(session_id() == '') {
									session_start();
								}
								if(array_key_exists('user', $_SESSION)){ //Si no está logueado, no puede acceder a las subastas
									if(array_key_exists('subastador', $_SESSION['user'])){ //Si es un subastador puede ver la subasta pero no pujar
									?>
										<td style="width: 150px; text-align: center;">
										<a href="<?php echo $tipoSubastaPhp ?>.php?id=<?php echo $idSubasta; ?>">
										<?php echo "Ver subasta"?> 
									</a>
									</td>
									<?php
									}
									else if(array_key_exists('postor', $_SESSION['user'])){ //Si es un postor puede pujar
									?>
										<td style="width: 150px; text-align: center;">
										<a href="<?php echo $tipoSubastaPhp ?>.php?id=<?php echo $idSubasta; ?>">
										<?php echo "Pujar"?> 
									<?php
									}
									?>
									
								<?php
								}
								else{
									?>
									<td style="width: 170px; text-align: center;"> </td>
								<?php
								}
								?>
								
								

						</tr>
		           	</table>
				
				<!-- FIN TABLA -->	
				
				<?php
				
				}
				
			}else{
				echo "";
				?>
					<label style="margin-left: 470px; margin-top:600px; font-family:'Segoe UI'; font-size: 20px;"> No existen subastas actualmente </label>
				<?php
			}
	}
	?>