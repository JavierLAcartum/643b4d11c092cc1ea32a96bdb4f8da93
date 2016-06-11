<form action="administrador.php" method="post">

    <select name='tipoUsuario' style="width:105px; margin-left: 580px; margin-top: 200px;">
        <option selected value='administrador'>Administrador</option>
        <option value='subastador'>Subastador</option>
    </select>
    <br/><br/>

    <input type='text' name='username' id='username' placeholder="Usuario" maxlength="20" style="width:100px; margin-left: 580px;" required />
    <br/><br/>
    <input type='password' name='password' id='password' placeholder="Password" maxlength="20" style="width:100px; margin-left: 580px;" required />
    <br/><br/>
    <input type='text' name='nombre' id='nombre' placeholder="Nombre" maxlength="20" style="width:100px; margin-left: 580px;" required />
    <br/><br/>
    <input type='text' name='apellidos' id='apellidos' placeholder="Apellidos" maxlength="40" style="width:100px; margin-left: 580px;" required />
    <br/><br/>
    <input type='submit' name="registro" style="margin-left: 600px;" />

</form>