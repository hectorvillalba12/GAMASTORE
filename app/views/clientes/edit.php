<h2>Editar Cliente</h2>

<form method="POST" action="index.php?action=update_cliente">

<input type="hidden" name="id_persona" value="<?= $cliente['id_persona'] ?>">

<input name="nombre" value="<?= $cliente['nombre'] ?>">
<input name="apellido" value="<?= $cliente['apellido'] ?>">
<input name="dni" value="<?= $cliente['dni'] ?>">
<input name="telefono" value="<?= $cliente['telefono'] ?>">
<input name="email" value="<?= $cliente['email'] ?>">

<button type="submit">Actualizar</button>

</form>