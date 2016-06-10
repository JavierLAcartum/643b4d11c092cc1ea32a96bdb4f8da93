<?php
function crearTabla(){
       	
    if(isset($_GET['id'])){
        $idSubasta = $_GET['id'];
    }
    $conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
    if(session_id() == '') {
        session_start();
    }
    
    echo '<table><tr><td>TEST</td></tr></table>';
}

crearTabla();
?>