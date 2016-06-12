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
	
//***********************************************************************************//
 $idUser;
 $tipoUser;
 include("valorMinimo.php");
 
			if(array_key_exists('subastador', $_SESSION['user'])){
				
				$idUser = $_SESSION['user']['subastador'];
                $tipoUser = "subastador";
			}
			else if(array_key_exists('postor', $_SESSION['user'])){
				
				$idUser = $_SESSION['user']['postor'];
                $tipoUser = "postor";
			}
if(isset($_POST['puja'])){
$pujactual = $_POST['puja'];
$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		$selectSubastas;
		$resultSubastas;
    	if(session_id() == '') {
            session_start();
		}
       $select = "SELECT tipo FROM subastas WHERE id='$idSubasta'";
       $result = $conn->query($select);
       $row=$result->fetch_assoc();
       $tipoSubasta = $row['tipo'];
       
if($pujactual > valorMinimo($idSubasta)&& ($tipoSubasta==1 || $tipoSubasta==3))
    {
        $conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		$selectSubastas;
		$resultSubastas;	
                        
        $date = date('Y-m-d H:i:s');
        $puja = $_POST['puja'];
        $select = "INSERT INTO pujas (fecha, cantidad, idsubasta, idpostor) VALUES ('$date', '$puja',    '$idSubasta', '$idUser')";
        if ($conn->query($select) === TRUE) {
        	?>
			<script type="text/javascript">
				alert('Usuario Puja Correcta');
			</script>
			<?php
            echo "";
        } else {
           // echo "Error updating record: " . $conn->error;
        }
}else if($pujactual < valorMinimo($idSubasta)&& ($tipoSubasta==2 || $tipoSubasta==4)){
        $conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		$selectSubastas;
		$resultSubastas;
    	  
        $idUser = $_SESSION['user']['postor'];		
                        
        // Then call the date functions
        $date = date('Y-m-d H:i:s');
        $puja = $_POST['puja'];
        $select = "INSERT INTO pujas (fecha, cantidad, idsubasta, idpostor) VALUES ('$date', '$puja','$idSubasta', '$idUser')";
        if ($conn->query($select) === TRUE) {
            ?>
			<script type="text/javascript">
				alert('Usuario Puja Correcta');
			</script>
			<?php
        } else {
            //echo "Error updating record: " . $conn->error;
        }
}else{
	?>
		<script type="text/javascript">
			alert('La puja tiene un valor incorrecto!');
		</script>
	<?php
    echo "";
}
}

?>









<!DOCTYPE html>
<html>
	<meta charset="UTF-8">
    </meta>
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

	    		<table style="width:100%; padding: 30px; margin-top: 130px; font-family:'Segoe UI'; font-weight: bold;">
	                <tr>
	                    <td style="width: 100px; text-align: center;">FECHA INICIO</td>
	                    <td style="width: 100px; text-align: center;">FECHA CIERRE</td>
	                    <td style="width: 135px; text-align: center;">SUBASTADOR</td>
	                    <td style="width: 130px; text-align: center;">LOTE/PRODUCTO</td>
	                    <td style="width: 150px; text-align: center;">DESCRIPCIÓN</td>
	                </tr>
	            </table>


       		<table style="width:100%; padding: 15px; margin-top: 10px; font-family:'Segoe UI'; border: 1px solid black;">


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
        if($tipoUser=='postor'){
        ?>

        </table>

        <a class="active">
                <form id='pujar' class="input-list style-4 clearfix" action='dinamicaDescAscendente.php?id=<?php echo $idSubasta; ?>' method='post' accept-charset='UTF-8'>
                    <input type='number' name='puja' id='puja' placeholder="<?php echo valorMinimo($idSubasta) ?>" style="width:100px;" required />
                    <button name='submit'>Puja</button>
                </form>
        </a>
        <?php
        }
        ?>

        <script type="text/javascript">
        function loadDoc() {
                <?php
            if($tipoSubasta==1 || $tipoSubasta==2){
            ?>

                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if ((xhttp.readyState == 4) && (xhttp.status == 200)) {

                        document.getElementById("demo").innerHTML = xhttp.responseText;
                    }
                };
                xhttp.open("GET", "listaPujasDescubierta.php?id=<?php echo $idSubasta; ?>", true);
                xhttp.send();
                <?php
            }
			else if($tipoSubasta==3 || $tipoSubasta==4){
            ?>
                 var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if ((xhttp.readyState == 4) && (xhttp.status == 200)) {

                        document.getElementById("demo").innerHTML = xhttp.responseText;
                    }
                };
                xhttp.open("GET", "listaPujasAnonima.php?id=<?php echo $idSubasta; ?>", true);
                xhttp.send();
            <?php
			}
			?>
		}
			
			var respuestaXhttp;
			function comprobarGanador() {
					   
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function () {
					if ((xhttp.readyState == 4) && (xhttp.status == 200)) {

						respuestaXhttp = xhttp.responseText;
						document.getElementById("pujaFinalizada").innerHTML = respuestaXhttp;
						if(respuestaXhttp != ""){
						document.getElementById('pujar').style.display='none'; //Si ya ha realizado una puja, se oculta el formulario para pujar
						}
					}
				};
				xhttp.open("GET", "comprobarGanador.php?id=<?php echo $idSubasta;?>", true);
				xhttp.send(); 
			}				
			
			setInterval(function () {
			comprobarGanador();
			}, 500);
                

            setInterval(function () {
                loadDoc();
            }, 500);
        </script>

		<div id = "pujaFinalizada"> </div>
        <div id="demo"></div>

        <div id="tableHolder"></div>
    </body>

    </html>