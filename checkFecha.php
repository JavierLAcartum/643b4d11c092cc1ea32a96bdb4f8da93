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
$select = "SELECT precioinicial, cambioprecio, precioactual, fechainicio, tiempocambioprecio, fechaactual, tipo, idpujaganadora FROM subastas WHERE id='$idSubasta'";
$result = $conn->query($select);
$row = $result->fetch_assoc();
$precioInicial = $row['precioinicial'];
$sumar = $row['cambioprecio'];
$precioActual=$row['precioactual'];
$tipoSubasta=$row['tipo'];
$fechaInicio = $row['fechainicio'];
$tiempoCambio = $row['tiempocambioprecio'];
$fechaCambio = $row['fechaactual'];
$ganador = $row['idpujaganadora'];
if($ganador == null){
if($fechaCambio == ""){
    $fecha = new DateTime($fechaInicio);
    $fecha->add(new DateInterval('PT'.$tiempoCambio.'S'));
    $fecha = $fecha->format('Y-m-d H:i:s');
}else{
    $fecha = new DateTime($fechaCambio);
    $fecha->add(new DateInterval('PT'.$tiempoCambio.'S'));
    $fecha = $fecha->format('Y-m-d H:i:s');
}

$fechaActual = date('Y-m-d H:i:s');
$fechaActual = new DateTime($fechaActual);
$fechaActual = $fechaActual->format('Y-m-d H:i:s');


if(strtotime($fechaActual) >= strtotime($fecha)){
if($precioActual==null){
    if($tipoSubasta == 5){
        $precioActual = $precioInicial+$sumar;
    }else if($tipoSubasta == 6){
        if($precioInicial-$sumar>0){
            $precioActual = $precioInicial-$sumar;
        }
    }
   
}else{
    if($tipoSubasta == 5){
        $precioActual = $precioActual+$sumar;
    }else if($tipoSubasta == 6){
        if($precioActual-$sumar>0){
            $precioActual = $precioActual-$sumar;
        }
    }
}

$update= "UPDATE subastas SET precioactual='$precioActual' WHERE id='$idSubasta'";

		
if ($conn->query($update) === TRUE) {
    echo $precioActual;
} else {
    echo "Error updating record: " . $conn->error;
}
    $update= "UPDATE subastas SET fechaactual='$fecha' WHERE id='$idSubasta'";
    
		
$conn->query($update);
  
}else{
    if($precioActual==null){
        echo $precioInicial;
    }else{
        echo $precioActual;
    }
}
}else{
    $select = "SELECT idpostor, cantidad FROM pujas WHERE id='$ganador'";
    $result = $conn->query($select);
    $row = $result->fetch_assoc();
    $idPostor = $row['idpostor'];
    $valor = $row['cantidad'];
    $select = "SELECT usuario FROM usuarios WHERE id='$idPostor'";
    $result = $conn->query($select);
    $row = $result->fetch_assoc();
    $user = $row['usuario'];
    echo 'El usuario '.$user.' ha ganado la subasta con un valor de '.$valor.'.';
}
?>