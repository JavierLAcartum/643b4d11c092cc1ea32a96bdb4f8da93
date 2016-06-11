<?php

		

	function mostrarLogs(){
		
		$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		$selectLog;
		$resultLog;
		
		if(session_id() == '') {
			session_start();
		}
		
        $selectLog = "SELECT * FROM log ORDER BY id DESC";
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
		
        $selectLog = "SELECT * FROM log WHERE $tipoConsulta ='$valorConsulta'  ORDER BY id DESC";
		$resultLog = $conn->query($selectLog);

        crearTableLog($resultLog, $conn);
        
        }
		
	

	
	function crearTableLog($resultLog, $conn){
		?>
        <table>
            <tr>
                <td>idLog</td>
                <td>fecha</td>
                <td>Descripción</td>
                <td>Nombre de usuario</td>
                <td>idSubasta</td>
                <td>idProducto</td>
                <td>idLote</td>
                <td>idPuja</td>
            </tr>

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
                    $idLote = '';
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
                    $idLote = $rowLog['idlote'];
					$idPuja = $rowLog['idpuja'];
					
					
				?>


                <tr>
                    <td>
                        <?php echo $idLog?>
                    </td>
                    <td>
                        <?php echo $fecha?>
                    </td>
                    <td>
                        <?php echo $descripcion?>
                    </td>
                    <td>
                        <?php echo $nombreUsuario?>
                    </td>
                    <td>
                        <?php echo $idSubasta?>
                    </td>
                    <td>
                        <?php echo $idProducto?>
                    </td>
                    <td>
                        <?php echo $idLote?>
                    </td>
                    <td>
                        <?php echo $idPuja?>
                    </td>
                </tr>





                <?php
				
				}//Cierre del While
                ?>
        </table>

        <?php
			}else{
				echo "No existen logs actualmente.";
			}
	}
	?>