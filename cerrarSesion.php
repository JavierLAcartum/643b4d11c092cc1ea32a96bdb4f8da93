<?php
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
	</br>
	<label style="position: inherit; margin-left: 510px; top: 150px; font-family:'Segoe UI'; font-size: 20px;"> ¿Estás seguro de que quieres cerrar sesión? </label> 
	</br> </br>
	<input type='submit' name="salir" value="Cerrar sesion" style="position: inherit; margin-left: 625px; top: 150px;" />
	<button style="position: inherit; top:150px;" onclick="location.href='subastador.php'"> Volver</button>
</form>