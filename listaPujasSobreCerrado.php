<?php
function crearTabla(){
       	
    if(isset($_GET['id'])){
        $idSubasta = $_GET['id'];
    }
    $conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
    if(session_id() == '') {
        session_start();
    }
    
    $select = "SELECT id, fecha, cantidad, idpostor FROM pujas WHERE idsubasta='$idSubasta'";
    $result = $conn->query($select);
    if($result->num_rows>0){
        $tabla='<table><tr><td>ID Puja</td><td>Fecha</td><td>Cantidad</td><td>Usuario</td></tr>';
        while($row = $result->fetch_assoc()){
			$idPuja = $row['id'];
            $fechasub= $row['fecha'];
            $cantidad= $row['cantidad'];
            $idpostor= $row['idpostor'];
                $select = "SELECT usuario FROM usuarios WHERE id='$idpostor'";
                $result2 = $conn->query($select);   
                $row2 = $result2->fetch_assoc();
                $user = $row2['usuario'];
                $tabla=$tabla.'<tr><td>'.$idPuja.'<td><td>'.$fechasub.'</td><td>'.$cantidad.'</td><td>'.$user.'</td></tr>';
        }
        $tabla=$tabla.'</table>';
        echo $tabla;
        
    
    }else{
        echo "";
        ?>
            <label style="position: absolute; margin-left: 515px; margin-top: 135px; font-family:'Segoe UI'; font-size: 13px; font-weight: bold; "> *No hay pujas actualmente* </label> 
        <?php
    }
    
    
}

crearTabla();

function visualizarPujas(){
		
		 if(isset($_GET['id'])){
			$idSubasta = $_GET['id'];
		}
		$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		if(session_id() == '') {
			session_start();
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
			
			echo "No ha realizado ninguna puja";
		}
			
	}