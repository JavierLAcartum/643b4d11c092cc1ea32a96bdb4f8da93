<?php

$fecha= new DateTime('2000-01-01');
$hora = "00:00:10";
$date = strtotime($hora);

$fecha->add(new DateInterval('PT30S'));

echo $fecha->format('Y-m-d H:i:s') . "\n";



?>