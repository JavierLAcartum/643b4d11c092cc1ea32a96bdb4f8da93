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
        $tabla='<table><tr><td>Fecha</td><td>Puja</td></tr>';
        while($row = $result->fetch_assoc()){
            $fechasub= $row['fecha'];
            $cantidad= $row['cantidad'];
            $idpostor= $row['idpostor'];
                $tabla=$tabla.'<tr><td>'.$fechasub.'</td><td>'.$cantidad.'</td></tr>';
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
?>