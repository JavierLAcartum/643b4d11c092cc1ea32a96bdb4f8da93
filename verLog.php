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
		
				
	

	
	function crearTableLog($resultLog, $conn){
		?>
        <table>
            <tr>
                <td>idLog</td>
                <td>fecha</td>
                <td>Descripción</td>
                <td>Id Usuario</td>
                <td>idSubasta</td>
                <td>idProducto</td>
            </tr>

            <?php
        if($resultLog->num_rows > 0){//LISTA DE LOGS
				while($rowLog = $resultLog->fetch_assoc()) {//ITERACION SOBRE LOS LOGS
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
					$idUsuario = $rowLog['idusuario'];
                    $idSubasta = $rowLog['idsubasta'];
					$idProducto = $rowLog['idproducto'];
					
					
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
                        <?php echo $idUsuario?>
                    </td>
                    <td>
                        <?php echo $idSubasta?>
                    </td>
                    <td>
                        <?php echo $idProducto?>
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