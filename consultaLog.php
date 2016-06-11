<!doctype html>
	<html>
	<head>
	<meta charset="utf-8" />
	<title>Consulta Log</title>
	<link rel="stylesheet" href="css/anytime.5.1.2.css"/>
	<script src="jquery-1.11.0.min.js"></script>
	<script src="anytime.5.1.2.js"></script>

<form action="administrador.php?page=consultaLog" method="post">
    Realizar consulta por:
    <select id="consultaLog" name="consultaLog" onchange="cambiar_formulario()">
        <option selected value="id">Id del Log</option>
        <option value="fecha">Fecha</option>
        <option value="idusuario">Nombre de usuario</option>
        <option value="idsubasta">idSubasta</option>
        <option value="idproducto">idProducto</option>
        <option value="idlote">idLote</option>
        <option value="idpuja">idPuja</option>
    </select>
    <span id="consulta"> <input type='number' name='valor' step='1' min='1' required/></span>
    <input type="submit" name="enviarConsultaLog">
    
</form>

<script>
    function cambiar_formulario(){
        var consulta = document.getElementById("consulta");
        var tipoConsulta = document.getElementById("consultaLog").value;
       
        if(tipoConsulta == "fecha"){
			
            consulta.innerHTML = "Desde: <input type='text' id= 'fechainicio' name = 'fechainicio' required/> Hasta: <input type='text' id = 'fechafin' name = 'fechafin' required/>";
			AnyTime.picker("fechainicio",
				{format: "%Y-%m-%d %H:%i:%s"} );
			AnyTime.picker("fechafin",
				{format: "%Y-%m-%d %H:%i:%s"} );
			
        }
        else if(tipoConsulta == "idusuario"){
            consulta.innerHTML = "<input type='text' name='valor' maxlength='20' required/>";
        }
        else{
            consulta.innerHTML = "<input type='number' name='valor' step='1' min='1' required/>";
        }
        
        
    }
</script>
