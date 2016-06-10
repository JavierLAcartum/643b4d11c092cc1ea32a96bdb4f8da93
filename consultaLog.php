<form action="administrador.php" method="post">
    Realizar consulta por:
    <select id="consultaLog" name="consultaLog" onchange="cambiar_formulario()">
        <option selected value="1">Id del Log</option>
        <option value="2">Fecha</option>
        <option value="3">idUsuario</option>
        <option value="4">idSubasta</option>
        <option value="5">idProducto</option>
    </select>
    <div id="consulta"> <input type='number' name='id' step='1' min='1' required/></div>
</form>

<script>
    function cambiar_formulario(){
        var consulta = document.getElementById("consulta");
        var tipoConsulta = document.getElementById("consultaLog").value;
        if(tipoConsulta == 1){
            consulta.innerHTML = " <input type='number' name='id' step='1' min='1' required/><br/> <br/>";
        }
        if(tipoConsulta == 2){
            consulta.innerHTML = "<input type='datetime-local' name = 'fecha' step='1' required/> <br/> <br/>";
        }
        if(tipoConsulta == 3){
            consulta.innerHTML = "<input type='number' name='idusuario' step='1' min='1' required/><br/> <br/>";
        }
        if(tipoConsulta == 4){
            consulta.innerHTML = "<input type='number' name='idsubasta' step='1' min='1' required/><br/> <br/>";
        }
        if(tipoConsulta == 5){
            consulta.innerHTML = "<input type='number' name='idproducto' step='1' min='1' required/><br/> <br/>";
        }
        
    }
</script>