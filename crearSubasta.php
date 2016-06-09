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
?>

		
	<br/><br/>Elija el tipo de subasta<br/><br/>
	
	<form action="subastador.php" method="post">
		<select id="tipoSubasta" name="tipoSubasta" onchange="cambiar_formulario()">
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

		Fecha de apertura		
		<input type="datetime-local" name = 'fechainicio' step='1' required/>
		Fecha de cierre
		<input type="datetime-local" name = 'fechacierre' step='1' required/> <br/> <br/>
		
		<div id="segunda-puja"> </div>		
		
		<div id="precio-inicial"><input type='number' name='precioInicial' placeholder='Precio inicial' step='0.01' min='0' required/> <br/> <br/></div> 
		
		<div id="tiempo-cambio-precio"></div>
		<div id="cambio-precio"></div>
		
		<script>
		
		function cambiar_formulario(){
			
			var precioInicial = document.getElementById("precio-inicial");
			var selectSubasta = document.getElementById("tipoSubasta");
			var tipoSubasta = selectSubasta.value;
			var tiempoCambioPrecio = document.getElementById("tiempo-cambio-precio");
			var cambioPrecio = document.getElementById("cambio-precio");
			var segundaPuja = document.getElementById("segunda-puja");
			
			if (tipoSubasta == "1" | tipoSubasta == "2"| tipoSubasta == "3"){ //Si es dinámica descubierta, anónima o de tipo holandés parte de un precio inicial
				
				precioInicial.innerHTML = "<input type='number' name='precioInicial' placeholder='Precio inicial' step='0.01' min='0' required/> <br/> <br/>";
			}
			else{
				
				precioInicial.innerHTML= "";
				
			}
			
			if(tipoSubasta == "3"){ //Si es de tipo holandés tiene que elegir un tiempo tras el que se cambiará el precio y cada cuanto se cambiará
				
				tiempoCambioPrecio.innerHTML = "Elija cada cuánto tiempo desea variar el precio de la subasta: <input type='time' step='1' name = 'tiempoCambioPrecio' required/> <br/> <br/>"
				cambioPrecio.innerHTML = "Elija la cantidad que desea que el precio varíe cada vez: <input type='number' name='cambioPrecio' step='0.01' min='0' required/> <br/> <br/>";
			
			}
			else{
				
				tiempoCambioPrecio.innerHTML = "";
				cambioPrecio.innerHTML = "";				
			}
			
			if(tipoSubasta == "6"){ //Si es Round Robin deberá elegir una fecha para la segunda puja
				
				segundaPuja.innerHTML = "Seleccione una fecha para la segunda puja: <input type='datetime-local' name = 'fechaSegundaPuja' step='1' required/> <br/> <br/>";
				
			}
			else{
				segundaPuja.innerHTML = "";
			}
			
		}	
		

		</script>
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
