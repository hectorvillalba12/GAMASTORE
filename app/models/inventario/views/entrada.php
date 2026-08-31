<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Entrada - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-box-arrow-in-down"></i>
                        Registrar Entrada — <?= htmlspecialchars($item['nombre_producto']) ?>
                    </h5>
                </div>
                <div class="card-body">

                    <?php
                    $errores = [
                        'cantidad_invalida' => 'La cantidad debe ser mayor a cero.',
                        'motivo_requerido'  => 'Indicá el motivo de la entrada (ej: compra a proveedor).',
                    ];
                    if (isset($_GET['error']) && isset($errores[$_GET['error']])): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill"></i> <?= $errores[$_GET['error']] ?>
                        </div>
                    <?php endif; ?>

                    <p class="text-muted">
                        Stock actual: <strong><?= $item['stock_actual'] ?></strong> unidades
                    </p>

                    <form action="index.php?action=inventario_entrada_guardar" method="POST">

                        <input type="hidden" name="id_inventario" value="<?= $item['id_inventario'] ?>">

                        <div class="mb-3">
                            <label class="form-label">Cantidad que ingresa</label>
                            <input type="number" name="cantidad" class="form-control" min="1" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Motivo</label>
                            <input type="text" name="motivo" class="form-control"
                                placeholder="Ej: Compra a proveedor, devolución de cliente, etc." required>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="index.php?action=inventario" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Registrar Entrada
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