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
            echo "Usuario Puja Correcta.";
        } else {
            echo "Error updating record: " . $conn->error;
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
            echo "Usuario Puja Correcta.";
        } else {
            echo "Error updating record: " . $conn->error;
        }
}else{
    echo "La puja tiene un valor incorrecto!";
}
}

?>
    <html>

    <body>
        <!-- Puja -->
        <?php
    $selectSubastas = "SELECT tipo, idsubastador, fechainicio, fechacierre FROM subastas WHERE id='$idSubasta'";
	$resultSubastas = $conn->query($selectSubastas);
	$tipoSubasta; $tipoSubastaString; $producto; $subastador; $fechaInicio; $fechaCierre;
	
	include("listaSubastas.php");
	
	if($resultSubastas->num_rows > 0){
		while($row = $resultSubastas->fetch_assoc()) {
			$tipoSubasta = $row['tipo'];
			$tipoSubastaString = pasarTipoSubastaAString($tipoSubasta);
			echo "Tipo de subasta: ".$tipoSubastaString."\n";
			
			$fechaInicio = $row['fechainicio'];
			$fechaCierre = $row['fechacierre'];

			echo "Fecha de inicio: ".$fechaInicio."\n";
			echo "Fecha de cierre: ".$fechaCierre."\n";
			
			$idSubastador = $row['idsubastador'];
			$selectSubastador = "SELECT nombre, apellidos FROM usuarios WHERE id='$idSubastador'";
			$resultSubastador = $conn->query($selectSubastador);
			
			if($resultSubastador->num_rows > 0){
		
				while($rowSubastador = $resultSubastador->fetch_assoc()) {
				
					$nombre = $rowSubastador['nombre'];
					$apellidos = $rowSubastador['apellidos'];
					
					echo "Subastador: ".$nombre." ".$apellidos."\n";

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
					
					echo "Producto a subastar: ".$nombreProducto."\n";
					echo "Descripcion: ".$descripcionProducto;
				}	
			}

			else if ($resultLote->num_rows > 0){
		
				while($rowLote= $resultLote->fetch_assoc()) {
				
					$nombreLote = $rowLote['nombre'];
					$descripcionLote = $rowLote['descripcion'];
					
					echo "Lote a subastar: ".$nombreLote."\n";
					echo "Descripcion: ".$descripcionLote;
				}	
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