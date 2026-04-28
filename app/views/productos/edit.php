<h2>Editar Producto</h2>

<form method="POST" action="index.php?action=actualizar">

<input type="hidden" name="id" value="<?= $producto['id_producto'] ?>">

<input name="nombre" value="<?= $producto['nombre'] ?>" class="form-control mb-2">

<input name="tipodezapatillas" value="<?= $producto['tipodezapatillas'] ?>" class="form-control mb-2">

<input type="number" name="precio" value="<?= $producto['precio'] ?>" class="form-control mb-2">

<!-- TALLE -->
<select name="talle_id_talle" class="form-control mb-2">
    <?php foreach ($talles as $t): ?>
        <option value="<?= $t['id_talle'] ?>"
            <?= $t['id_talle'] == $producto['talle_id_talle'] ? 'selected' : '' ?>>
            <?= $t['talles_disponibles'] ?>
        </option>
    <?php endforeach; ?>
</select>

<!-- MARCA -->
<select name="marca_id_marca" class="form-control mb-2">
    <?php foreach ($marcas as $m): ?>
        <option value="<?= $m['id_marca'] ?>"
            <?= $m['id_marca'] == $producto['marca_id_marca'] ? 'selected' : '' ?>>
            <?= $m['marcas_disponibles'] ?>
        </option>
    <?php endforeach; ?>
</select>

<!-- COLOR -->
<select name="color_id_color" class="form-control mb-2">
    <?php foreach ($colores as $c): ?>
        <option value="<?= $c['id_color'] ?>"
            <?= $c['id_color'] == $producto['color_id_color'] ? 'selected' : '' ?>>
            <?= $c['colores_disponibles'] ?>
        </option>
    <?php endforeach; ?>
</select>

<!-- CATEGORIA -->
<select name="categoria_id_categoria" class="form-control mb-2">
    <?php foreach ($categorias as $cat): ?>
        <option value="<?= $cat['id_categoria'] ?>"
            <?= $cat['id_categoria'] == $producto['categoria_id_categoria'] ? 'selected' : '' ?>>
            <?= $cat['tipodezapatilla'] ?>
        </option>
    <?php endforeach; ?>
</select>

<button class="btn btn-success">Actualizar</button>

</form>