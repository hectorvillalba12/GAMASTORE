<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Inventario - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square"></i> 
                        Editar Stock — <?= htmlspecialchars($item['nombre_producto']) ?>
                    </h5>
                </div>
                <div class="card-body">

                    <?php if (isset($_GET['error']) && $_GET['error'] === 'stock_invalido'): ?>
                        <div class="alert alert-danger">
                            El stock máximo no puede ser menor al stock mínimo.
                        </div>
                    <?php endif; ?>

                    <form action="index.php?action=inventario_actualizar" method="POST">

                        <input type="hidden" name="id_inventario"        value="<?= $item['id_inventario'] ?>">
                        <input type="hidden" name="producto_id_producto" value="<?= $item['producto_id_producto'] ?>">

                        <!-- Info del producto (solo lectura) -->
                        <div class="mb-3">
                            <label class="form-label text-muted">Producto</label>
                            <input type="text" class="form-control" 
                                value="<?= htmlspecialchars($item['nombre_producto']) ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ubicación</label>
                            <input type="text" name="ubicacion" class="form-control"
                                value="<?= htmlspecialchars($item['ubicacion'] ?? '') ?>"
                                placeholder="Ej: Estante A3">
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Stock Actual</label>
                                <input type="number" name="stock_actual" class="form-control"
                                    value="<?= $item['stock_actual'] ?>" min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Stock Mínimo</label>
                                <input type="number" name="stock_minimo" class="form-control"
                                    value="<?= $item['stock_minimo'] ?>" min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Stock Máximo</label>
                                <input type="number" name="stock_maximo" class="form-control"
                                    value="<?= $item['stock_maximo'] ?>" min="0" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="index.php?action=inventario" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save"></i> Guardar cambios
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