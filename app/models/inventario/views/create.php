<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Inventario - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Nuevo Registro de Inventario</h5>
                </div>
                <div class="card-body">
                    <form action="index.php?action=inventario_guardar" method="POST">

                        <div class="mb-3">
                            <label class="form-label">Producto</label>
                            <select name="producto_id_producto" class="form-select" required>
                                <option value="">-- Seleccionar producto --</option>
                                <?php foreach ($productos as $p): ?>
                                    <option value="<?= $p['id_producto'] ?>">
                                        <?= htmlspecialchars($p['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ubicación</label>
                            <input type="text" name="ubicacion" class="form-control"
                                placeholder="Ej: Depósito A - Estante 3" required>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Stock Actual</label>
                                <input type="number" name="stock_actual" class="form-control" min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Stock Mínimo</label>
                                <input type="number" name="stock_minimo" class="form-control" min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Stock Máximo</label>
                                <input type="number" name="stock_maximo" class="form-control" min="0" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="index.php?action=inventario" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Guardar
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