<?php

		

	function mostrarLogs(){
		
		$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		$selectLog;
		$resultLog;
		
		if(session_id() == '') {
			session_start();
		}
		
        $selectLog = "SELECT * FROM log ORDER BY fecha DESC";
		$resultLog = $conn->query($selectLog);

        crearTableLog($resultLog, $conn);
        
        }
		
   	function mostrarConsulta($tipoConsulta, $valorConsulta){
		
		$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		$selectLog;
		$resultLog;
		
		if(session_id() == '') {
			session_start();
		}
        
        if($tipoConsulta == 'idusuario'){
            $resultNombreUsuario = $conn->query( "SELECT id FROM usuarios WHERE usuario='$valorConsulta'");
            $rowNombreUsuario = $resultNombreUsuario->fetch_assoc();
			$valorConsulta = $rowNombreUsuario['id'];
        }
        
        if($tipoConsulta == 'fecha'){
            $selectLog = "SELECT * FROM log WHERE $tipoConsulta BETWEEN '$valorConsulta[0]' AND '$valorConsulta[1]' ORDER BY fecha DESC";
		    
        }else{
            $selectLog = "SELECT * FROM log WHERE $tipoConsulta ='$valorConsulta'  ORDER BY fecha DESC";
		    
        }
		
        $resultLog = $conn->query($selectLog);

        crearTableLog($resultLog, $conn);
        
        }
		
	

	
	function crearTableLog($resultLog, $conn){
		?>

        <table style="width:100%; padding: 30px; margin-top: 10px; font-family:'Segoe UI'; font-weight: bold;">
                <tr>
                    <td style="width: 130px; text-align: center;">idLOG</td>
                    <td style="width: 100px; text-align: center;">FECHA</td>
                    <td style="width:140px; text-align: center;">DESCRIPCIÓN</td>
                    <td style="width: 135px; text-align: center;">NOMBRE DE USUARIO</td>
                    <td style="width: 130px; text-align: center;">idSUBASTA</td>
                    <td style="width: 130px; text-align: center;">idPRODUCTO</td>
                    <td style="width: 130px; text-align: center;">NOMBRE PRODUCTO</td>
                    <td style="width: 130px; text-align: center;">idLOTE</td>
                    <td style="width: 130px; text-align: center;">NOMBRE LOTE</td>
                    <td style="width: 150px; text-align: center;">idPUJA</td>
                </tr>
            </table>
        

        <table style="width:100%; padding: 10px; padding-left: 30px; margin-top: 10px; font-family:'Segoe UI'; border: 1px solid black;">

            <?php
        if($resultLog->num_rows > 0){//LISTA DE LOGS
				while($rowLog = $resultLog->fetch_assoc()) {//ITERACION SOBRE LOS LOGS
					//VARIABLES A MOSTRAR
					//**************************************************************************
					$idLog = '';
					$fecha;
                    $descripcion = '';
                    $nombreUsuario = '';
                    $idSubasta = '';
                    $idProducto = '';
                    $nombreProducto = '';
                    $idLote = '';
                    $nombreLote = '';
                    $idPuja = '';
					
					
					//****************************************************************************
					//****************************************************************************
					
					$idLog = $rowLog['id'];
					$fecha = $rowLog['fecha'];
                    $descripcion = $rowLog['descripcion'];
                    
                    $resultNombreUsuario = $conn->query( "SELECT usuario FROM usuarios WHERE id='".$rowLog['idusuario']."'");
                    $rowNombreUsuario = $resultNombreUsuario->fetch_assoc();
					$nombreUsuario = $rowNombreUsuario['usuario'];
                    
                    $idSubasta = $rowLog['idsubasta'];
					$idProducto = $rowLog['idproducto'];
                    $nombreProducto = $rowLog['nombreproducto'];
                    $idLote = $rowLog['idlote'];
                    $nombreLote = $rowLog['nombrelote'];
					$idPuja = $rowLog['idpuja'];
					
					
				?>


                <tr>
                    <td style="width: 130px; text-align: center;">
                        <?php echo $idLog?>
                    </td>
                    <td style="width: 100px; text-align: center;">
                        <?php echo $fecha?>
                    </td>
                    <td style="width:140px; text-align: center;">
                        <?php echo $descripcion?>
                    </td>
                    <td style="width: 135px; text-align: center;">
                        <?php echo $nombreUsuario?>
                    </td>
                    <td style="width: 130px; text-align: center;">
                        <?php echo $idSubasta?>
                    </td>
                    <td style="width: 130px; text-align: center;">
                        <?php echo $idProducto?>
                    </td>
                    <td style="width: 130px; text-align: center;">
                        <?php echo $nombreProducto?>
                    </td>
                    <td style="width: 130px; text-align: center;">
                        <?php echo $idLote?>
                    </td>
                    <td style="width: 130px; text-align: center;">
                        <?php echo $nombreLote?>
                    </td>
                    <td style="width: 150px; text-align: center;">
                        <?php echo $idPuja?>
                    </td>
                </tr>

                <?php
				
				}//Cierre del While
                ?>

        </table>

        <?php
			}else{
                ?>
                <label style="position: absolute; left: 515px; top: 435px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold; "> *No existen logs actualmente* </label>
                <?php
			}
	}
	?>