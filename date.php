<?php
$string = '2000-01-01 23:59:00' ;
$fecha= new DateTime($string);
$hora = "00:00:10";
$date = strtotime($hora);

$fecha->add(new DateInterval('PT70S'));

echo $fecha->format('Y-m-d H:i:s') . "\n";

?>