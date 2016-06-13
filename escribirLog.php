<?php
function escribirLog($descripcion, $idusuario, $idsubasta, $idproducto, $idlote, $idpuja){
   
	
	$fecha = date('Y-m-d H:i:s');
		
	//Escribimos en la base de datos
	$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
    
    if($idproducto!="NULL"){
        $queryNombreProducto =("SELECT nombre FROM productos WHERE id='$idproducto'");
        $resultQueryNombreProd = $conn ->query($queryNombreProducto);
        $rowNombreProd = $resultQueryNombreProd->fetch_assoc();
        $nombreproducto = $rowNombreProd['nombre'];
    }else{
        $nombreproducto = "";
    }
    
    if($idlote!="NULL"){
        $queryNombreLote = ("SELECT nombre FROM lotes WHERE id='$idlote'");
        $resultQueryNombreLote = $conn ->query($queryNombreLote);
        $rowNombreLote = $resultQueryNombreLote->fetch_assoc();
        $nombrelote = $rowNombreLote['nombre'];
    }else{
        $nombrelote = "";
    }
    
    if($descripcion=="La subasta ".$idsubasta." ha finalizado."||(strpos($descripcion, 'La puja ganadora de la subasta') !== false)||(strpos($descripcion, 'ha finalizado sin pujas') !== false)){
        $queryFechaFin =("SELECT fechacierre FROM subastas WHERE id='$idsubasta'");
        $resultQueryFechaFin = $conn ->query($queryFechaFin);
        $rowFechaFin = $resultQueryFechaFin->fetch_assoc();
        $fecha = $rowFechaFin['fechacierre'];
    }
    
	
	$sql = ("INSERT INTO log (fecha, descripcion, idusuario, idsubasta, idproducto, idlote, idpuja, nombreproducto, nombrelote) VALUES ('$fecha', '$descripcion', $idusuario, $idsubasta, $idproducto, $idlote, $idpuja, '$nombreproducto', '$nombrelote')");
	
	if ($conn->query($sql) === TRUE) {
		echo "";
		
	} else {
		
		echo "Error: " . $sql . "<br>" . $conn->error;
	}

	
	$conn->close();
	
    }
?>