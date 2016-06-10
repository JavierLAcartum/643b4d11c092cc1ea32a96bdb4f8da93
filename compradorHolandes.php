<?php
function RedirectToURL($url, $tiempo)
{
	header("Refresh: $tiempo, URL=$url");
    exit;
}

if(isset($_GET['id'])){
        $idSubasta = $_GET['id'];
    }

$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
$selectSubastas;
$resultSubastas;
if(session_id() == '') {
    session_start();
}

$idUser = $_SESSION['user']['postor'];

$select = "SELECT cambioprecio FROM subastas WHERE id='$idSubasta'";
$result = $conn->query($selectProducto);
$row=$result->fetch_assoc();
$valor = $row['cambioprecio'];


$select = "INSERT INTO pujas (fecha, cantidad, idsubasta, idpostor) VALUES ('$date', '$valor',    '$idSubasta', '$idUser')";

if ($conn->query($select) === TRUE) {
    echo "PUJA GUARDADA CORRECTAMENTE";
    return true;
} else {
    echo "Error updating record: " . $conn->error;
    return false;
}

$select = "SELECT id FROM pujas WHERE idsubasta='$idSubasta'";
$result = $conn->query($selectProducto);
$row=$result->fetch_assoc();
$idPuja = $row['id'];

$update= "UPDATE subastas SET idpujaganadora='$idPuja' WHERE id='$idSubasta'";

		
if ($conn->query($update) === TRUE) {
    echo "PUJA GANADORA GUARDADA CORRECTAMENTE";
    return true;
} else {
    echo "Error updating record: " . $conn->error;
    return false;
}
       			
RedirectToURL("postor.php", 0);
>