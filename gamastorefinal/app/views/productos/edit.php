<form method="POST" action="index.php?action=actualizar">
<input type="hidden" name="id" value="<?= $producto['id_producto'] ?>">
<input name="nombre" value="<?= $producto['nombre'] ?>">
<input name="tipodezapatilla" value="<?= $producto['tipodezapatilla'] ?>">
<input name="precio" value="<?= $producto['precio'] ?>">
<input name="talle_id_talle" value="<?= $producto['precio'] ?>">
<button>Actualizar</button>
</form>
