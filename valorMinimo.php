	
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
	
	function valorMinimoRR($idSubasta, $fecha){
       $conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		$selectSubastas;
		$resultSubastas;
    	if(session_id() == '') {
            session_start();
		}
        
       $select = "SELECT tipo, precioinicial  FROM subastas WHERE id='$idSubasta'";
       $result = $conn->query($select);
       $row=$result->fetch_assoc();
       $tipoSubasta = $row['tipo'];
	   $precio;
	   
	   if($tipoSubasta==11){
			$precio=0;
	   }else if($tipoSubasta==12){
		   $precio=9999999999999999999;
	   }
       $select = "SELECT cantidad FROM pujas WHERE idsubasta='$idSubasta' AND fecha <= '$fecha'";
	   $result = $conn->query($select);
	   
        if ($result->num_rows> 0) {
            while($row=$result->fetch_assoc()){
                $precioronda = $row['cantidad'];
                if($precio<$precioronda && ($tipoSubasta==11)){
                    $precio = $precioronda;
                }else if($precio>$precioronda && ($tipoSubasta==12)){
                    $precio = $precioronda;
                }
            }
            return $precio;
		
	   }else{
		   
           return -1;
	   }
       
    }
	
	function sacarIdPuja($idSubasta){
		  $conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		$selectSubastas;
		$resultSubastas;
    	if(session_id() == '') {
            session_start();
		}
        
       $select = "SELECT tipo, precioinicial  FROM subastas WHERE id='$idSubasta'";
       $result = $conn->query($select);
       $row=$result->fetch_assoc();
       $tipoSubasta = $row['tipo'];
	   $precio;
       if($tipoSubasta==11){
			$precio=0;
	   }else if($tipoSubasta==12){
		   $precio=9999999999999999999;
	   }
	   
	   $idPujaRetorno = '';
       $select = "SELECT cantidad, id FROM pujas WHERE idsubasta='$idSubasta'";
	   $result = $conn->query($select);

        if ($result->num_rows> 0) {
            
            while( $row=$result->fetch_assoc()){
                $precioronda = $row['cantidad'];
				$idPuja = $row['id'];
                if($precio<$precioronda && ($tipoSubasta==11)){
                    $precio = $precioronda;
					$idPujaRetorno = $idPuja;
                }else if($precio>$precioronda && ($tipoSubasta==12)){
                    $precio = $precioronda;
					$idPujaRetorno = $idPuja;
                }
            }
            return $idPujaRetorno;
		
	   }else{
            return false;
	   }     
    }
	
	
	function cantidadSegundaPuja($idSubasta){
       $conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		$selectSubastas;
		$resultSubastas;
    	if(session_id() == '') {
            session_start();
		}
       
       $select = "SELECT tipo, precioinicial, cantidadsegundapuja  FROM subastas WHERE id='$idSubasta'";
       $result = $conn->query($select);
       $row=$result->fetch_assoc();
       $tipoSubasta = $row['tipo'];
       $precio = $row['cantidadsegundapuja'];
        
            return $precio;
		
	   }
	
	?>