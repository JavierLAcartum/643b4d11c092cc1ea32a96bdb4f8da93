<!DOCTYPE html>
<html>
	<head>
		<title>
			POSTOR
		</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="estilos.css" media="screen" />
	</head>
	<body>
		
		<div class="wrapper">
			<div>
				<?php
				$conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
				session_start();
				$selectPujas = "SELECT id,fecha,idsubasta FROM pujas WHERE idpostor='".$_SESSION['user']['postor']."'";
				$resultPujas = $conn->query($selectPujas);
			
				?>
				PUJAS REALIZADAS
				<table>
					<tr>
						<td>ID PUJA</td>
						<td>FECHA</td>
						<td>ID SUBASTA</td>
						<td>NOMBRE PRODUCTO/LOTE</td>
						<td>GANADOR</td>
					</tr>
				<?php
				
				while($row = $resultPujas->fetch_array())
				{
					$idSubasta = $row[2];
					$nombreProducto;
					$ganador;
					
					$selectProducto = ("SELECT nombre FROM productos WHERE idsubasta='$idSubasta'");
					$resultProducto = $conn->query($selectProducto);
					while($nombreP = $resultProducto->fetch_array()){
							$nombreProducto = $nombreP[0];
					}
			
					$selectLote = ("SELECT nombre FROM lotes WHERE idsubasta='$idSubasta'");
					$resultLote = $conn->query($selectLote);
					while($nombreL = $resultLote->fetch_array()){
						$nombreProducto = $nombreL[0];
					}
					
					$selectGanador = ("SELECT id FROM subastas WHERE id = '$idSubasta' AND idganador ='".$_SESSION['user']['postor']."'");
					$resultGanador = $conn->query($selectGanador);
					if($resultGanador->num_rows == 1){
						
						$ganador = "SI";
						
					}
					else{
						$ganador = "NO";
					}
					
					echo "<tr>";
						echo "<td>".$row[0]."</td>";
						echo "<td>".$row[1]."</td>";
						echo "<td>".$row[2]."</td>";
						echo "<td>".$nombreProducto."</td>";
						echo "<td>".$ganador."</td>";
					echo "</tr>";
				}
				echo "</table>";
				$conn->close();
				?>
			
		</div>
	</body
	</html>