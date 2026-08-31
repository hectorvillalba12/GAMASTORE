<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-boxes"></i> Inventario</h2>
        <div>
            <a href="index.php?action=dashboard" class="btn btn-outline-secondary btn-sm me-2">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="index.php?action=inventario_crear" class="btn btn-success btn-sm">
                <i class="bi bi-plus-lg"></i> Nueva fila de inventario
            </a>
        </div>
    </div>

    <?php if (isset($_GET['ok'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php if ($_GET['ok'] === 'entrada'): ?>
                <i class="bi bi-box-arrow-in-down me-1"></i> Entrada de stock registrada correctamente.
            <?php elseif ($_GET['ok'] === 'salida'): ?>
                <i class="bi bi-box-arrow-up me-1"></i> Salida de stock registrada correctamente.
            <?php else: ?>
                Inventario actualizado correctamente.
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($stockBajo > 0): ?>
        <div class="alert alert-danger d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div>
                Hay <strong><?= $stockBajo ?></strong> producto(s) con stock igual o por debajo del mínimo.
                Revisá la columna "Estado" para identificarlos.
            </div>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Tipo</th>
                            <th>Talle</th>
                            <th>Color</th>
                            <th>Ubicación</th>
                            <th class="text-center">Stock Actual</th>
                            <th class="text-center">Mín.</th>
                            <th class="text-center">Máx.</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($inventarios)): ?>
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                No hay productos en el inventario.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($inventarios as $i): ?>
                        <?php
                            if ($i['stock_maximo'] > 0 && $i['stock_actual'] >= $i['stock_maximo']) {
                                $badge  = 'bg-warning text-dark';
                                $estado = 'Sobrestock';
                            } elseif ($i['stock_actual'] <= $i['stock_minimo']) {
                                $badge  = 'bg-danger';
                                $estado = 'Stock bajo';
                            } else {
                                $badge  = 'bg-success';
                                $estado = 'Normal';
                            }
                        ?>
                        <tr>
                            <td><?= $i['id_inventario'] ?></td>
                            <td><strong><?= htmlspecialchars($i['nombre_producto'] ?? '—') ?></strong></td>
                            <td><?= htmlspecialchars($i['tipodezapatillas'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($i['talle'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($i['color'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($i['ubicacion'] ?: '—') ?></td>
                            <td class="text-center fw-bold"><?= $i['stock_actual'] ?></td>
                            <td class="text-center"><?= $i['stock_minimo'] ?></td>
                            <td class="text-center"><?= $i['stock_maximo'] ?></td>
                            <td class="text-center">
                                <span class="badge <?= $badge ?>"><?= $estado ?></span>
                            </td>
                            <td class="text-center text-nowrap">
                                <a href="index.php?action=inventario_entrada&id=<?= $i['id_inventario'] ?>"
                                    class="btn btn-success btn-sm" title="Registrar entrada">
                                    <i class="bi bi-box-arrow-in-down"></i>
                                </a>
                                <a href="index.php?action=inventario_salida&id=<?= $i['id_inventario'] ?>"
                                    class="btn btn-danger btn-sm" title="Registrar salida">
                                    <i class="bi bi-box-arrow-up"></i>
                                </a>
                                <a href="index.php?action=inventario_editar&id=<?= $i['id_inventario'] ?>"
                                    class="btn btn-warning btn-sm" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="index.php?action=inventario_historial&id=<?= $i['id_inventario'] ?>"
                                    class="btn btn-outline-secondary btn-sm" title="Ver historial">
                                    <i class="bi bi-clock-history"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>