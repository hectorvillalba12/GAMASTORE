<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-boxes"></i> Inventario</h2>
        <a href="index.php?action=dashboard" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <?php if (isset($_GET['ok'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            Inventario actualizado correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
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
                        <td class="text-center">
                            <a href="index.php?action=inventario_editar&id=<?= $i['id_inventario'] ?>"
                            class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>