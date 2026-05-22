<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alta de Producto - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-plus-circle"></i> Alta de Producto</h2>
                <a href="index.php?action=productos" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>

            <?php
            $errores = [
                'campos_requeridos' => 'Todos los campos son obligatorios.',
                'precio_invalido'   => 'El precio debe ser un número mayor a cero.',
            ];
            if (isset($_GET['error']) && isset($errores[$_GET['error']])): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <?= $errores[$_GET['error']] ?>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="index.php?action=guardar">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre del producto</label>
                                <input type="text" name="nombre" class="form-control"
                                    placeholder="Ej: Air Max 90" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tipo de zapatilla</label>
                                <input type="text" name="tipodezapatillas" class="form-control"
                                    placeholder="Ej: Running, Casual..." required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Precio</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="precio" class="form-control"
                                        placeholder="0" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Talle</label>
                                <select name="talle_id_talle" class="form-select" required>
                                    <option value="">-- Seleccionar --</option>
                                    <?php foreach ($talle as $t): ?>
                                        <option value="<?= $t['id_talle'] ?>">
                                            <?= htmlspecialchars($t['talles_disponibles']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Color</label>
                                <select name="color_id_color" class="form-select" required>
                                    <option value="">-- Seleccionar --</option>
                                    <?php foreach ($color as $c): ?>
                                        <option value="<?= $c['id_color'] ?>">
                                            <?= htmlspecialchars($c['colores_disponibles']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Marca</label>
                                <select name="marca_id_marca" class="form-select" required>
                                    <option value="">-- Seleccionar --</option>
                                    <?php foreach ($marca as $m): ?>
                                        <option value="<?= $m['id_marca'] ?>">
                                            <?= htmlspecialchars($m['marcas_disponibles']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Categoría</label>
                                <select name="categoria_id_categoria" class="form-select" required>
                                    <option value="">-- Seleccionar --</option>
                                    <?php foreach ($categoria as $cat): ?>
                                        <option value="<?= $cat['id_categoria'] ?>">
                                            <?= htmlspecialchars($cat['tipodezapatilla']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <a href="index.php?action=productos" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Guardar Producto
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>