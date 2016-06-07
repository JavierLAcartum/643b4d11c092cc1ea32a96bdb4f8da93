<?php echo "Estas seguro de que quieres cerrar sesion"; 
	if(session_id() == '') {
		session_start();
	}
	$user;
	foreach (array_keys($_SESSION['user']) as $field)
		{
			$user = $field;
		}
	?>
<form id='cerrarSesion' action='<?php echo  $user ?>.php' method='post' enctype="multipart/form-data" accept-charset='UTF-8'>
	<input type='submit' name="salir" value="Cerrar sesion"/>
	<button onclick="location.href='subastador.php'"> Volver</button>
</form>