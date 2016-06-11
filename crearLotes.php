<?php
	if(session_id() == '') {
		session_start();
	}
	
	$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");

	$select = "SELECT id, nombre FROM productos WHERE idusuario='".$_SESSION['user']['subastador']."' AND idlote IS NULL AND idsubasta IS NULL";
	$result = $conn->query($select);

	if ($result->num_rows > 1) {
		?><form action="subastador.php" method="post">
		<p>
			<label style="margin-left: 450px; font-family:'Segoe UI'; font-size: 15px;">Nombre del lote:</label>
			<input style="margin-left: 15px;" type='text' name='nombreLote' placeholder="Nombre del lote" maxlength="44" required/>
		</p>
		<label style="margin-left: 450px; font-family:'Segoe UI'; font-size: 15px;">Descripción:</label>
		<p>
			<textarea style="margin-left: 450px;" rows="4" cols="50" name="descripcionLote" placeholder="Descripción del Lote" maxlength="200" required></textarea>
		</p>
		<label style="margin-left: 450px; font-family:'Segoe UI'; font-size: 15px;">Seleccione los productos a incluir en el lote:</label>
		<?php
		while($row = $result->fetch_assoc()) {
		?>
		<input type="checkbox" name="<?php echo $row['id']; ?>"> <?php echo $row['nombre']?> </input>
		<?php
		}
		?>
		<p>
			<input style="font-family:'Segoe UI'; font-size: 15px; margin-left: 450px;" type="submit" name="crearLote" value="Crear lote">
		</p>
		
		</form>
		
		<?php

	}else{
		echo "";
		?>
		<label style="margin-left: 470px; font-family:'Segoe UI'; font-size: 20px;"> No puede crear lotes, no tiene al menos 2 productos </label>
		<?php
	}

	$conn->close();
?>