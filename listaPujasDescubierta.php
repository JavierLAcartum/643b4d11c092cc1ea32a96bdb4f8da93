<?php
function crearTabla(){
       	
    if(isset($_GET['id'])){
        $idSubasta = $_GET['id'];
    }
    $conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
    if(session_id() == '') {
        session_start();
    }
    
    $select = "SELECT fecha, cantidad, idpostor FROM pujas WHERE idsubasta='$idSubasta'";
    $result = $conn->query($select);
    if($result->num_rows>0){
        $tabla='<table><tr><td>Fecha</td><td>Puja</td><td>Usuario</td></tr>';
        while($row = $result->fetch_assoc()){
            $fechasub= $row['fecha'];
            $cantidad= $row['cantidad'];
            $idpostor= $row['idpostor'];
                $select = "SELECT usuario FROM usuarios WHERE id='$idpostor'";
                $result2 = $conn->query($select);   
                $row2 = $result2->fetch_assoc();
                $user = $row2['usuario'];
                $tabla=$tabla.'<tr><td>'.$fechasub.'</td><td>'.$cantidad.'</td><td>'.$user.'</td></tr>';
        }
        $tabla=$tabla.'</table>';
        echo $tabla;
        
    
    }else{
        echo "No hay pujas actualmente";
    }
    
    
}

crearTabla();
?>