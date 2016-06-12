<?php

function visualizarPujas(){
			
		 if(isset($_GET['id'])){
			$idSubasta = $_GET['id'];
		}
		$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
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
		
		if($tipoUsuario == "postor"){
		
			$selectPujas = "SELECT * FROM pujas WHERE idsubasta='$idSubasta' AND idpostor='".$_SESSION['user']['postor']."'";
			$resultPujas = $conn->query($selectPujas);
		}
		
		else if($tipoUsuario == "subastador"){
			
			$selectPujas = "SELECT * FROM pujas WHERE idsubasta='$idSubasta'";
			$resultPujas = $conn->query($selectPujas);
		}
		
		if ($resultPujas->num_rows > 0){
		
			$tabla='<table><tr><td>ID Puja</td><td>Fecha</td><td>Cantidad</td></tr>';
			while($rowPuja= $resultPujas->fetch_assoc()) {
			
				$idPuja = $rowPuja['id'];
				$fechaPuja = $rowPuja['fecha'];
				$cantidadPuja = $rowPuja['cantidad'];
				
				
				$tabla=$tabla.'<tr><td>'.$idPuja.'</td><td>'.$fechaPuja.'</td><td>'.$cantidadPuja.'</td><tr>';
			}	
				
			$tabla=$tabla.'</table>';
			echo $tabla;
		}	
		else{
			echo "";
			 ?>
            	<label style="position: absolute; margin-left: 515px; margin-top: 135px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold; "> *No ha realizado ninguna puja* </label> 
        	<?php
		}
			
	}
	visualizarPujas();
	
	?>