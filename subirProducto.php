<form id='subirProducto' class="input-list style-2 clearfix" action='subastador.php' method='post' enctype="multipart/form-data" accept-charset='UTF-8'>
	<input type='text' name='nombreProducto' placeholder="Nombre del producto" maxlength="44" required/>
	<br>
	<br>
	<textarea rows="4" cols="50" name="descripcionProducto" placeholder="Descripcion del producto" form="subirProducto" required></textarea>
	<br>
	<br>
	<label>Si lo desea puede subir una imagen del producto:</label>
	<input type='file' name='imagenProducto'/>
	<br>
	<br>
	<input type='submit' name="subirProducto"/>
</form>