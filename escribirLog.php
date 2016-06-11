<?php
function escribirLog($descripcion, $idusuario, $idsubasta, $idproducto, $idlote, $idpuja){
   
	
	$fecha = date('Y-m-d H:i:s');
		
	//Escribimos en la base de datos
	$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
	
	$sql = ("INSERT INTO log (fecha, descripcion, idusuario, idsubasta, idproducto, idlote, idpuja) VALUES ('$fecha', '$descripcion', $idusuario, $idsubasta, $idproducto, $idlote, $idpuja)");
	
	if ($conn->query($sql) === TRUE) {
		echo "New record created successfully";
		
	} else {
		
		echo "Error: " . $sql . "<br>" . $conn->error;
	}

	
	$conn->close();
	
    }
?>