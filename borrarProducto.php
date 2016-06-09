<?php
	if(session_id() == '') {
		session_start();
	}
	
	$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");

	$selectProductos = "SELECT id, nombre FROM productos WHERE idusuario='".$_SESSION['user']['subastador']."' AND idlote IS NULL AND idsubasta IS NULL";
	$resultProductos = $conn->query($selectProductos);
	
	$selectLotes = "SELECT id, nombre FROM lotes WHERE idusuario='".$_SESSION['user']['subastador']."' AND idsubasta IS NULL";
	$resultLotes = $conn->query($selectLotes);

	if ($resultProductos->num_rows >= 1 | $resultLotes->num_rows >= 1) {
		
		echo "Seleccione los productos o lotes que desea eliminar:";
		?><form action="subastador.php" method="post">
		<?php
		if($resultProductos->num_rows >= 1){
		?>
			PRODUCTOS: <br/>
		
		<?php
		
		}
		while($row = $resultProductos->fetch_assoc()) {
		?>
			<input type="checkbox" name = "productoSeleccionado[]" value = "<?php echo $row['nombre']; ?>" > <?php echo $row['nombre']?> </input>
		<?php
		}
		
		if($resultLotes->num_rows >= 1){
		?>
			<br/><br/>LOTES: <br/>
			
		<?php
		}
		while($row = $resultLotes->fetch_assoc()) {
		?>
			
			<input type="checkbox" name = "productoSeleccionado[]" value = "<?php echo $row['nombre']; ?>" value> <?php echo $row['nombre']?> </input>
		<?php
		}
		?>
		
		<br/><br/><input type="submit" name="borrarProducto">
		</form>
		<button onclick="location.href='subastador.php'"> Volver</button>
		<?php

	}else{
		echo "";
		?>
		<label style="margin-left: 470px; font-family:'Segoe UI'; font-size: 20px;"> No tiene productos o lotes que borrar </label>
		<?php
	}
	$conn->close();
?>