<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ventas - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-cart-check"></i> Ventas</h2>
        <div>
            <a href="index.php?action=dashboard" class="btn btn-outline-secondary btn-sm me-2">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="index.php?action=ventas_crear" class="btn btn-success">
                <i class="bi bi-plus-lg"></i> Nueva Venta
            </a>
        </div>
    </div>

    <?php if (isset($_GET['ok'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            Venta registrada con éxito.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th class="text-end">Total</th>
                        <th>Método de Pago</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($ventas)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No hay ventas registradas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($ventas as $v): ?>
                    <tr>
                        <td><?= $v['id_venta'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                        <td><?= htmlspecialchars($v['nombre_cliente'] ?? 'Consumidor final') ?></td>
                        <td class="text-end fw-bold">$<?= number_format($v['total'], 2) ?></td>
                        <td><?= htmlspecialchars($v['metodo_de_pago']) ?></td>
                        <td class="text-center">
                            <a href="index.php?action=ventas_ver&id=<?= $v['id_venta'] ?>"
                            class="btn btn-info btn-sm text-white" title="Ver detalle">
                                <i class="bi bi-eye"></i>
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