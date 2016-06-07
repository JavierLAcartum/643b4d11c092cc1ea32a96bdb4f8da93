<?php
	if(session_id() == '') {
		session_start();
	}
	
	$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");

	$select = "SELECT id, nombre FROM productos WHERE idusuario='".$_SESSION['user']['subastador']."' AND idlote IS NULL AND idsubasta IS NULL";
	$result = $conn->query($select);

	if ($result->num_rows > 1) {
		?><form action="subastador.php" method="post">
		<input type='text' name='nombreLote' placeholder="Nombre del lote" maxlength="44" required/>
		<?php
		while($row = $result->fetch_assoc()) {
		?>
		<input type="checkbox" name="<?php echo $row['id']; ?>" > <?php echo $row['nombre']?> </input>
		<?php
		}
		?><input type="submit" name="crearLote" value="Crear lote">
		<button onclick="location.href='subastador.php'"> Volver</button>
		
		</form>
		
		<?php

	}else{
		echo "No puede crear lotes, no tiene al menos 2 productos";
	}
	$conn->close();
?>