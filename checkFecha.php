<?php

include("escribirLog.php");
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
$select = "SELECT precioinicial, cambioprecio, precioactual, fechainicio, tiempocambioprecio, fechaactual, tipo, idpujaganadora, fechacierre FROM subastas WHERE id='$idSubasta'";
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
$fechaFin = $row['fechacierre'];

if($fechaCambio == null){
    $tiempoInicial = strtotime($fechaInicio);
}else{
    $tiempoInicial = strtotime($fechaCambio);
}

$fechaActual = date('Y-m-d H:i:s');
$fechaActual = new DateTime($fechaActual);
$fechaActual = $fechaActual->format('Y-m-d H:i:s');

$tiempoActual = strtotime($fechaActual);

$tiempo = $tiempoActual-$tiempoInicial;
$repeticiones = $tiempo/$tiempoCambio;
$repeticiones = floor($repeticiones);
$totalDinero;
if($repeticiones>1 && $ganador==null && strtotime($fechaActual) <= strtotime($fechaFin)){
    $totalDinero=$repeticiones*$sumar;
    if($precioActual == ""){
        if($tipoSubasta == 5){
        $totalDinero = $totalDinero + $precioInicial;
        }else if($tipoSubasta == 6){
            $totalDinero = $precioInicial - $totalDinero;
            if($totalDinero<1){
                $totalDinero = 1;
            }
        }
    }else{
        if($tipoSubasta == 5){
            $totalDinero = $totalDinero + $precioActual;  
        }else if($tipoSubasta == 6){
            $totalDinero = $precioActual - $totalDinero;
             if($totalDinero<1){
                $totalDinero = 1;
            }

        }
    }
    $diferenciaTiempo=$tiempoCambio*$repeticiones;
    $tiempoAct = $tiempoInicial+$diferenciaTiempo;
    $precioActual = $totalDinero;
    $fechaactual = $tiempoAct;
    $tiempoAct2 = date('Y-m-d H:i:s', $tiempoAct);
    $update= "UPDATE subastas SET precioactual='$precioActual', fechaactual='$tiempoAct2' WHERE id='$idSubasta'";
    if ($conn->query($update) === TRUE) {
        //echo 'La subasta esta en un valor de: '.$precioActual;
        ?>
            </br>
            <label style="margin-left: 500px; top: 30px; font-family:'Segoe UI'; font-size: 15px; color:white;"> *La subasta está en un valor de: <?php echo $precioActual; ?>* </label>
        <?php
    } else {
        //echo "Error updating record: " . $conn->error;
    }
    
}

if($ganador == null && strtotime($fechaActual) <= strtotime($fechaFin)){
if($fechaCambio == ""){
    $fecha = new DateTime($fechaInicio);
    $fecha->add(new DateInterval('PT'.$tiempoCambio.'S'));
    $fecha = $fecha->format('Y-m-d H:i:s');
}else{
    $fecha = new DateTime($fechaCambio);
    $fecha->add(new DateInterval('PT'.$tiempoCambio.'S'));
    $fecha = $fecha->format('Y-m-d H:i:s');
}



if((strtotime($fechaActual) >= strtotime($fecha))&& $repeticiones<2){
if($precioActual==null){
    if($tipoSubasta == 5){
        $precioActual = $precioInicial+$sumar;
    }else if($tipoSubasta == 6){
        if($precioInicial-$sumar>0){
            $precioActual = $precioInicial-$sumar;
            if($precioActual<0){
                $precioActual=1;
            }
        }
    }
   
}else{
    if($tipoSubasta == 5){
        $precioActual = $precioActual+$sumar;
    }else if($tipoSubasta == 6){
        if($precioActual-$sumar>0){
            $precioActual = $precioActual-$sumar;
            if($precioActual<0){
                $precioActual=1;
            }
        }
    }
}

$update= "UPDATE subastas SET precioactual='$precioActual' WHERE id='$idSubasta'";

if ($conn->query($update) === TRUE) {
    //echo 'La subasta esta en un valor de: '.$precioActual;
    ?>
        </br>
        <label style="margin-left: 500px; top: 30px; font-family:'Segoe UI'; font-size: 15px; color:white;"> *La subasta está en un valor de: <?php echo $precioActual; ?>* </label>
    <?php
} else {
    //-echo "Error updating record: " . $conn->error;
}
    $update= "UPDATE subastas SET fechaactual='$fecha' WHERE id='$idSubasta'";
    
		
$conn->query($update);
  
}else{
    if($precioActual==null){
        //echo 'La subasta esta en un valor de: '.$precioInicial;
        ?>
            </br>
            <label style="margin-left: 500px; top: 30px; font-family:'Segoe UI'; font-size: 15px; color:white;"> *La subasta está en un valor de: <?php echo $precioInicial; ?>* </label>
        <?php
    }else{
        //echo 'La subasta esta en un valor de: '.$precioActual;
        ?>
            </br>
            <label style="margin-left: 500px; top: 30px; font-family:'Segoe UI'; font-size: 15px; color:white;"> *La subasta está en un valor de: <?php echo $precioActual; ?>* </label>
        <?php
    }
}
}else if($ganador!=null){
    $select = "SELECT idpostor, cantidad FROM pujas WHERE id='$ganador'";
    $result = $conn->query($select);
    $row = $result->fetch_assoc();
    $idPostor = $row['idpostor'];
    $valor = $row['cantidad'];
    $select = "SELECT usuario FROM usuarios WHERE id='$idPostor'";
    $result = $conn->query($select);
    $row = $result->fetch_assoc();
    $user = $row['usuario'];
    //echo 'El usuario '.$user.' ha ganado la subasta con un valor de '.$valor.'.';

    
    
   
    //echo 'El usuario '.$user.' ha ganado la subasta con un valor de '.$valor.'.';
    ?>
        </br>
        <label style="margin-left: 440px; top: 30px; font-family:'Segoe UI'; font-size: 15px; color:white;"> *El usuario <?php echo $user; ?> ha ganado la subasta con un valor de <?php echo $valor; ?>* </label>
    <?php
}else{
    ?>
            </br>
        <label style="margin-left: 500px; top: 30px; font-family:'Segoe UI'; font-size: 15px; color:white;"> *No ha habido pujas en la subasta* </label>
<?php
        
        //para el log
        $queryFinSubasta = "SELECT * FROM log WHERE descripcion = 'La subasta ".$idSubasta." ha finalizado sin pujas.'";
        $resultQueryFinSubasta = $conn ->query($queryFinSubasta);
        if($resultQueryFinSubasta->num_rows == 0){
            $queryBuscarProd = "SELECT id FROM productos WHERE idsubasta='$idSubasta' ";
            $resultNombreProd = $conn->query( $queryBuscarProd);
            if($resultNombreProd->num_rows > 0){
                $rowNombreProd = $resultNombreProd->fetch_assoc();
                $idprod = $rowNombreProd['id'];
                escribirLog("La subasta ".$idSubasta." ha finalizado sin pujas.", "NULL", $idSubasta, $idprod, "NULL", "NULL");
            }else{
                $queryBuscarLote= "SELECT id FROM lotes WHERE idsubasta='$idSubasta' ";
                $resultNombreLote = $conn->query( $queryBuscarLote);
                $rowNombreLote = $resultNombreLote->fetch_assoc();
                $idlote = $rowNombreLote['id'];
                escribirLog("La subasta ".$idSubasta." ha finalizado sin pujas.", "NULL", $idSubasta, "NULL", $idlote, "NULL");
            }
            
        }
        //fin del para el log
    
}
?>
