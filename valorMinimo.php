	
<?php
	function valorMinimo($idSubasta){
       $conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		$selectSubastas;
		$resultSubastas;
    	if(session_id() == '') {
            session_start();
		}
        //$selectPujas = "SELECT id FROM usuarios WHERE id = '".$_SESSION['user']['postor']."'";
        //$resultSubastas = $conn->query($selectSubastas);
       $select = "SELECT tipo, precioinicial  FROM subastas WHERE id='$idSubasta'";
       $result = $conn->query($select);
       $row=$result->fetch_assoc();
       $tipoSubasta = $row['tipo'];
       $precio = $row['precioinicial'];
        $select = "SELECT cantidad FROM pujas WHERE idsubasta='$idSubasta'";
	   $result = $conn->query($select);
	   $idUser = '';
        if ($result->num_rows> 0) {
            //$precio=0;
            while( $row=$result->fetch_assoc()){
                $precioronda = $row['cantidad'];
                if($precio<$precioronda && ($tipoSubasta==1 || $tipoSubasta==3)){
                    $precio = $precioronda;
                }else if($precio>$precioronda && ($tipoSubasta==2 || $tipoSubasta==4)){
                    $precio = $precioronda;
                }
            }
            return $precio;
		
	   }else{
            $select = "SELECT precioinicial FROM subastas WHERE id='$idSubasta'";
            $result = $conn->query($select);
            $row = $result->fetch_assoc();
            $precio = $row['precioinicial'];
            return $precio;
	   }
       
    }
	
	?>