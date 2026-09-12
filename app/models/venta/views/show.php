<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle Venta - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>@media print { .no-print { display:none; } }</style>
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h2><i class="bi bi-receipt"></i> Comprobante #<?= $venta['id_venta'] ?></h2>
        <div>
            <a href="index.php?action=ventas" class="btn btn-outline-secondary btn-sm me-2">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <button onclick="window.print()" class="btn btn-dark btn-sm">
                <i class="bi bi-printer"></i> Imprimir
            </button>
            <?php if ($venta['estado'] !== 'cancelada'): ?>
            <a href="index.php?action=ventas_cancelar&id=<?= $venta['id_venta'] ?>"
            class="btn btn-danger btn-sm"
            onclick="return confirm('¿Seguro que querés cancelar esta venta? Se devolverá el stock de los productos.');">
                <i class="bi bi-x-circle"></i> Cancelar Venta
            </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($_GET['ok'])): ?>
        <div class="alert alert-success no-print">✅ Venta registrada con éxito.</div>
    <?php endif; ?>
    <?php if (isset($_GET['cancelada'])): ?>
        <div class="alert alert-warning no-print">⚠️ La venta fue cancelada y el stock fue restaurado.</div>
    <?php endif; ?>
    <?php if (($_GET['error'] ?? '') === 'ya_cancelada'): ?>
        <div class="alert alert-danger no-print">Esta venta ya se encuentra cancelada.</div>
    <?php endif; ?>
    <?php if (($_GET['error'] ?? '') === 'error_al_cancelar'): ?>
        <div class="alert alert-danger no-print">Ocurrió un error al cancelar la venta. Intentá de nuevo.</div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <strong>GamaStore — Comprobante de Venta</strong>
            <?php if ($venta['estado'] === 'cancelada'): ?>
                <span class="badge bg-danger">CANCELADA</span>
            <?php else: ?>
                <span class="badge bg-success">COMPLETADA</span>
            <?php endif; ?>
        </div>
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>N° Venta:</strong> #<?= $venta['id_venta'] ?></p>
                    <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($venta['fecha'])) ?></p>
                    <p><strong>Cliente:</strong> <?= htmlspecialchars($venta['nombre_cliente'] ?? 'Consumidor final') ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p><strong>Método de Pago:</strong> <?= htmlspecialchars($venta['metodo_de_pago']) ?></p>
                </div>
            </div>

            <table class="table table-bordered">
                <thead class="table-dark">
                <tr>
                    <th>Producto</th>
                    <th class="text-center">Descripción</th>
                    <th class="text-end">Precio Unit.</th>
                    <th class="text-end">Descuento</th>
                    <th class="text-end">Subtotal</th>
                    <th class="text-end">IVA</th>
                    <th class="text-end">Total</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalle as $d): ?>
                        <tr>
                            <td><?= htmlspecialchars($d['nombre_producto']) ?></td>
                            <td><?= htmlspecialchars($d['descripcion']) ?></td>
                            <td class="text-end">$<?= number_format($d['precio_producto'], 2) ?></td>
                            <td class="text-end">
                                <?php if ($d['descuento'] > 0): ?>
                                <span class="text-danger">-$<?= number_format($d['descuento'], 2) ?></span>
                                <?php else: ?>
                                —
                                <?php endif; ?>
                            </td>
                            <td class="text-end">$<?= number_format($d['total_venta'], 2) ?></td>
                            <td class="text-end"><?= number_format($d['IVA'] * 100, 0) ?>%</td>
                            <td class="text-end">$<?= number_format($d['precio_final'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="fw-bold fs-5">
                        <td colspan="5" class="text-end">TOTAL:</td>
                        <td class="text-end text-success">$<?= number_format($venta['total'], 2) ?></td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>