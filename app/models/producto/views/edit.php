<?php
$errores = [
    'campos_requeridos'  => 'Todos los campos son obligatorios.',
    'precio_invalido'    => 'El precio debe ser un número mayor a cero.',
    'producto_duplicado' => 'Ya existe otro producto con ese nombre, talle y marca.',
    'imagen_invalida'    => 'La imagen no es válida. Usá un archivo JPG, PNG o WEBP.',
    'imagen_grande'      => 'La imagen es demasiado pesada. El máximo es 2 MB.',
    'codigo_duplicado'   => 'Ya existe otro producto con ese código.',
    'codigo_largo'       => 'El código no puede superar los 50 caracteres.',
];
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Producto | GamaStore</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="css/gamastore.css" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../../../ayudantes/navbar.php'; ?>

<main class="wrap estrecho">

    <div class="page-head">
        <div>
            <div class="page-label">Productos</div>
            <h1 class="page-titulo"><i class="bi bi-pencil-square"></i> Editar producto</h1>
            <p class="page-sub">Modificá los datos de <strong><?= htmlspecialchars($producto['nombre']) ?></strong>.</p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=productos" class="btn btn-ghost"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
    </div>

    <?php if (isset($errores[$error])): ?>
        <div class="alerta danger">
            <i class="bi bi-exclamation-triangle-fill"></i> <?= $errores[$error] ?>
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <div class="panel">
        <div class="panel-body">
            <form method="POST" action="index.php?action=actualizar" enctype="multipart/form-data">

                <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">

                <div class="form-grid">

                    <div class="campo col-3">
                        <label for="nombre">Nombre del producto</label>
                        <div class="input-wrap">
                            <i class="bi bi-tag icono"></i>
                            <input type="text" id="nombre" name="nombre" required
                                   value="<?= htmlspecialchars($producto['nombre']) ?>">
                        </div>
                    </div>

                    <div class="campo col-3">
                        <label for="tipodezapatillas">Tipo de zapatilla</label>
                        <div class="input-wrap">
                            <i class="bi bi-lightning icono"></i>
                            <input type="text" id="tipodezapatillas" name="tipodezapatillas" required
                                   value="<?= htmlspecialchars($producto['tipodezapatillas']) ?>">
                        </div>
                    </div>

                    <div class="campo col-6">
                        <label for="codigo">Código del producto <small>(opcional · ej: código de barras o SKU)</small></label>
                        <div class="input-wrap">
                            <i class="bi bi-upc-scan icono"></i>
                            <input type="text" id="codigo" name="codigo" maxlength="50" placeholder="Ej: 7791234567890" autocomplete="off"
                                   value="<?= htmlspecialchars($producto['codigo'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="campo col-6">
                        <label for="descripcion">Descripción</label>
                        <div class="input-wrap area">
                            <i class="bi bi-card-text icono"></i>
                            <textarea id="descripcion" name="descripcion" rows="3"><?= htmlspecialchars($producto['descripcion'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="campo col-3">
                        <label for="precio">Precio</label>
                        <div class="input-wrap">
                            <i class="bi bi-currency-dollar icono"></i>
                            <input type="number" id="precio" name="precio" min="1" step="0.01" required
                                   value="<?= $producto['precio'] ?>">
                        </div>
                    </div>

                    <div class="campo col-3">
                        <label for="talle">Talle</label>
                        <div class="input-wrap">
                            <i class="bi bi-rulers icono"></i>
                            <select id="talle" name="talle_id_talle" required>
                                <option value="">Seleccionar</option>
                                <?php foreach ($talles as $t): ?>
                                    <option value="<?= $t['id_talle'] ?>" <?= $t['id_talle'] == $producto['talle_id_talle'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($t['talles_disponibles']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <i class="bi bi-chevron-down flecha"></i>
                        </div>
                    </div>
                <div class="campo col-6">
                    <label for="imagen">Imagen del producto <small>(JPG, PNG o WEBP · máx. 2 MB)</small></label>
                    <div class="img-upload">
                        <div class="img-preview" id="imgPreview">
                            <?php if (!empty($producto['imagen'])): ?>
                                <img src="uploads/productos/<?= htmlspecialchars($producto['imagen']) ?>" alt="Imagen actual">
                            <?php else: ?>
                                <i class="bi bi-image"></i>
                            <?php endif; ?>
                        </div>
                        <button type="button" class="btn btn-ghost" onclick="document.getElementById('imagen').click()">
                            <i class="bi bi-upload"></i> Cambiar imagen
                        </button>
                        <input type="file" id="imagen" name="imagen" accept="image/jpeg,image/png,image/webp">
                        <span class="img-nombre" id="imgNombre">
                            <?= !empty($producto['imagen']) ? 'Imagen actual (elegí otra para reemplazarla)' : 'Este producto todavía no tiene imagen' ?>
                        </span>
                    </div>
                </div>

                    <div class="campo col-3">
                        <label for="marca">Marca</label>
                        <div class="input-wrap">
                            <i class="bi bi-award icono"></i>
                            <select id="marca" name="marca_id_marca" required>
                                <option value="">Seleccionar</option>
                                <?php foreach ($marcas as $m): ?>
                                    <option value="<?= $m['id_marca'] ?>" <?= $m['id_marca'] == $producto['marca_id_marca'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($m['marcas_disponibles']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <i class="bi bi-chevron-down flecha"></i>
                        </div>
                    </div>

                    <div class="campo col-3">
                        <label for="categoria">Categoría</label>
                        <div class="input-wrap">
                            <i class="bi bi-collection icono"></i>
                            <select id="categoria" name="categoria_id_categoria" required>
                                <option value="">Seleccionar</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat['id_categoria'] ?>" <?= $cat['id_categoria'] == $producto['categoria_id_categoria'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['tipodezapatilla']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <i class="bi bi-chevron-down flecha"></i>
                        </div>
                    </div>

                </div>

                <div class="form-acciones">
                    <button type="submit" class="btn btn-main"><i class="bi bi-save"></i> Actualizar producto</button>
                    <a href="index.php?action=productos" class="btn btn-ghost">Cancelar</a>
                </div>

            </form>
        </div>
    </div>

</main>
<script>
document.getElementById('imagen').addEventListener('change', function () {
    var f = this.files[0];
    if (!f) return;

    if (f.size > 2 * 1024 * 1024) {
        alert('La imagen supera los 2 MB. Elegí una más liviana.');
        this.value = '';
        return;
    }
    document.getElementById('imgNombre').textContent = f.name;
    document.getElementById('imgPreview').innerHTML =
        '<img src="' + URL.createObjectURL(f) + '" alt="Vista previa">';
});
</script>
</body>
</html>