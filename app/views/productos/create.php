<h2>Nuevo Producto</h2>

<form method="POST" action="index.php?action=guardar">

    <input name="nombre" placeholder="Nombre" required class="form-control mb-2">

    <input name="tipodezapatillas" placeholder="Tipo de zapatillas" required class="form-control mb-2">

    <input type="number" name="precio" placeholder="Precio" required class="form-control mb-2">

    <!-- TALLE -->
    <select name="talle_id_talle" class="form-control mb-2" required>
        <option value="">Seleccionar talle</option>
        <?php foreach ($talles as $t): ?>
            <option value="<?= $t['id_talle'] ?>">
                <?= $t['talles_disponibles'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- MARCA -->
    <select name="marca_id_marca" class="form-control mb-2" required>
        <option value="">Seleccionar marca</option>
        <?php foreach ($marcas as $m): ?>
            <option value="<?= $m['id_marca'] ?>">
                <?= $m['marcas_disponibles'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- COLOR -->
    <select name="color_id_color" class="form-control mb-2" required>
        <option value="">Seleccionar color</option>
        <?php foreach ($colores as $c): ?>
            <option value="<?= $c['id_color'] ?>">
                <?= $c['colores_disponibles'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- CATEGORIA -->
    <select name="categoria_id_categoria" class="form-control mb-2" required>
        <option value="">Seleccionar categoría</option>
        <?php foreach ($categorias as $cat): ?>
            <option value="<?= $cat['id_categoria'] ?>">
                <?= $cat['tipodezapatilla'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit" class="btn btn-primary">Guardar</button>

</form>