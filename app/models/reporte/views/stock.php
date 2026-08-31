<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Stock Bajo - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>@media print { .no-print { display: none !important; } }</style>
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h2><i class="bi bi-exclamation-triangle"></i> Reporte de Stock Bajo</h2>
        <a href="index.php?action=reportes" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver a Reportes
        </a>
    </div>

    <div class="mb-3 no-print text-end">
        <a href="index.php?action=reportes_excel&tipo=stock" class="btn btn-outline-success btn-sm">
            <i class="bi bi-file-earmark-excel"></i> Exportar Excel
        </a>
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-printer"></i> Imprimir / PDF
        </button>
    </div>

    <p class="text-muted no-print">
        Este reporte es una foto del momento actual (no depende de un rango de fechas).
    </p>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-danger">
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th class="text-center">Stock actual</th>
                        <th class="text-center">Stock mínimo</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($productos)): ?>
                    <tr><td colspan="4" class="text-center text-muted py-4">
                        <i class="bi bi-check-circle text-success"></i> No hay productos con stock bajo. Todo en orden.
                    </td></tr>
                <?php else: ?>
                    <?php foreach ($productos as $p): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
                        <td><?= htmlspecialchars($p['categoria'] ?? '—') ?></td>
                        <td class="text-center text-danger fw-bold"><?= $p['stock_actual'] ?></td>
                        <td class="text-center"><?= $p['stock_minimo'] ?></td>
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