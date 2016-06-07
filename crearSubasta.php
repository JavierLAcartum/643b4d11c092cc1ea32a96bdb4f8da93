<?php
	$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
	if(session_id() == '') {
		session_start();
	}
	$selectProductos = "SELECT id, nombre FROM productos WHERE idlote IS NULL AND idSubasta is NULL AND idusuario='".$_SESSION['user']['subastador']."'";
	$resultProductos = $conn->query($selectProductos);
	$selectLotes = "SELECT id, nombre FROM lotes WHERE idSubasta IS NULL AND idusuario='".$_SESSION['user']['subastador']."'";
	$resultLotes = $conn->query($selectLotes);
	if ($resultProductos->num_rows >= 1 | $resultLotes->num_rows >= 1) {
	
	echo "<br/><br/>Elija el tipo de subasta";
?>
	<form action="subastador.php" method="post">
		<select name="tipoSubasta">
			<option selected value="1">Dinámica descubierta</option>
			<option value="2">Dinámica anónima</option>
			<option value="3">Dinámica de tipo holandés</option>
			<option value="4">Sobre cerrado de primer precio</option>
			<option value="5">Sobre cerrado de segundo precio</option>
			<option value="6">Round Robin</option>
		</select>

		<select name="subtipo">
			<option selected value="1">Ascendente</option>
			<option value="0">Descendente</option>
		</select><br/><br/>


		<input type="number" name='precio' placeholder="Precio inicial (si procede)" step="0.01" min="0"> <br/> <br/>

		<?php
			echo "Fecha de apertura";
		?>
			<input type="datetime-local" name = 'fechainicio' required/>
			<?php
			echo "Fecha de cierre";
		?>
				<input type="datetime-local" name = 'fechacierre' required/> <br/> <br/>

				<?php
		
		if ($resultProductos->num_rows >= 1){
			echo "Elija un producto para subastar"; 		
			while($row = $resultProductos->fetch_assoc()) {
			?>
						<input type="radio" name="seleccion"value = <?php echo $row['nombre'];?>>
							<?php echo $row['nombre'];?>
							</input>
							<?php
			}
		}
	
		if ($resultLotes->num_rows >= 1){
			echo "<br/><br/>Elija un lote para subastar";
			while($row = $resultLotes->fetch_assoc()) {
			?>
								<input type="radio" name="seleccion" value = "<?php echo $row['nombre'];?>">
								<?php echo $row['nombre'];?>
									</input>
									<?php
			}
			?>
			
			
		<?php
		}
		?>
		<br/><br/>
		<input type="submit" name="crearSubasta">
	<?php
	}
	
	else{
		echo "No tiene productos ni lotes para subastar.";
	}
		?>
		<button onclick="location.href='subastador.php'"> Volver</button>
		

	</form>

	<?php
		$conn->close();
	?>
