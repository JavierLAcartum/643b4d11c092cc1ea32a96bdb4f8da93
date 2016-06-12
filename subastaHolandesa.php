<?php
    	
if(isset($_GET['id'])){
    $idSubasta = $_GET['id'];
}
//Conexion($idSubasta);
$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
$selectSubastas;
$resultSubastas;
if(session_id() == '') {
    session_start();
}
function RedirectToURL($url, $tiempo)
{
    header("Refresh: $tiempo, URL=$url");
    exit;
}


function checkCambioPrecio($idSubasta){

       $var="<table><tr><td>Fecha</td><td>Puja</td></tr>";
}


    

//***********************************************************************************//
 $idUser;
 $tipoUser;
			if(array_key_exists('subastador', $_SESSION['user'])){
				
				$idUser = $_SESSION['user']['subastador'];
                $tipoUser = "subastador";
			}
			else if(array_key_exists('postor', $_SESSION['user'])){
				
				$idUser = $_SESSION['user']['postor'];
                $tipoUser = "postor";
			}
    $select="SELECT idpujaganadora FROM subastas WHERE id='$idSubasta'";
    $result = $conn->query($select);
    $row=$result->fetch_assoc();
    $ganador = $row['idpujaganadora']; 
    
    
?>






<!DOCTYPE html>
<html>
	<meta charset="UTF-8">
    
    <head>
        <title>SUBASTAS</title>
        <link rel="stylesheet" href="css/estilos.css" type="text/css" media="all" />
    </head>

    <body>


		        <!-- Puja -->
		        <?php
		        	$selectSubastas = "SELECT tipo, idsubastador, fechainicio, fechacierre FROM subastas WHERE id='$idSubasta'";
			$resultSubastas = $conn->query($selectSubastas);
			$tipoSubasta; $tipoSubastaString; $producto; $subastador; $fechaInicio; $fechaCierre;

			foreach (array_keys($_SESSION['user']) as $field){

			}
			
			include("listaSubastas.php");
			
			if($resultSubastas->num_rows > 0){
				
				while($row = $resultSubastas->fetch_assoc()) {
					
					$tipoSubasta = $row['tipo'];
					$tipoSubastaString = pasarTipoSubastaAString($tipoSubasta);

					$fechaInicio = $row['fechainicio'];
					$fechaCierre = $row['fechacierre'];

					?>

					<div id="header">
						<button class="buttonVolver" onclick="location.href='<?php echo $field; ?>.php'">Volver</button>
			    		<h2 style="font-size: 30px; font-style: italic;"> <?php echo $tipoSubastaString; ?> </h2>
			    	</div>

			    		<table style="width:100%; padding: 10px; padding-left: 15px; margin-top: 130px; font-family:'Segoe UI'; font-weight: bold;">
			                <tr>
			                    <td style="width: 100px; text-align: center;">FECHA INICIO</td>
			                    <td style="width: 100px; text-align: center;">FECHA CIERRE</td>
			                    <td style="width: 135px; text-align: center;">SUBASTADOR</td>
			                    <td style="width: 130px; text-align: center;">LOTE/PRODUCTO</td>
			                    <td style="width: 150px; text-align: center;">DESCRIPCIÓN</td>
			                </tr>
			            </table>


			            <table style="width:100%; padding: 10px; padding-left: 15px; margin-top: 10px; font-family:'Segoe UI'; border: 1px solid black;">

				            <td style="width: 100px; text-align: center;"> <?php echo $fechaInicio; ?> </td>
							<td style="width: 100px; text-align: center;"> <?php echo $fechaCierre; ?> </td>
						
					<?php
					
					$idSubastador = $row['idsubastador'];
					$selectSubastador = "SELECT nombre, apellidos FROM usuarios WHERE id='$idSubastador'";
					$resultSubastador = $conn->query($selectSubastador);
					
					if($resultSubastador->num_rows > 0){
				
						while($rowSubastador = $resultSubastador->fetch_assoc()) {
						
							$nombre = $rowSubastador['nombre'];
							$apellidos = $rowSubastador['apellidos'];

							?>
								<td style="width: 135px; text-align: center;"> <?php echo $nombre." ".$apellidos; ?> </td>
							<?php

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

							?>
								<td style="width: 130px; text-align: center;"> <?php echo $nombreProducto; ?> </td>
							
								<td style="width: 150px; text-align: center;"> <?php echo $descripcionProducto; ?> </td>
							<?php
						}	
					}

					else if ($resultLote->num_rows > 0){
				
						while($rowLote= $resultLote->fetch_assoc()) {
						
							$nombreLote = $rowLote['nombre'];
							$descripcionLote = $rowLote['descripcion'];
							
							?>
								<td style="width: 130px; text-align: center;"> <?php echo $nombreLote; ?> </td>
							<?php

							?>
								<td style="width: 150px; text-align: center;"> <?php echo $descripcionLote; ?> </td>
							<?php
						}	
					}

					else{
						?>
							<td style="width: 130px; text-align: center;"> </td>
						
							<td style="width: 150px; text-align: center;"> </td>
						<?php
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
				}		
			}
		            if($tipoUser=='postor' && $ganador==null){
		      
		            }
		        ?>

		        </table>

        <script type="text/javascript">
            //setInterval(function(){ refreshTable(); }, 3000);
            //setTimeout(refreshTable, 5000);

            /*function refreshTable() {
                $('#tableHolder').load('listaPujas.php', function());
            }*/
            function loadDoc() {

                <?php
                    
                ?>
                var xhttp = new XMLHttpRequest();
                console.log(xhttp.status);
                xhttp.onreadystatechange = function () {
                    if ((xhttp.readyState == 4) && (xhttp.status == 200)) {

                        document.getElementById("demo").innerHTML = xhttp.responseText;
                    }
                };
                xhttp.open("GET", "checkFecha.php?id=<?php echo $idSubasta; ?>", true);
                xhttp.send();
            }  

            setInterval(function () {
                loadDoc();
            }, 50);
        </script>

        		
		        	<a class="active">
		                <form id='login' class="input-list style-4 clearfix" action='compradorHolandes.php?id=<?php echo $idSubasta; ?>' method='post' accept-charset='UTF-8'>
		                    <button style="margin-top: 60px; margin-left: 600px;" name='submit'>PUJAR</button>
		                </form>
		            </a>
		     


        <div id="demo"></div>

        <div id="tableHolder"></div>
    </body>

    </html>