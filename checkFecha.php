<?php
if(isset($_GET['id'])){
    $idSubasta = $_GET['id'];
}
//Conexion($idSubasta);
$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
$selectSubastas;
$resultSubastas;
if(session_id() == '') {
    session_start();
}
function RedirectToURL($url, $tiempo)
{
    header("Refresh: $tiempo, URL=$url");
    exit;
}
?>