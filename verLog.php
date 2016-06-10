<?php
		

		
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
	
	function crearTableLog($resultLog, $conn){
					//VARIABLES A MOSTRAR
					//**************************************************************************
					$idLog = '';
					$fecha;
                    $descripcion = '';
                    $idUsuario = '';
                    $idSubasta = '';
                    $idProducto = '';
					
					
					//****************************************************************************
					//****************************************************************************
					
					$idLog = $rowLog['id'];
					$fecha = $rowLog['fecha'];
                    $descripcion = $rowLog['descripcion'];
					
					
				?>
				
                <table>
                    <tr>
                        <td><?php echo $idLog?></td>
                        <td><?php echo $fecha?></td>
                        <td><?php echo $descripcion?></td>
                        <td><?php echo $idUsuario?></td>
                        <td><?php echo $idSubasta?></td>
                        <td><?php echo $idProducto?></td>
                    </tr>
                </table>
				
				
				
				
				<?php
				
				}
				
			}else{
			}
	}
	?>
