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
                <form id='login' class="input-list style-4 clearfix" action='compradorHolandes.php?id=<?php echo $idSubasta; ?>' method='post' accept-charset='UTF-8'>
                    <button name='submit'>COMPRA</button>
                </form>
            </a>
        <?php
            }
        ?>

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
            }, 100);
        </script>


        <div id="demo"></div>

        <div id="tableHolder"></div>
    </body>

    </html>