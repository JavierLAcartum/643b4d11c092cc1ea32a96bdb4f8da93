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
$select = "SELECT precioinicial, cambioprecio, precioactual, tipo FROM subastas WHERE id='$idSubasta'";
$result = $conn->query($select);
$row = $result->fetch_assoc();
$precioInicial = $row['precioinicial'];
$sumar = $row['cambioprecio'];
$precioActual=$row['precioactual'];
$tipoSubasta=$row['tipo'];
if($precioActual==null){
    if(tipo == 5){
        $precioActual = $precioInicial+$sumar;
    }else if(tipo == 6){
        $precioActual = $precioInicial-$sumar;
    }
   
}else{
    if(tipo == 5){
        $precioActual = $precioActual+$sumar;
    }else if(tipo == 6){
        $precioActual = $precioActual-$sumar;
    }
}

$update= "UPDATE subastas SET precioactual='$precioActual' WHERE id='$idSubasta'";

		
if ($conn->query($update) === TRUE) {
    echo $precioActual;
    return true;
} else {
    echo "Error updating record: " . $conn->error;
    return false;
}
?>