<?php
    	
    if(isset($_GET['id'])){
        $idSubasta = $_GET['id'];
    }
    //Conexion($idSubasta);

    function RedirectToURL($url, $tiempo)
    {
        header("Refresh: $tiempo, URL=$url");
        exit;
    }


   function valorMinimo($idSubasta){
       $conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		$selectSubastas;
		$resultSubastas;
    	if(session_id() == '') {
            session_start();
		}
        //$selectPujas = "SELECT id FROM usuarios WHERE id = '".$_SESSION['user']['postor']."'";
        //$resultSubastas = $conn->query($selectSubastas);
        $select = "SELECT cantidad FROM pujas WHERE idsubasta='$idSubasta'";
	   $result = $conn->query($select);
	   $idUser = '';
        if ($result->num_rows> 0) {
            $precio=0;
            while( $row=$result->fetch_assoc()){
                $precioronda = $row['cantidad'];
                if($precio<$precioronda){
                    $precio = $precioronda;
                }
            }
            return $precio;
		
	   }else{
            $select = "SELECT precioinicial FROM subastas WHERE id='$idSubasta'";
            $result = $conn->query($select);
            $row = $result->fetch_assoc();
            $precio = $row['precioinicial'];
            echo 'OK!';
            return $precio;
	   }
       
    }

    

//***********************************************************************************//
$pujactual = $_POST['puja'];
if($pujactual > valorMinimo($idSubasta))
    {
        $conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		$selectSubastas;
		$resultSubastas;
    	if(session_id() == '') {
            session_start();
		}
        //$selectPujas = "SELECT id FROM usuarios WHERE id = '".$_SESSION['user']['postor']."'";
        //$resultSubastas = $conn->query($selectSubastas);
        $select = "SELECT id FROM usuarios WHERE usuario='postor' AND password='sekretp'";
	   $result = $conn->query($select);
	   $idUser = '';
            $result->num_rows;
            $row = $result->fetch_assoc();
            $idUser = $row['id'];
		

		
                        
        // Then call the date functions
        $date = date('Y-m-d H:i:s');
        // Or
        $date = date('Y/m/d H:i:s');
        $puja = $_POST['puja'];
        $select = "INSERT INTO pujas (fecha, cantidad, idsubasta, idpostor) VALUES ('$date', '$puja',    '$idSubasta', '$idUser')";
        if ($conn->query($select) === TRUE) {
            echo "Usuario Puja Correcta.";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }else{
        echo "La puja es inferior a la establecida!";
    }
    

?>
    <html>

    <body>
        <!-- Puja -->
        <a class="active">
                <form id='login' class="input-list style-4 clearfix" action='dinamicaDescAscendente.php?id=<?php echo $idSubasta; ?>' method='post' accept-charset='UTF-8'>
                    <input type='number' name='puja' id='puja' style="width:100px;" required />
                    <button name='submit'>Puja</button>
                </form>
            </a>

        <script type="text/javascript">
            $(document).ready(function () {
                refreshTable();
            });

            function refreshTable() {
                $('#tableHolder').load('dinamicaDescAscendente.php', function () {
                    setTimeout(refreshTable, 5000);
                });
            }
        </script>
    </body>

    </html>