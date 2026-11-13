<?php
$errores = [
    'campos_requeridos'  => 'Todos los campos son obligatorios.',
    'precio_invalido'    => 'El precio debe ser un número mayor a cero.',
    'producto_duplicado' => 'Ya existe un producto con ese nombre, talle y marca.',
    'imagen_requerida'   => 'Tenés que subir una imagen del producto.',
    'imagen_invalida'    => 'La imagen no es válida. Usá un archivo JPG, PNG o WEBP.',
    'imagen_grande'      => 'La imagen es demasiado pesada. El máximo es 2 MB.',
];
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Alta de Producto | GamaStore</title>
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
            <h1 class="page-titulo"><i class="bi bi-plus-circle"></i> Registrar Producto</h1>
            <p class="page-sub">Completá los datos para registrar una zapatilla en GamaStore.</p>
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
            <form method="POST" action="index.php?action=guardar" enctype="multipart/form-data">

                <div class="form-grid">

                    <div class="campo col-3">
                        <label for="nombre">Nombre del producto</label>
                        <div class="input-wrap">
                            <i class="bi bi-tag icono"></i>
                            <input type="text" id="nombre" name="nombre" placeholder="Ej: Air Max 90" required autofocus>
                        </div>
                    </div>

                    <div class="campo col-3">
                        <label for="tipodezapatillas">Tipo de zapatilla</label>
                        <div class="input-wrap">
                            <i class="bi bi-lightning icono"></i>
                            <input type="text" id="tipodezapatillas" name="tipodezapatillas" placeholder="Ej: Running, Casual..." required>
                        </div>
                    </div>

                    <div class="campo col-6">
                        <label for="descripcion">Descripción</label>
                        <div class="input-wrap area">
                            <i class="bi bi-card-text icono"></i>
                            <textarea id="descripcion" name="descripcion" rows="3"
                                      placeholder="Detalles del producto (materiales, uso, etc.)"></textarea>
                        </div>
                    </div>

                    <div class="campo col-3">
                        <label for="precio">Precio</label>
                        <div class="input-wrap">
                            <i class="bi bi-currency-dollar icono"></i>
                            <input type="number" id="precio" name="precio" placeholder="0" min="1" step="0.01" required>
                        </div>
                    </div>

                    <div class="campo col-3">
                        <label for="talle">Talle</label>
                        <div class="input-wrap">
                            <i class="bi bi-rulers icono"></i>
                            <select id="talle" name="talle_id_talle" required>
                                <option value="">Seleccionar</option>
                                <?php foreach ($talles as $t): ?>
                                    <option value="<?= $t['id_talle'] ?>"><?= htmlspecialchars($t['talles_disponibles']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i class="bi bi-chevron-down flecha"></i>
                        </div>
                    </div>
                    
                    <div class="campo col-6">
                        <label for="imagen">Imagen del producto <small>(JPG, PNG o WEBP · máx. 2 MB)</small></label>
                        <div class="img-upload">
                            <div class="img-preview" id="imgPreview"><i class="bi bi-image"></i></div>
                            <button type="button" class="btn btn-ghost" onclick="document.getElementById('imagen').click()">
                                <i class="bi bi-upload"></i> Elegir imagen
                            </button>
                            <input type="file" id="imagen" name="imagen" accept="image/jpeg,image/png,image/webp" required>
                            <span class="img-nombre" id="imgNombre">Ningún archivo seleccionado</span>
                        </div>
                    </div>

                    <div class="campo col-3">
                        <label for="marca">Marca</label>
                        <div class="input-wrap">
                            <i class="bi bi-award icono"></i>
                            <select id="marca" name="marca_id_marca" required>
                                <option value="">Seleccionar</option>
                                <?php foreach ($marcas as $m): ?>
                                    <option value="<?= $m['id_marca'] ?>"><?= htmlspecialchars($m['marcas_disponibles']) ?></option>
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
                                    <option value="<?= $cat['id_categoria'] ?>"><?= htmlspecialchars($cat['tipodezapatilla']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i class="bi bi-chevron-down flecha"></i>
                        </div>
                    </div>

                </div>

                <div class="form-acciones">
                    <button type="submit" class="btn btn-main"><i class="bi bi-save"></i> Guardar producto</button>
                    <a href="index.php?action=productos" class="btn btn-ghost">Cancelar</a>
                </div>

            </form>
        </div>
    </div>

</main>
<script>
document.getElementById('imagen').addEventListener('change', function () {
    var f = this.files[0];
    var prev = document.getElementById('imgPreview');
    var nom  = document.getElementById('imgNombre');

    if (!f) {
        prev.innerHTML = '<i class="bi bi-image"></i>';
        nom.textContent = 'Ningún archivo seleccionado';
        return;
    }
    if (f.size > 2 * 1024 * 1024) {
        alert('La imagen supera los 2 MB. Elegí una más liviana.');
        this.value = '';
        prev.innerHTML = '<i class="bi bi-image"></i>';
        nom.textContent = 'Ningún archivo seleccionado';
        return;
    }
    nom.textContent = f.name;
    prev.innerHTML = '<img src="' + URL.createObjectURL(f) + '" alt="Vista previa">';
});
</script>

</body>
</html>