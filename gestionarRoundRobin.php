<?php
	
    include("escribirLog.php");
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
	?>

	<table style="width:100%; padding: 10px; padding-left: 15px; padding-bottom: 30px; margin-top: 10px; margin-left: 15px; font-family:'Segoe UI';">
	                <tr>
	                    <td style="width: 100px; text-align: center;"><?php echo $fechainicio; ?></td>
	                    <td style="width: 100px; text-align: center;"><?php echo $fechasegundapuja; ?></td>
	                    <td style="width: 135px; text-align: center;"><?php echo $fechacierre; ?></td>
	                </tr>
	</table>
	
	</br>
	<label style="margin-left: 115px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold;"> PUJAS: </label>

	<?php
	$posicionFecha;
	
	include("valorMinimo.php");
	
	if($tipoUsuario == "postor"){
		$haPujado = haPujado_SI_NO($idSubasta);
		
		if(strtotime($fechaActual) < strtotime($fechainicio)){ 
			?>
				<label style="margin-left: 400px; font-family:'Segoe UI'; font-size: 15px; color:white;"> *La subasta no ha comenzado aún* </label>
			<?php
			$posicionFecha = 1;
		}else if((strtotime($fechaActual) >= strtotime($fechainicio)) && (strtotime($fechaActual) < strtotime($fechasegundapuja))){
			$posicionFecha = 2;
			?>
				<label style="margin-left: 515px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold;"> *SUBASTA EMPEZADA* </label>
			<?php
			//echo !empty($haPujado);
			if (!empty($haPujado)){
				?>
					<table style="width:50%; padding: 10px; padding-left: 15px; padding-bottom: 1px; margin-top: 10px; font-family:'Segoe UI'; font-weight: bold;">
		                <tr>
		                	<td style="width: 100px; text-align: center;">Fecha</td>
		                    <td style="width: 100px; text-align: center;">Puja</td>
		                </tr>
					</table>
					<table style="width:50%; padding: 1px; padding-left: 15px; padding-bottom: 5px; margin-top: 1px; font-family:'Segoe UI';">
				<?php
				//echo "<table><tr><td>Fecha</td><td>Puja</td></tr>";
				while($row = $haPujado->fetch_assoc()){
					?>
						<tr>
		                	<td style="width: 100px; text-align: center;"> <?php echo $row['fecha'];?> </td>
		                    <td style="width: 100px; text-align: center;"> <?php echo $row['cantidad'];?> </td>
		                </tr>
		            <?php
				}
				?>
					</table>
				<?php
				
			}else{
				
				pujar($idSubasta, $tipoSubasta, $_SESSION['user']['postor'], "puja");
			}			
		}else if((strtotime($fechaActual) >= strtotime($fechasegundapuja)) &&  (strtotime($fechaActual) < strtotime($fechacierre))){
			$resultado = valorMinimoRR($idSubasta, $fechasegundapuja);
			if($resultado==false){
				echo "";
				?>
					<label style="margin-left: 400px; font-family:'Segoe UI';"> *Nadie ha pujado en la subasta* </label>
				<?php
			}else{
				$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
				
				$update = "UPDATE subastas SET cantidadsegundapuja='".$resultado."' WHERE id='$idSubasta'";
				$result = $conn->query($update);
				if($tipoSubasta==11){
					?>
					<label style="margin-left: 115px; margin-top: 10px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold;"> *La puja ganadora hasta el momento es de: <?php echo cantidadSegundaPuja($idSubasta); ?> euros. Puede realizar otra única puja mayor que la actual.* </label>
				<?php
				}else if($tipoSubasta==12){
					echo "<br>";
					?>
						<label style="margin-left: 115px; margin-top: 10px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold;"> *La puja ganadora hasta el momento es de: <?php echo cantidadSegundaPuja($idSubasta); ?> euros. Puede realizar otra única puja mayor que la actual.* </label>
					<?php
				}
				
				$posicionFecha = 3;
				if(empty($haPujado)){
					?><p>Ya no puede pujar, porque no participó en la primera ronda</p><?php
				}else{
					if($haPujado->num_rows == 1){
						pujar($idSubasta, $tipoSubasta, $_SESSION['user']['postor'], "pujaSegunda");
						//echo "<table><tr><td>Fecha</td><td>Puja</td></tr>";
						?>
							<table style="width:50%; padding: 10px; padding-left: 15px; padding-bottom: 1px; margin-top: 10px; font-family:'Segoe UI'; font-weight: bold;">
								<tr>
									<td style="width: 100px; text-align: center;">Fecha</td>
									<td style="width: 100px; text-align: center;">Puja</td>
								</tr>
							</table>
							<table style="width:50%; padding: 1px; padding-left: 15px; padding-bottom: 5px; margin-top: 1px; font-family:'Segoe UI';">
						<?php
						while($row = $haPujado->fetch_assoc()){
							?>
								<tr>
									<td style="width: 100px; text-align: center;"> <?php echo $row['fecha'];?> </td>
									<td style="width: 100px; text-align: center;"> <?php echo $row['cantidad'];?> </td>
								</tr>
							<?php
						}
						?>
							</table>
						<?php
					}else if($haPujado->num_rows == 2){
						?>
							<table style="width:50%; padding: 10px; padding-left: 15px; padding-bottom: 1px; margin-top: 10px; font-family:'Segoe UI'; font-weight: bold;">
								<tr>
									<td style="width: 100px; text-align: center;">Fecha</td>
									<td style="width: 100px; text-align: center;">Puja</td>
								</tr>
							</table>
							<table style="width:50%; padding: 1px; padding-left: 15px; padding-bottom: 5px; margin-top: 1px; font-family:'Segoe UI';">
						<?php
						while($row = $haPujado->fetch_assoc()){
							?>
								<tr>
									<td style="width: 100px; text-align: center;"> <?php echo $row['fecha'];?> </td>
									<td style="width: 100px; text-align: center;"> <?php echo $row['cantidad'];?> </td>
								</tr>
							<?php
						}
						?>
							</table>
						<?php
					}else{
						?>
							<label style="margin-left: 515px; font-family:'Segoe UI'; font-size: 15px; color:white;"> *Ha pujado <?php echo $haPujado->num_rows; ?> veces* </label>
						<?php
						//echo "Ha pujado ".$haPujado->num_rows." veces.";

					}
				}
			}
					

			
			
			
			
		}else if(strtotime($fechaActual) >= strtotime($fechacierre)){
			//Subasta finalizada, mostrar ganador

			?>
				<label style="margin-left: 400px; font-family:'Segoe UI'; font-size: 15px; color:white;"> *Subasta finalizada* </label>
			<?php
			$resultado = valorMinimoRR($idSubasta, $fechacierre);
			if($resultado==false){
				?>
					<label style="margin-left: 400px; font-family:'Segoe UI';"> *Nadie ha pujado en la subasta* </label>
				<?php
                 escribirLogNoPujas($conn, $idSubasta, $fechacierre);
			}else{
				listaPujas($idSubasta);
				$posicionFecha = 4;
				?>
					<table style="border: 1px solid; margin-bottom: 10px; margin-top: 10px; margin-left: 10px;">
					<td><label style="text-align: center; margin-left: 1px; font-family:'Segoe UI'; font-size: 15px; font-weight: bold;"> La puja ganadora de esta subasta es de: <?php echo valorMinimoRR($idSubasta, $fechacierre); ?> euros, cuya id es <?php echo sacarIdPuja($idSubasta); ?> </label> </td>
					</table>
				<?php
                escribirLogGanador($conn, $idSubasta, $fechacierre);
			}

			
			

		}
	}
	
	if($tipoUsuario == "subastador"){
		if(strtotime($fechaActual) < strtotime($fechainicio)){ 
			?>
				<label style="margin-left: 400px; font-family:'Segoe UI'; font-size: 15px; color:white;"> *La subasta no ha comenzado aún* </label>
			<?php
			$posicionFecha = 1;
		}else if((strtotime($fechaActual) >= strtotime($fechainicio)) && (strtotime($fechaActual) < strtotime($fechasegundapuja))){
			$posicionFecha = 2;
			listaPujas($idSubasta);			
		}else if((strtotime($fechaActual) >= strtotime($fechasegundapuja)) &&  (strtotime($fechaActual) < strtotime($fechacierre))){

			$resultado = valorMinimoRR($idSubasta, $fechasegundapuja);
			if($resultado==false){
				?>
					<label style="margin-left: 400px; font-family:'Segoe UI';"> *Nadie ha pujado en la subasta* </label>
				<?php
			}else{
				$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
				
				$update = "UPDATE subastas SET cantidadsegundapuja='".$resultado."' WHERE id='$idSubasta'";
				$result = $conn->query($update);
				if($tipoSubasta==11){
					?>
						<label style="margin-left: 115px; margin-top: 10px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold;"> *La puja ganadora hasta el momento es de: <?php echo cantidadSegundaPuja($idSubasta); ?> euros. Puede realizar otra única puja menor que la actual.* </label>
					<?php
				}else if($tipoSubasta==12){
					?>
						<label style="margin-left: 115px; margin-top: 10px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold;"> *La puja ganadora hasta el momento es de: <?php echo cantidadSegundaPuja($idSubasta); ?> euros. Puede realizar otra única puja menor que la actual.* </label>
					<?php
				}
				listaPujas($idSubasta);

				$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
				
				$update = "UPDATE subastas SET cantidadsegundapuja='".valorMinimoRR($idSubasta, $fechasegundapuja)."' WHERE id='$idSubasta'";
				$result = $conn->query($update);
				if($tipoSubasta==11){
					echo "<br>";
					?>
						<label style="margin-left: 115px; margin-top: 10px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold;"> *La puja ganadora hasta el momento es de: <?php echo cantidadSegundaPuja($idSubasta); ?> euros* </label>
					<?php
					//echo "<p>La puja ganadora hasta el momento es de: ".cantidadSegundaPuja($idSubasta)." euros.";
				}else if($tipoSubasta==12){
					echo "<br>";
					?>
						<label style="margin-left: 115px; margin-top: 10px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold;"> *La puja ganadora hasta el momento es de: <?php echo cantidadSegundaPuja($idSubasta); ?> euros* </label>
					<?php
				}
			}
			
		}else if(strtotime($fechaActual) >= strtotime($fechacierre)){
			//Subasta finalizada, mostrar ganador
			?>
				<label style="margin-left: 400px; font-family:'Segoe UI'; font-size: 15px; color:white;"> *Subasta finalizada* </label>
			<?php
			$resultado = valorMinimoRR($idSubasta, $fechacierre);
			if($resultado==false){
				?>
					<label style="margin-left: 400px; font-family:'Segoe UI';"> *Nadie ha pujado en la subasta* </label>
				<?php
                 escribirLogNoPujas($conn, $idSubasta, $fechacierre);
			}else{
				listaPujas($idSubasta);
				$posicionFecha = 4;
				?>
					<table style="border: 1px solid; margin-bottom: 10px; margin-top: 10px; margin-left: 10px;">
					<td><label style="text-align: center; margin-left: 1px; font-family:'Segoe UI'; font-size: 15px; font-weight: bold;"> La puja ganadora de esta subasta es de: <?php echo valorMinimoRR($idSubasta, $fechacierre); ?> euros, cuya id es <?php echo sacarIdPuja($idSubasta); ?> </label> </td>
					</table>
				<?php
                escribirLogGanador($conn, $idSubasta, $fechacierre);
			}

		}
	}

    //para el log
     if(strtotime($fechaActual) >= strtotime($fechacierre)){
        
        $queryFinSubasta = "SELECT * FROM log WHERE descripcion = 'La subasta "  .$idSubasta.  " ha finalizado.'";
        $resultQueryFinSubasta = $conn ->query($queryFinSubasta);
        if($resultQueryFinSubasta->num_rows == 0){
            $queryBuscarProd = "SELECT id FROM productos WHERE idsubasta='$idSubasta' ";
            $resultNombreProd = $conn->query( $queryBuscarProd);
            if($resultNombreProd->num_rows > 0){
                $rowNombreProd = $resultNombreProd->fetch_assoc();
                $idprod = $rowNombreProd['id'];
                escribirLog("La subasta "  .$idSubasta.  " ha finalizado.", "NULL", $idSubasta, $idprod, "NULL", "NULL");
            }else{
                $queryBuscarLote= "SELECT id FROM lotes WHERE idsubasta='$idSubasta' ";
                $resultNombreLote = $conn->query( $queryBuscarLote);
                $rowNombreLote = $resultNombreLote->fetch_assoc();
                $idlote = $rowNombreLote['id'];
                escribirLog("La subasta "  .$idSubasta.  " ha finalizado.", "NULL", $idSubasta, "NULL", idlote, "NULL");
            }
            
        }
                    
     }
    //fin de para el log
	
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
			?>
				<table style="width:50%; padding: 10px; padding-left: 15px; padding-bottom: 1px; margin-top: 10px; font-family:'Segoe UI'; font-weight: bold;">
	                <tr>
	                	<td style="width: 100px; text-align: center;">Fecha</td>
	                    <td style="width: 100px; text-align: center;">Puja</td>
	                </tr>
				</table>
				<table style="width:50%; padding: 1px; padding-left: 15px; padding-bottom: 5px; margin-top: 1px; font-family:'Segoe UI';">
			<?php
			while($row = $result->fetch_assoc()){
				?>
					<tr>
	                	<td style="width: 100px; text-align: center;"> <?php echo $row['fecha'];?> </td>
	                    <td style="width: 100px; text-align: center;"> <?php echo $row['cantidad'];?> </td>
	                </tr>
	            <?php
			}

			?>
				</table>
			<?php	
		}else{
			?>
			<label style="margin-left: 10px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold;"> *No hay pujas* </label>
			<?php
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

    function escribirLogGanador($conn, $idSubasta, $fechacierre){
        $queryIdGnador = "SELECT * FROM pujas WHERE id = '".sacarIdPuja($idSubasta)."'";
        $resultQueryIdGanador = $conn ->query($queryIdGnador);
        $rowIdGanador = $resultQueryIdGanador->fetch_assoc();
        $idganador = $rowIdGanador['idpostor'];
        
        $queryFinSubasta = "SELECT * FROM log WHERE descripcion = 'La puja ganadora de la subasta "  .$idSubasta.  " es " .valorMinimoRR($idSubasta, $fechacierre). "€.'";
        $resultQueryFinSubasta = $conn ->query($queryFinSubasta);
        if($resultQueryFinSubasta->num_rows == 0){
            $queryBuscarProd = "SELECT id FROM productos WHERE idsubasta='$idSubasta' ";
            $resultNombreProd = $conn->query( $queryBuscarProd);
            if($resultNombreProd->num_rows > 0){
                $rowNombreProd = $resultNombreProd->fetch_assoc();
                $idprod = $rowNombreProd['id'];
                escribirLog("La puja ganadora de la subasta ".$idSubasta." es ".valorMinimoRR($idSubasta, $fechacierre)."€.", $idganador, $idSubasta, $idprod, "NULL", sacarIdPuja($idSubasta));
            }else{
                $queryBuscarLote= "SELECT id FROM lotes WHERE idsubasta='$idSubasta' ";
                $resultNombreLote = $conn->query( $queryBuscarLote);
                $rowNombreLote = $resultNombreLote->fetch_assoc();
                $idlote = $rowNombreLote['id'];
                escribirLog("La puja ganadora de la subasta ".$idSubasta." es ".valorMinimoRR($idSubasta, $fechacierre)."€.", $idganador, $idSubasta, "NULL", idlote, sacarIdPuja($idSubasta));
            }
            
        }
    }

    function escribirLogNoPujas($conn, $idSubasta, $fechacierre){
        
        
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
                escribirLog("La subasta ".$idSubasta." ha finalizado sin pujas.", "NULL", $idSubasta, "NULL", idlote, "NULL");
            }
            
        }
    }
	
?>
